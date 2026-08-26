<?php

declare(strict_types=1);

namespace App\Tests;

use App\HelpGenerator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HelpGeneratorTest extends TestCase
{
    private string $dir;

    protected function setUp(): void
    {
        $this->dir = sys_get_temp_dir() . '/help-gen-' . bin2hex(random_bytes(6));
        mkdir($this->dir, 0o775, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->dir . '/*') ?: [] as $file) {
            unlink($file);
        }
        if (is_dir($this->dir)) {
            rmdir($this->dir);
        }
    }

    private function write(string $name, string $contents): void
    {
        file_put_contents($this->dir . '/' . $name, $contents);
    }

    private function article(string $body = "Intro text.\n\n## First heading\n\nBody.\n"): string
    {
        return "---\ntitle: Window title\nsummary: What the title shows.\norder: 30\n"
            . "source: docs/WINDOW_TITLE.md\n---\n\n" . $body;
    }

    public function testParsesFrontMatterAndBody(): void
    {
        $this->write('window-title.md', $this->article());

        $articles = (new HelpGenerator($this->dir))->generate();

        self::assertCount(1, $articles);
        self::assertSame('window-title', $articles[0]->slug);
        self::assertSame('Window title', $articles[0]->title);
        self::assertSame('What the title shows.', $articles[0]->summary);
        self::assertSame(30, $articles[0]->order);
        self::assertSame('docs/WINDOW_TITLE.md', $articles[0]->source);
        self::assertStringContainsString('<p>Intro text.</p>', $articles[0]->html);
    }

    public function testHeadingsGetIdsAndAreExtracted(): void
    {
        $this->write('window-title.md', $this->article("## Format\n\nText.\n\n### Notes on it\n\nMore.\n"));

        $article = (new HelpGenerator($this->dir))->generate()[0];

        self::assertStringContainsString('<h2 id="format">Format</h2>', $article->html);
        self::assertSame(
            [
                ['level' => 2, 'id' => 'format', 'text' => 'Format'],
                ['level' => 3, 'id' => 'notes-on-it', 'text' => 'Notes on it'],
            ],
            $article->headings
        );
    }

    public function testRepeatedHeadingTextGetsUniqueIds(): void
    {
        $this->write('window-title.md', $this->article("## Notes\n\nA.\n\n## Notes\n\nB.\n"));

        $article = (new HelpGenerator($this->dir))->generate()[0];

        self::assertSame(['notes', 'notes-2'], array_column($article->headings, 'id'));
    }

    public function testTablesAreRendered(): void
    {
        $this->write('window-title.md', $this->article("| A | B |\n|---|---|\n| 1 | 2 |\n"));

        self::assertStringContainsString('<table>', (new HelpGenerator($this->dir))->generate()[0]->html);
    }

    public function testPlainTextKeepsWordsApartAcrossBlocks(): void
    {
        $this->write('window-title.md', $this->article("First para.\n\n## Second\n\nThird.\n"));

        $text = (new HelpGenerator($this->dir))->generate()[0]->text;

        self::assertStringContainsString('First para. Second Third.', $text);
    }

    public function testArticlesAreSortedByOrder(): void
    {
        $this->write('window-title.md', $this->article());
        $this->write(
            'command-palette.md',
            "---\ntitle: Command palette\nsummary: Run commands by name.\norder: 10\n---\n\nText.\n"
        );

        $slugs = array_map(
            static fn ($a) => $a->slug,
            (new HelpGenerator($this->dir))->generate()
        );

        self::assertSame(['command-palette', 'window-title'], $slugs);
    }

    public function testReservedSlugIsRejected(): void
    {
        $this->write('keybindings.md', $this->article());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('reserved');
        (new HelpGenerator($this->dir))->generate();
    }

    public function testNonKebabCaseFilenameIsRejected(): void
    {
        $this->write('Window_Title.md', $this->article());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('kebab-case');
        (new HelpGenerator($this->dir))->generate();
    }

    public function testMissingFrontMatterIsRejected(): void
    {
        $this->write('window-title.md', "## No front matter\n\nBody.\n");

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('front matter');
        (new HelpGenerator($this->dir))->generate();
    }

    public function testMissingRequiredKeyIsRejected(): void
    {
        $this->write('window-title.md', "---\ntitle: Window title\n---\n\nBody.\n");

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('summary');
        (new HelpGenerator($this->dir))->generate();
    }

    public function testRawHtmlInMarkdownIsEscaped(): void
    {
        $this->write('window-title.md', $this->article("<script>alert(1)</script>\n"));

        self::assertStringNotContainsString(
            '<script>',
            (new HelpGenerator($this->dir))->generate()[0]->html
        );
    }
}
