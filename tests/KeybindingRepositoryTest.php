<?php

declare(strict_types=1);

namespace App\Tests;

use App\Keybinding;
use App\KeybindingRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class KeybindingRepositoryTest extends TestCase
{
    private string $tmpFile;

    protected function setUp(): void
    {
        $this->tmpFile = tempnam(sys_get_temp_dir(), 'kb_') . '.json';
        file_put_contents($this->tmpFile, json_encode([
            ['keys' => 'F5', 'command' => 'copy', 'name' => 'Copy', 'platform' => 'all', 'args' => null],
            ['keys' => 'Ctrl+V', 'command' => 'paste', 'name' => 'Paste', 'platform' => 'windows', 'args' => null],
            ['keys' => 'Space', 'command' => 'video_toggle_pause', 'name' => 'Play / pause (video)', 'platform' => 'viewer', 'args' => null],
            ['keys' => 'Shift+Down', 'command' => 'move_cursor_down', 'name' => 'Move cursor down (extends selection)', 'platform' => 'all', 'args' => ['toggle_selection' => true]],
        ]));
    }

    protected function tearDown(): void
    {
        @unlink($this->tmpFile);
    }

    public function testAllReturnsValueObjects(): void
    {
        $repo = new KeybindingRepository($this->tmpFile);
        $all = $repo->all();

        self::assertCount(4, $all);
        self::assertInstanceOf(Keybinding::class, $all[0]);
        self::assertSame('copy', $all[0]->command);
        self::assertSame('Copy', $all[0]->name);
        self::assertSame(['toggle_selection' => true], $all[3]->args);
    }

    public function testGroupedByPlatformOrder(): void
    {
        $repo = new KeybindingRepository($this->tmpFile);
        $groups = $repo->groupedByPlatform();

        // 'all' before 'windows' before 'viewer' per PLATFORM_ORDER; 'mac'/'linux' absent.
        self::assertSame(['all', 'windows', 'viewer'], array_keys($groups));
        self::assertCount(2, $groups['all']);
    }

    public function testStableRowId(): void
    {
        $b = new Keybinding('Ctrl+Shift+P', 'command_palette', 'Command palette', 'windows', null);
        self::assertSame('kb-windows-command-palette-ctrl-shift-p', $b->id());
    }

    public function testMissingFileThrows(): void
    {
        $repo = new KeybindingRepository(sys_get_temp_dir() . '/does-not-exist-kb.json');
        $this->expectException(RuntimeException::class);
        $repo->all();
    }
}
