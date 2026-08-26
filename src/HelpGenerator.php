<?php

declare(strict_types=1);

namespace App;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use RuntimeException;

/**
 * Turns the hand-written (or skill-written) markdown in content/help/ into the
 * public/data/help.json the site serves. Same generate-and-commit shape as
 * KeybindingGenerator: markdown is parsed here, never at request time.
 */
final class HelpGenerator
{
    /** Route names the router claims before it looks up an article. */
    public const RESERVED_SLUGS = ['keybindings', 'assets', 'data', 'index'];

    private const REQUIRED_FRONT_MATTER = ['title', 'summary'];

    public function __construct(private readonly string $contentDir)
    {
    }

    /**
     * @return HelpArticle[] sorted by order, then title
     */
    public function generate(): array
    {
        if (!is_dir($this->contentDir)) {
            throw new RuntimeException('Help content directory not found: ' . $this->contentDir);
        }

        $files = glob(rtrim($this->contentDir, '/\\') . '/*.md');
        if ($files === false) {
            throw new RuntimeException('Could not read help content directory: ' . $this->contentDir);
        }
        sort($files);

        $articles = [];
        $seen = [];
        foreach ($files as $file) {
            $article = $this->parseFile($file);
            if (isset($seen[$article->slug])) {
                throw new RuntimeException(
                    'Duplicate help slug "' . $article->slug . '": '
                    . $seen[$article->slug] . ' and ' . $file
                );
            }
            $seen[$article->slug] = $file;
            $articles[] = $article;
        }

        $this->assertParents($articles, $seen);

        usort(
            $articles,
            static fn (HelpArticle $a, HelpArticle $b): int => [$a->order, $a->title] <=> [$b->order, $b->title]
        );

        return $articles;
    }

    private function parseFile(string $file): HelpArticle
    {
        $slug = basename($file, '.md');
        $this->assertSlug($slug, $file);

        $raw = (string) file_get_contents($file);
        [$meta, $body] = $this->splitFrontMatter($raw, $file);

        foreach (self::REQUIRED_FRONT_MATTER as $key) {
            if (($meta[$key] ?? '') === '') {
                throw new RuntimeException(
                    'Help article ' . $file . ' is missing front matter key "' . $key . '".'
                );
            }
        }

        $html = $this->addHeadingIds($this->markdownToHtml($body));

        return new HelpArticle(
            slug: $slug,
            title: $meta['title'],
            summary: $meta['summary'],
            order: (int) ($meta['order'] ?? 0),
            source: $meta['source'] ?? '',
            parent: $meta['parent'] ?? '',
            html: $html,
            headings: $this->extractHeadings($html),
            text: $this->plainText($html),
        );
    }

    /**
     * Subpages are one level deep on purpose: a parent article lists its
     * children, and a child links back. Nesting deeper would need a real tree
     * in the templates for no reader benefit.
     *
     * @param HelpArticle[]         $articles
     * @param array<string, string> $files slug => source file
     */
    private function assertParents(array $articles, array $files): void
    {
        $parents = [];
        foreach ($articles as $article) {
            $parents[$article->slug] = $article->parent;
        }

        foreach ($articles as $article) {
            if ($article->parent === '') {
                continue;
            }
            if (!isset($parents[$article->parent])) {
                throw new RuntimeException(
                    'Help article ' . $files[$article->slug] . ' has parent "' . $article->parent
                    . '", which is not an article.'
                );
            }
            if ($article->parent === $article->slug) {
                throw new RuntimeException(
                    'Help article ' . $files[$article->slug] . ' is its own parent.'
                );
            }
            if ($parents[$article->parent] !== '') {
                throw new RuntimeException(
                    'Help article ' . $files[$article->slug] . ' nests under "' . $article->parent
                    . '", which is itself a subpage. Subpages are only one level deep.'
                );
            }
        }
    }

    private function assertSlug(string $slug, string $file): void
    {
        if (preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) !== 1) {
            throw new RuntimeException(
                'Help article filename must be a lowercase kebab-case slug: ' . $file
            );
        }
        if (in_array($slug, self::RESERVED_SLUGS, true)) {
            throw new RuntimeException(
                'Help slug "' . $slug . '" is reserved by the router: ' . $file
            );
        }
    }

    /**
     * The front matter is a flat "key: value" block — no nesting, no lists — so
     * a YAML dependency would never earn its keep.
     *
     * @return array{0: array<string, string>, 1: string}
     */
    private function splitFrontMatter(string $raw, string $file): array
    {
        $normalised = str_replace("\r\n", "\n", $raw);
        if (!str_starts_with($normalised, "---\n")) {
            throw new RuntimeException(
                'Help article ' . $file . ' does not start with a "---" front matter block.'
            );
        }

        $end = strpos($normalised, "\n---", 3);
        if ($end === false) {
            throw new RuntimeException('Help article ' . $file . ' has an unterminated front matter block.');
        }

        $header = substr($normalised, 4, $end - 3);
        $body = ltrim(substr($normalised, $end + 4), "\n");

        $meta = [];
        foreach (explode("\n", $header) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = explode(':', $line, 2);
            if (count($parts) !== 2) {
                throw new RuntimeException('Bad front matter line in ' . $file . ': ' . $line);
            }
            $meta[trim($parts[0])] = trim($parts[1]);
        }

        return [$meta, $body];
    }

    private function markdownToHtml(string $markdown): string
    {
        $environment = new Environment([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        // Articles compare viewers and platforms in tables; core CommonMark has none.
        $environment->addExtension(new TableExtension());

        return trim((string) (new MarkdownConverter($environment))->convert($markdown));
    }

    /** Give h2/h3 stable ids so the on-page contents list can link to them. */
    private function addHeadingIds(string $html): string
    {
        $used = [];

        return (string) preg_replace_callback(
            '#<h([23])>(.*?)</h\1>#s',
            static function (array $m) use (&$used): string {
                $text = html_entity_decode(strip_tags($m[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $id = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');
                if ($id === '') {
                    $id = 'section';
                }
                $used[$id] = ($used[$id] ?? 0) + 1;
                if ($used[$id] > 1) {
                    $id .= '-' . $used[$id];
                }

                return '<h' . $m[1] . ' id="' . $id . '">' . $m[2] . '</h' . $m[1] . '>';
            },
            $html
        );
    }

    /**
     * @return list<array{level: int, id: string, text: string}>
     */
    private function extractHeadings(string $html): array
    {
        preg_match_all('#<h([23]) id="([^"]+)">(.*?)</h\1>#s', $html, $matches, PREG_SET_ORDER);

        $headings = [];
        foreach ($matches as $match) {
            $headings[] = [
                'level' => (int) $match[1],
                'id' => $match[2],
                'text' => trim(html_entity_decode(strip_tags($match[3]), ENT_QUOTES | ENT_HTML5, 'UTF-8')),
            ];
        }

        return $headings;
    }

    /** Flattened body text — what the search index matches against. */
    private function plainText(string $html): string
    {
        $spaced = (string) preg_replace('#<(?:/p|/li|/h[1-6]|br\s*/?)>#', ' ', $html);
        $text = html_entity_decode(strip_tags($spaced), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * @param HelpArticle[] $articles
     */
    public function writeJson(array $articles, string $outPath): void
    {
        $dir = dirname($outPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0o775, true);
        }

        $payload = array_map(static fn (HelpArticle $a): array => $a->toArray(), $articles);
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Could not encode help articles as JSON.');
        }

        file_put_contents($outPath, $json . "\n");
    }
}
