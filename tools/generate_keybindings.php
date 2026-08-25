<?php

declare(strict_types=1);

use App\KeybindingGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);
$config = require $baseDir . '/config/app.php';

$generator = new KeybindingGenerator(
    (string) $config['fman_core_dir'],
    (string) $config['fman_keybindings_doc'],
);

$bindings = $generator->generate();
$outPath = $baseDir . '/public/data/keybindings.json';
$generator->writeJson($bindings, $outPath);

fwrite(STDOUT, sprintf("Wrote %d keybindings → %s\n", count($bindings), $outPath));
