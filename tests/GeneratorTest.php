<?php

declare(strict_types=1);

namespace App\Tests;

use App\Keybinding;
use App\KeybindingGenerator;
use PHPUnit\Framework\TestCase;

/**
 * Hermetic: runs against tests/fixtures/, not a real fman checkout, so the
 * standalone repo's suite passes anywhere. Real generation is exercised
 * separately by running tools/generate_keybindings.php against a local fman.
 */
final class GeneratorTest extends TestCase
{
    private function generator(): KeybindingGenerator
    {
        $fixtures = __DIR__ . '/fixtures';

        return new KeybindingGenerator($fixtures . '/Core', $fixtures . '/KEYBINDINGS.md');
    }

    /** @return Keybinding[] */
    private function generated(): array
    {
        return $this->generator()->generate();
    }

    /**
     * @param Keybinding[] $bindings
     */
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
        $map = $this->generator()->commandAliasMap();
        self::assertSame('Move cursor down', $map['move_cursor_down'] ?? null);
    }

    public function testArgHintExtendsSelection(): void
    {
        $b = $this->find($this->generated(), 'all', 'Shift+Down', 'move_cursor_down');
        self::assertNotNull($b);
        self::assertStringContainsString('extends selection', $b->name);
    }

    public function testFallbackHumanizesUnaliasedCommand(): void
    {
        // 'copy' has no aliases class in the fixture -> humanized fallback "Copy".
        $b = $this->find($this->generated(), 'all', 'F5', 'copy');
        self::assertNotNull($b);
        self::assertSame('Copy', $b->name);
    }

    public function testViewerBindingsParsedFromMarkdown(): void
    {
        $b = $this->find($this->generated(), 'viewer', 'Space', 'video_toggle_pause');
        self::assertNotNull($b);
        self::assertStringContainsStringIgnoringCase('pause', $b->name);
    }

    public function testUnboundViewerCommandHasDashKeys(): void
    {
        // `video_mute` default key is *(none)* in the fixture -> em dash placeholder.
        $b = $this->find($this->generated(), 'viewer', '—', 'video_mute');
        self::assertNotNull($b);
    }
}
