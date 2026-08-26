<?php

declare(strict_types=1);

use App\HelpGenerator;
use App\KeybindingGenerator;
use App\KeybindingRepository;
use App\SearchIndexBuilder;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);
$config = require $baseDir . '/config/app.php';

// 1. Keyboard shortcuts, parsed out of the fman source checkout.
$keybindingGenerator = new KeybindingGenerator(
    (string) $config['fman_core_dir'],
    (string) $config['fman_keybindings_doc'],
);
$bindings = $keybindingGenerator->generate();
$keybindingsPath = $baseDir . '/public/data/keybindings.json';
$keybindingGenerator->writeJson($bindings, $keybindingsPath);
fwrite(STDOUT, sprintf("Wrote %d keybindings -> %s\n", count($bindings), $keybindingsPath));

// 2. Help articles, rendered from the markdown in content/help/.
$helpGenerator = new HelpGenerator($baseDir . '/content/help');
$articles = $helpGenerator->generate();
$helpPath = $baseDir . '/public/data/help.json';
$helpGenerator->writeJson($articles, $helpPath);
fwrite(STDOUT, sprintf("Wrote %d help articles -> %s\n", count($articles), $helpPath));

// 3. The flat index the site-wide search box fetches. Built from the two files
//    above, via the repository, so it can never describe a shape the site does not serve.
$searchBuilder = new SearchIndexBuilder();
$records = $searchBuilder->build($articles, (new KeybindingRepository($keybindingsPath))->all());
$searchPath = $baseDir . '/public/data/search.json';
$searchBuilder->writeJson($records, $searchPath);
fwrite(STDOUT, sprintf("Wrote %d search records -> %s\n", count($records), $searchPath));
