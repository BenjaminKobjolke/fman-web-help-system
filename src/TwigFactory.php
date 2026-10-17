<?php

declare(strict_types=1);

namespace App;

use App\Config\TranslationKeys;
use PhpLocalization\Localization;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * Builds the Twig environment and wires the localization t() function plus the
 * TranslationKeys (TK) global.
 */
final class TwigFactory
{
    public function __construct(private readonly string $templatesDir)
    {
    }

    public function create(
        Localization $localization,
        bool $debug,
        string $basePath = '/',
        array $app = [],
        bool $hasKeybindings = false,
    ): Environment
    {
        $twig = new Environment(new FilesystemLoader($this->templatesDir), [
            'debug' => $debug,
            'autoescape' => 'html',
        ]);

        $twig->addFunction(new TwigFunction(
            't',
            static fn (string $key, array $params = []): string => $localization->lang($key, $params)
        ));
        $twig->addGlobal('TK', new TranslationKeys());
        $twig->addGlobal('app', $app);
        $twig->addGlobal('has_keybindings', $hasKeybindings);
        // Every asset href and internal link is built from this — relative hrefs
        // break as soon as an article URL sits one level deeper than the root.
        $twig->addGlobal('base', $basePath);

        return $twig;
    }
}
