<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Loads the generated help.json and exposes it as typed HelpArticle objects.
 * Mirrors KeybindingRepository — the served site never reads content/ markdown.
 */
final class HelpRepository
{
    /** @var HelpArticle[]|null */
    private ?array $articles = null;

    public function __construct(private readonly string $jsonPath)
    {
    }

    /**
     * @return HelpArticle[] in display order (order, then title)
     */
    public function all(): array
    {
        if ($this->articles !== null) {
            return $this->articles;
        }

        if (!is_file($this->jsonPath)) {
            throw new RuntimeException(
                'Help data not found: ' . $this->jsonPath . ' — run tools/generate.php first.'
            );
        }

        $decoded = json_decode((string) file_get_contents($this->jsonPath), true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Help data is not valid JSON: ' . $this->jsonPath);
        }

        $articles = array_map(
            static fn (array $row): HelpArticle => HelpArticle::fromArray($row),
            $decoded
        );

        usort(
            $articles,
            static fn (HelpArticle $a, HelpArticle $b): int => [$a->order, $a->title] <=> [$b->order, $b->title]
        );

        return $this->articles = $articles;
    }

    public function bySlug(string $slug): ?HelpArticle
    {
        foreach ($this->all() as $article) {
            if ($article->slug === $slug) {
                return $article;
            }
        }

        return null;
    }
}
