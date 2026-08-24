<?php

declare(strict_types=1);

use App\KeybindingGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);
$config = require $baseDir . '/config/app.php';

// One config value owns the main-repo location; derive the two source paths from it.
$fmanRepoDir = (string) $config['fman_repo_dir'];
$generator = new KeybindingGenerator(
    $fmanRepoDir . '/src/main/resources/base/Plugins/Core',
    $fmanRepoDir . '/docs/KEYBINDINGS.md',
);

$bindings = $generator->generate();
$outPath = $baseDir . '/public/data/keybindings.json';
$generator->writeJson($bindings, $outPath);

fwrite(STDOUT, sprintf("Wrote %d keybindings → %s\n", count($bindings), $outPath));
