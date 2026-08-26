<?php

declare(strict_types=1);

namespace App\Tests;

use App\HelpRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class HelpRepositoryTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = sys_get_temp_dir() . '/help-repo-' . bin2hex(random_bytes(6)) . '.json';
    }

    protected function tearDown(): void
    {
        if (is_file($this->file)) {
            unlink($this->file);
        }
    }

    private function seed(): void
    {
        file_put_contents($this->file, json_encode([
            ['slug' => 'window-title', 'title' => 'Window title', 'summary' => 'B', 'order' => 30, 'html' => '<p>b</p>'],
            ['slug' => 'command-palette', 'title' => 'Command palette', 'summary' => 'A', 'order' => 10, 'html' => '<p>a</p>'],
        ]));
    }

    public function testAllIsSortedByOrder(): void
    {
        $this->seed();

        $slugs = array_map(
            static fn ($a) => $a->slug,
            (new HelpRepository($this->file))->all()
        );

        self::assertSame(['command-palette', 'window-title'], $slugs);
    }

    public function testTopLevelSkipsSubpagesAndChildrenAreFoundByParent(): void
    {
        file_put_contents($this->file, json_encode([
            ['slug' => 'file-viewers', 'title' => 'File viewers', 'summary' => 'A', 'order' => 40],
            ['slug' => 'text-viewer', 'title' => 'Text viewer', 'summary' => 'B', 'order' => 50, 'parent' => 'file-viewers'],
            ['slug' => 'image-viewer', 'title' => 'Image viewer', 'summary' => 'C', 'order' => 60, 'parent' => 'file-viewers'],
        ]));
        $repo = new HelpRepository($this->file);

        self::assertSame(
            ['file-viewers'],
            array_map(static fn ($a) => $a->slug, $repo->topLevel())
        );
        self::assertSame(
            ['text-viewer', 'image-viewer'],
            array_map(static fn ($a) => $a->slug, $repo->childrenOf('file-viewers'))
        );
    }

    public function testBySlugFindsAnArticle(): void
    {
        $this->seed();

        $article = (new HelpRepository($this->file))->bySlug('window-title');

        self::assertNotNull($article);
        self::assertSame('Window title', $article->title);
    }

    public function testBySlugReturnsNullForAnUnknownSlug(): void
    {
        $this->seed();

        self::assertNull((new HelpRepository($this->file))->bySlug('nope'));
    }

    public function testMissingFileTellsYouToRunTheGenerator(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('tools/generate.php');
        (new HelpRepository($this->file))->all();
    }
}
