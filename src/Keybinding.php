<?php

declare(strict_types=1);

namespace App;

/**
 * One keyboard shortcut: which keys, the fman command it runs, a human-readable
 * name, the platform it applies to, and any command args. Typed value object so
 * the decoded JSON never leaks as a bag-of-keys array past this boundary.
 */
final class Keybinding
{
    /**
     * @param array<string, mixed>|null $args
     */
    public function __construct(
        public readonly string $keys,
        public readonly string $command,
        public readonly string $name,
        public readonly string $platform,
        public readonly ?array $args = null,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromArray(array $row): self
    {
        return new self(
            keys: (string) ($row['keys'] ?? ''),
            command: (string) ($row['command'] ?? ''),
            name: (string) ($row['name'] ?? ''),
            platform: (string) ($row['platform'] ?? ''),
            args: isset($row['args']) && is_array($row['args']) ? $row['args'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'keys' => $this->keys,
            'command' => $this->command,
            'name' => $this->name,
            'platform' => $this->platform,
            'args' => $this->args,
        ];
    }

    /** Stable DOM id so JS can show/hide the server-rendered row for this binding. */
    public function id(): string
    {
        $raw = $this->platform . '-' . $this->command . '-' . $this->keys;

        return 'kb-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower($raw));
    }
}
