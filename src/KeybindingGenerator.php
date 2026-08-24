<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Builds the keybindings dataset from fman's own source files (single source of
 * truth). File-list bindings come from the authoritative Key Bindings*.json;
 * command display names from the Python command classes' `aliases`; viewer
 * bindings from the curated docs/KEYBINDINGS.md (no shipped Viewer JSON exists).
 */
final class KeybindingGenerator
{
    /** Key Bindings file name => platform tag. */
    private const PLATFORM_FILES = [
        'Key Bindings.json' => 'all',
        'Key Bindings (Windows).json' => 'windows',
        'Key Bindings (Mac).json' => 'mac',
        'Key Bindings (Linux).json' => 'linux',
    ];

    public function __construct(
        private readonly string $coreDir,
        private readonly string $keybindingsDoc,
    ) {
    }

    /**
     * @return Keybinding[]
     */
    public function generate(): array
    {
        $aliases = $this->commandAliasMap();
        $bindings = $this->fileListBindings($aliases);
        foreach ($this->viewerBindings() as $viewer) {
            $bindings[] = $viewer;
        }

        return $bindings;
    }

    /**
     * @param Keybinding[] $bindings
     */
    public function writeJson(array $bindings, string $outPath): void
    {
        $rows = array_map(static fn (Keybinding $b): array => $b->toArray(), $bindings);
        $json = json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Failed to encode keybindings to JSON.');
        }
        if (file_put_contents($outPath, $json . "\n") === false) {
            throw new RuntimeException('Failed to write ' . $outPath);
        }
    }

    /**
     * command name (snake_case) => primary human-readable alias.
     *
     * @return array<string, string>
     */
    public function commandAliasMap(): array
    {
        $dir = $this->coreDir . '/core/commands';
        $map = [];
        foreach (glob($dir . '/*.py') ?: [] as $file) {
            $content = (string) file_get_contents($file);
            if (!preg_match_all('/^class\s+(\w+)\s*\(/m', $content, $classes, PREG_OFFSET_CAPTURE)) {
                continue;
            }
            $count = count($classes[1]);
            for ($i = 0; $i < $count; $i++) {
                $className = $classes[1][$i][0];
                $start = (int) $classes[0][$i][1];
                $end = $i + 1 < $count ? (int) $classes[0][$i + 1][1] : strlen($content);
                $body = substr($content, $start, $end - $start);
                if (!preg_match('/\baliases\s*=\s*\(([^)]*)\)/s', $body, $tuple)) {
                    continue;
                }
                if (!preg_match('/[\'"]([^\'"]+)[\'"]/', $tuple[1], $first)) {
                    continue;
                }
                $command = $this->camelToSnake($className);
                $map[$command] ??= $first[1];
            }
        }

        return $map;
    }

    /**
     * @param array<string, string> $aliases
     * @return Keybinding[]
     */
    private function fileListBindings(array $aliases): array
    {
        $bindings = [];
        foreach (self::PLATFORM_FILES as $fileName => $platform) {
            $path = $this->coreDir . '/' . $fileName;
            if (!is_file($path)) {
                continue;
            }
            $entries = json_decode((string) file_get_contents($path), true);
            if (!is_array($entries)) {
                throw new RuntimeException('Invalid JSON in ' . $path);
            }
            foreach ($entries as $entry) {
                if (!is_array($entry) || empty($entry['keys']) || empty($entry['command'])) {
                    continue;
                }
                $command = (string) $entry['command'];
                $args = isset($entry['args']) && is_array($entry['args']) ? $entry['args'] : null;
                $name = ($aliases[$command] ?? $this->humanize($command)) . $this->argHint($args);
                $bindings[] = new Keybinding(
                    keys: (string) $entry['keys'][0],
                    command: $command,
                    name: $name,
                    platform: $platform,
                    args: $args,
                );
            }
        }

        return $bindings;
    }

    /**
     * Parse the "Bindable viewer commands" markdown table in docs/KEYBINDINGS.md.
     *
     * @return Keybinding[]
     */
    public function viewerBindings(): array
    {
        if (!is_file($this->keybindingsDoc)) {
            throw new RuntimeException('Keybindings doc not found: ' . $this->keybindingsDoc);
        }
        $lines = preg_split('/\r?\n/', (string) file_get_contents($this->keybindingsDoc)) ?: [];

        $inSection = false;
        $rows = [];
        foreach ($lines as $line) {
            if (str_starts_with($line, '### Bindable viewer commands')) {
                $inSection = true;
                continue;
            }
            if (!$inSection) {
                continue;
            }
            $trimmed = trim($line);
            if (str_starts_with($trimmed, '|')) {
                $rows[] = $trimmed;
            } elseif ($rows !== []) {
                break; // table ended
            }
        }

        $bindings = [];
        foreach ($rows as $index => $row) {
            $cells = array_map('trim', explode('|', trim($row, '|')));
            if ($index < 2 || count($cells) < 4) {
                continue; // header row + separator row
            }
            $command = $this->cleanCell($cells[0]);
            $scope = $this->cleanCell($cells[1]);
            $key = $this->cleanCell($cells[2]);
            $action = $this->cleanCell($cells[3]);
            if ($command === '' || str_starts_with($command, '---')) {
                continue;
            }
            $scopeTag = preg_match('/^\w+/', $scope, $m) ? $m[0] : '';
            $name = $action . ($scopeTag !== '' ? ' (' . $scopeTag . ')' : '');
            $bindings[] = new Keybinding(
                keys: $key === '' ? '—' : $key,
                command: $command,
                name: $name,
                platform: 'viewer',
                args: null,
            );
        }

        return $bindings;
    }

    private function cleanCell(string $cell): string
    {
        $cell = str_replace('`', '', $cell);
        // markdown emphasis / "(none)" placeholder for unbound commands
        $cell = trim($cell, '*');
        if (stripos($cell, '(none)') !== false) {
            return '';
        }

        return trim($cell);
    }

    /** Mirror fman's plugin.py::_get_command_name CamelCase -> snake_case. */
    private function camelToSnake(string $name): string
    {
        return strtolower((string) preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }

    /** Fallback display name, mirroring fman's _get_default_aliases behaviour. */
    private function humanize(string $command): string
    {
        return ucfirst(str_replace('_', ' ', $command));
    }

    /**
     * @param array<string, mixed>|null $args
     */
    private function argHint(?array $args): string
    {
        if ($args === null) {
            return '';
        }
        if (($args['toggle_selection'] ?? null) === true) {
            return ' (extends selection)';
        }
        if (isset($args['pane_index'])) {
            return ((int) $args['pane_index'] === 0) ? ' (left pane)' : ' (right pane)';
        }
        if (isset($args['column_index'])) {
            $columns = [0 => 'by name', 1 => 'by size', 2 => 'by date'];
            $col = $columns[(int) $args['column_index']] ?? 'column';

            return ' (' . $col . ')';
        }

        return '';
    }
}
