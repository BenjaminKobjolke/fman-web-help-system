<?php

declare(strict_types=1);

namespace App\Tests;

use App\Keybinding;
use App\KeybindingGenerator;
use PHPUnit\Framework\TestCase;

final class GeneratorTest extends TestCase
{
    private function generator(): KeybindingGenerator
    {
        // The fman checkout is wherever config/app.php points — this project no
        // longer lives inside the fman repo, so its location cannot be assumed.
        $config = require dirname(__DIR__) . '/config/app.php';
        $coreDir = (string) ($config['fman_core_dir'] ?? '');
        $doc = (string) ($config['fman_keybindings_doc'] ?? '');

        if (!is_dir($coreDir) || !is_file($doc)) {
            self::markTestSkipped(
                'fman source not found (fman_core_dir / fman_keybindings_doc in config/app.php).'
            );
        }

        return new KeybindingGenerator($coreDir, $doc);
    }

    /** @return Keybinding[] */
    private function generated(): array
    {
        return $this->generator()->generate();
    }

    private function find(array $bindings, string $platform, string $keys, string $command): ?Keybinding
    {
        foreach ($bindings as $b) {
            if ($b->platform === $platform && $b->keys === $keys && $b->command === $command) {
                return $b;
            }
        }

        return null;
    }

    public function testCommandDisplayNameFromAliases(): void
    {
        $b = $this->find($this->generated(), 'windows', 'Ctrl+Shift+P', 'command_palette');
        self::assertNotNull($b);
        self::assertSame('Command palette', $b->name);
    }

    public function testAliasMapUsesFirstAlias(): void
    {
        // Help class: aliases = ('Help', 'Show keyboard shortcuts', ...) -> first wins.
        $map = $this->generator()->commandAliasMap();
        self::assertSame('Help', $map['help'] ?? null);
        self::assertSame('Delete', $map['move_to_trash'] ?? null);
    }

    public function testArgHintExtendsSelection(): void
    {
        $b = $this->find($this->generated(), 'all', 'Shift+Down', 'move_cursor_down');
        self::assertNotNull($b);
        self::assertStringContainsString('extends selection', $b->name);
    }

    public function testFallbackHumanizesUnaliasedCommand(): void
    {
        // 'copy' (F5) has no aliases class -> humanized fallback "Copy".
        $b = $this->find($this->generated(), 'all', 'F5', 'copy');
        self::assertNotNull($b);
        self::assertSame('Copy', $b->name);
    }

    public function testViewerBindingsParsedFromMarkdown(): void
    {
        $viewer = array_values(array_filter(
            $this->generated(),
            static fn (Keybinding $b): bool => $b->platform === 'viewer'
        ));
        self::assertNotEmpty($viewer);

        $playPause = null;
        foreach ($viewer as $b) {
            if ($b->command === 'video_toggle_pause') {
                $playPause = $b;
                break;
            }
        }
        self::assertNotNull($playPause);
        self::assertSame('Space', $playPause->keys);
        self::assertStringContainsStringIgnoringCase('pause', $playPause->name);
    }
}
