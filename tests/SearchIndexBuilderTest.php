<?php

declare(strict_types=1);

namespace App\Tests;

use App\HelpArticle;
use App\Keybinding;
use App\SearchIndexBuilder;
use PHPUnit\Framework\TestCase;

final class SearchIndexBuilderTest extends TestCase
{
    /** @return list<array{type: string, title: string, subtitle: string, url: string, text: string}> */
    private function build(): array
    {
        $article = new HelpArticle(
            slug: 'window-title',
            title: 'Window title',
            summary: 'What the title shows.',
            order: 30,
            source: 'docs/WINDOW_TITLE.md',
            parent: '',
            html: '<p>Body text.</p>',
            headings: [['level' => 2, 'id' => 'format', 'text' => 'Format']],
            text: 'Body text.',
        );
        $binding = new Keybinding('Ctrl+Shift+P', 'command_palette', 'Command palette', 'windows');

        return (new SearchIndexBuilder())->build([$article], [$binding]);
    }

    public function testIndexesBothArticlesAndShortcuts(): void
    {
        $records = $this->build();

        self::assertCount(2, $records);
        self::assertSame(['help', 'shortcut'], array_column($records, 'type'));
    }

    public function testArticleRecordCarriesHeadingsButNotItsHtml(): void
    {
        $record = $this->build()[0];

        self::assertSame('window-title', $record['url']);
        self::assertStringContainsString('Format', $record['text']);
        self::assertArrayNotHasKey('html', $record);
        self::assertStringNotContainsString('<p>', $record['text']);
    }

    public function testShortcutRecordLinksToItsRowOnTheKeybindingsPage(): void
    {
        $record = $this->build()[1];

        self::assertSame('Command palette', $record['title']);
        self::assertSame('Ctrl+Shift+P', $record['subtitle']);
        self::assertSame('keybindings#kb-windows-command-palette-ctrl-shift-p', $record['url']);
    }

    public function testEmptyBindingsIndexesOnlyHelpArticles(): void
    {
        $article = new HelpArticle(
            slug: 'window-title',
            title: 'Window title',
            summary: 'What the title shows.',
            order: 30,
            source: 'docs/WINDOW_TITLE.md',
            parent: '',
            html: '<p>Body text.</p>',
            headings: [],
            text: 'Body text.',
        );

        $records = (new SearchIndexBuilder())->build([$article], []);

        self::assertCount(1, $records);
        self::assertSame(['help'], array_column($records, 'type'));
    }
}
