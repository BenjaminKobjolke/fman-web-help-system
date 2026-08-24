<?php

declare(strict_types=1);

use App\Config\TranslationKeys;
use App\Container;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);
$container = new Container($baseDir);

// Feature 1: the keybindings help page (single page, no router yet).
try {
    $groups = $container->keybindingRepository()->groupedByPlatform();
} catch (Throwable $e) {
    $container->logger()->error('Failed to load keybindings', ['exception' => $e->getMessage()]);
    http_response_code(500);
    echo 'Keybindings data unavailable. Run tools/generate_keybindings.php first.';

    return;
}

$platformLabelKeys = [
    'all' => TranslationKeys::PLATFORM_ALL,
    'windows' => TranslationKeys::PLATFORM_WINDOWS,
    'mac' => TranslationKeys::PLATFORM_MAC,
    'linux' => TranslationKeys::PLATFORM_LINUX,
    'viewer' => TranslationKeys::PLATFORM_VIEWER,
];

echo $container->twig()->render('keybindings.html.twig', [
    'groups' => $groups,
    'platform_label_keys' => $platformLabelKeys,
]);
