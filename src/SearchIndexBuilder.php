<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Flattens help articles and keybindings into the one small JSON file the
 * site-wide search box fetches. Article HTML is deliberately left out: the
 * index is downloaded by every visitor who focuses the search box, the rendered
 * markup is not needed to match against.
 *
 * ponytail: one flat file, fetched once and browser-cached across pages. If the
 * article count ever makes it heavy, split it per section and fetch on demand.
 */
final class SearchIndexBuilder
{
    /**
     * @param HelpArticle[] $articles
     * @param Keybinding[]  $bindings
     *
     * @return list<array{type: string, title: string, subtitle: string, url: string, text: string}>
     */
    public function build(array $articles, array $bindings): array
    {
        $records = [];

        foreach ($articles as $article) {
            $headings = implode(' ', array_column($article->headings, 'text'));
            $records[] = [
                'type' => 'help',
                'title' => $article->title,
                'subtitle' => $article->summary,
                'url' => $article->slug,
                'text' => trim($article->summary . ' ' . $headings . ' ' . $article->text),
            ];
        }

        foreach ($bindings as $binding) {
            $records[] = [
                'type' => 'shortcut',
                'title' => $binding->name,
                'subtitle' => $binding->keys,
                'url' => 'keybindings#' . $binding->id(),
                'text' => trim($binding->command . ' ' . $binding->keys . ' ' . $binding->platform),
            ];
        }

        return $records;
    }

    /**
     * @param list<array{type: string, title: string, subtitle: string, url: string, text: string}> $records
     */
    public function writeJson(array $records, string $outPath): void
    {
        $dir = dirname($outPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0o775, true);
        }

        $json = json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Could not encode the search index as JSON.');
        }

        file_put_contents($outPath, $json . "\n");
    }
}
