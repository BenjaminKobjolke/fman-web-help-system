<?php

declare(strict_types=1);

namespace App;

/**
 * One end-user help page: the front matter from content/help/<slug>.md plus the
 * HTML the generator rendered from its body, and the flattened text the search
 * index is built from. Typed value object so decoded JSON never leaks onward as
 * a bag-of-keys array.
 */
final class HelpArticle
{
    /**
     * @param list<array{level: int, id: string, text: string}> $headings
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $summary,
        public readonly int $order,
        public readonly string $source,
        public readonly string $html,
        public readonly array $headings,
        public readonly string $text,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromArray(array $row): self
    {
        $headings = [];
        if (isset($row['headings']) && is_array($row['headings'])) {
            foreach ($row['headings'] as $heading) {
                if (!is_array($heading)) {
                    continue;
                }
                $headings[] = [
                    'level' => (int) ($heading['level'] ?? 2),
                    'id' => (string) ($heading['id'] ?? ''),
                    'text' => (string) ($heading['text'] ?? ''),
                ];
            }
        }

        return new self(
            slug: (string) ($row['slug'] ?? ''),
            title: (string) ($row['title'] ?? ''),
            summary: (string) ($row['summary'] ?? ''),
            order: (int) ($row['order'] ?? 0),
            source: (string) ($row['source'] ?? ''),
            html: (string) ($row['html'] ?? ''),
            headings: $headings,
            text: (string) ($row['text'] ?? ''),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'order' => $this->order,
            'source' => $this->source,
            'html' => $this->html,
            'headings' => $this->headings,
            'text' => $this->text,
        ];
    }
}
