<?php

declare(strict_types=1);

use App\BasePath;
use App\Config\TranslationKeys;
use App\Container;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);
$container = new Container($baseDir);
$twig = $container->twig();

$basePath = $container->basePath();
$route = BasePath::route((string) ($_SERVER['REQUEST_URI'] ?? '/'), $basePath);

/** Renders the 404 page with a real 404 status, so links can be checked mechanically. */
$notFound = static function () use ($twig): void {
    http_response_code(404);
    echo $twig->render('404.html.twig', ['page' => '404']);
};

try {
    if ($route === '') {
        echo $twig->render('home.html.twig', [
            'page' => 'home',
            'articles' => $container->helpRepository()->all(),
        ]);

        return;
    }

    if ($route === 'keybindings') {
        echo $twig->render('keybindings.html.twig', [
            'page' => 'keybindings',
            'groups' => $container->keybindingRepository()->groupedByPlatform(),
            'platform_label_keys' => [
                'all' => TranslationKeys::PLATFORM_ALL,
                'windows' => TranslationKeys::PLATFORM_WINDOWS,
                'mac' => TranslationKeys::PLATFORM_MAC,
                'linux' => TranslationKeys::PLATFORM_LINUX,
                'viewer' => TranslationKeys::PLATFORM_VIEWER,
            ],
        ]);

        return;
    }

    $article = $container->helpRepository()->bySlug($route);
    if ($article === null) {
        $notFound();

        return;
    }

    echo $twig->render('help.html.twig', ['page' => 'help', 'article' => $article]);
} catch (Throwable $e) {
    $container->logger()->error('Request failed', ['route' => $route, 'exception' => $e->getMessage()]);
    http_response_code(500);
    echo 'Help data unavailable. Run tools/generate.php first.';
}
