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
            '/fast-file-manager/help/',
            BasePath::fromScriptName('/fast-file-manager/help/public/index.php')
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
            '/fast-file-manager/help/',
            BasePath::fromScriptName(
                '/public/sites/fast-file-manager/help/public/index.php',
                '/fast-file-manager/help/window-title'
            )
        );
    }

    public function testKeepsTheFullPrefixWhenTheRequestUsesIt(): void
    {
        self::assertSame(
            '/public/sites/fast-file-manager/help/',
            BasePath::fromScriptName(
                '/public/sites/fast-file-manager/help/public/index.php',
                '/public/sites/fast-file-manager/help/'
            )
        );
    }

    public function testRouteStripsTheBaseAndSurroundingSlashes(): void
    {
        self::assertSame(
            'window-title',
            BasePath::route('/fast-file-manager/help/window-title', '/fast-file-manager/help/')
        );
    }

    public function testRouteIsEmptyForTheStartPage(): void
    {
        self::assertSame('', BasePath::route('/fast-file-manager/help/', '/fast-file-manager/help/'));
    }

    public function testRouteIgnoresTheQueryString(): void
    {
        self::assertSame('keybindings', BasePath::route('/keybindings?q=copy', '/'));
    }
}
