<?php

declare(strict_types=1);

namespace App\Tests;

use App\BasePath;
use PHPUnit\Framework\TestCase;

final class BasePathTest extends TestCase
{
    public function testStripsPublicUnderASubdirectoryMount(): void
    {
        self::assertSame(
            '/fman-plus-plus/help/',
            BasePath::fromScriptName('/fman-plus-plus/help/public/index.php')
        );
    }

    public function testStripsPublicAtTheDocumentRoot(): void
    {
        self::assertSame('/', BasePath::fromScriptName('/public/index.php'));
    }

    public function testBuiltInServerServesFromPublicSoThereIsNoSegmentToStrip(): void
    {
        self::assertSame('/', BasePath::fromScriptName('/index.php'));
    }

    public function testDropsHostPrefixNotPresentInTheRequestUrl(): void
    {
        self::assertSame(
            '/fman-plus-plus/help/',
            BasePath::fromScriptName(
                '/public/sites/fman-plus-plus/help/public/index.php',
                '/fman-plus-plus/help/window-title'
            )
        );
    }

    public function testKeepsTheFullPrefixWhenTheRequestUsesIt(): void
    {
        self::assertSame(
            '/public/sites/fman-plus-plus/help/',
            BasePath::fromScriptName(
                '/public/sites/fman-plus-plus/help/public/index.php',
                '/public/sites/fman-plus-plus/help/'
            )
        );
    }

    public function testRouteStripsTheBaseAndSurroundingSlashes(): void
    {
        self::assertSame(
            'window-title',
            BasePath::route('/fman-plus-plus/help/window-title', '/fman-plus-plus/help/')
        );
    }

    public function testRouteIsEmptyForTheStartPage(): void
    {
        self::assertSame('', BasePath::route('/fman-plus-plus/help/', '/fman-plus-plus/help/'));
    }

    public function testRouteIgnoresTheQueryString(): void
    {
        self::assertSame('keybindings', BasePath::route('/keybindings?q=copy', '/'));
    }
}
