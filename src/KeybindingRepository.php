<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Loads the generated keybindings.json and exposes it as typed Keybinding
 * objects, grouped by platform in a stable display order.
 */
final class KeybindingRepository
{
    /** Display order for platform sections. */
    public const PLATFORM_ORDER = ['all', 'windows', 'mac', 'linux', 'viewer'];

    public function __construct(private readonly string $jsonPath)
    {
    }

    /**
     * @return Keybinding[]
     */
    public function all(): array
    {
        if (!is_file($this->jsonPath)) {
            throw new RuntimeException(
                'Keybindings data not found: ' . $this->jsonPath
                . ' — run tools/generate_keybindings.php first.'
            );
        }

        $raw = (string) file_get_contents($this->jsonPath);
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Keybindings data is not valid JSON: ' . $this->jsonPath);
        }

        return array_map(
            static fn (array $row): Keybinding => Keybinding::fromArray($row),
            $decoded
        );
    }

    /**
     * @return array<string, Keybinding[]> platform => bindings, in PLATFORM_ORDER
     */
    public function groupedByPlatform(): array
    {
        $groups = [];
        foreach ($this->all() as $binding) {
            $groups[$binding->platform][] = $binding;
        }

        $ordered = [];
        foreach (self::PLATFORM_ORDER as $platform) {
            if (!empty($groups[$platform])) {
                $ordered[$platform] = $groups[$platform];
            }
        }
        // Any platform not in the known order still gets shown (defensive).
        foreach ($groups as $platform => $bindings) {
            if (!isset($ordered[$platform])) {
                $ordered[$platform] = $bindings;
            }
        }

        return $ordered;
    }
}
