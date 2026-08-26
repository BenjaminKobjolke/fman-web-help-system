<?php

declare(strict_types=1);

namespace App;

use PhpLocalization\Localization;
use Twig\Environment;

/**
 * Small dependency container: builds and caches the app's collaborators from the
 * plain-PHP config file. No framework — this is a single-page help site.
 */
final class Container
{
    /** @var array<string, mixed> */
    private array $config;
    private ?Logger $logger = null;
    private ?Localization $localization = null;
    private ?Environment $twig = null;
    private ?KeybindingRepository $keybindings = null;
    private ?HelpRepository $help = null;

    public function __construct(private readonly string $baseDir)
    {
        $this->config = require $baseDir . '/config/app.php';
    }

    /** @return array<string, mixed> */
    public function config(): array
    {
        return $this->config;
    }

    public function logger(): Logger
    {
        if ($this->logger === null) {
            $logFile = (string) ($this->config['log_file'] ?? $this->baseDir . '/var/app.log');
            $dir = dirname($logFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0o775, true);
            }
            $this->logger = new Logger($logFile, (string) ($this->config['log_level'] ?? 'info'));
        }

        return $this->logger;
    }

    public function localization(): Localization
    {
        if ($this->localization === null) {
            $this->localization = new Localization([
                'driver' => 'json',
                'langDir' => $this->baseDir . '/lang/',
                'defaultLang' => (string) ($this->config['default_lang'] ?? 'en'),
                'fallBackLang' => 'en',
            ]);
        }

        return $this->localization;
    }

    public function twig(): Environment
    {
        if ($this->twig === null) {
            $factory = new TwigFactory($this->baseDir . '/templates');
            $this->twig = $factory->create(
                $this->localization(),
                (bool) ($this->config['debug'] ?? false),
                $this->basePath(),
            );
        }

        return $this->twig;
    }

    public function keybindingRepository(): KeybindingRepository
    {
        if ($this->keybindings === null) {
            $this->keybindings = new KeybindingRepository($this->baseDir . '/public/data/keybindings.json');
        }

        return $this->keybindings;
    }

    public function helpRepository(): HelpRepository
    {
        if ($this->help === null) {
            $this->help = new HelpRepository($this->baseDir . '/public/data/help.json');
        }

        return $this->help;
    }

    /** URL prefix the site is mounted under, e.g. "/" or "/fast-file-manager/help/". */
    public function basePath(): string
    {
        return BasePath::fromScriptName(
            (string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php'),
            (string) ($_SERVER['REQUEST_URI'] ?? ''),
        );
    }
}
