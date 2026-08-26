<?php

declare(strict_types=1);

use App\HelpGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$baseDir = dirname(__DIR__);

$generator = new HelpGenerator($baseDir . '/content/help');
$articles = $generator->generate();
$outPath = $baseDir . '/public/data/help.json';
$generator->writeJson($articles, $outPath);

fwrite(STDOUT, sprintf("Wrote %d help articles -> %s\n", count($articles), $outPath));
