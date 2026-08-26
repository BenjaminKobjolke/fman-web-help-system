<?php

declare(strict_types=1);

namespace App;

/**
 * Works out the URL prefix the site is mounted under, so templates can build
 * absolute-from-root links that survive the nested article URLs.
 *
 * Deriving it beats configuring it: config/app.php is gitignored but still gets
 * FTP'd, so a hardcoded production value would break local development.
 */
final class BasePath
{
    /**
     * @param string $scriptName $_SERVER['SCRIPT_NAME'] — ".../public/index.php"
     *                           behind Apache, "/index.php" under `php -S -t public`.
     * @param string $requestUri $_SERVER['REQUEST_URI'] — used to trim host prefixes
     *                           that appear in SCRIPT_NAME but not in the public URL.
     *
     * @return string always leading and trailing slash, e.g. "/" or "/help/"
     */
    public static function fromScriptName(string $scriptName, string $requestUri = ''): string
    {
        // dirname() is filesystem-aware, so on Windows it can hand back backslashes.
        $dir = str_replace('\\', '/', dirname($scriptName));

        // Behind Apache the front controller lives in public/, which is not part
        // of the public URL — the root .htaccess rewrites into it.
        if (basename($dir) === 'public') {
            $dir = str_replace('\\', '/', dirname($dir));
        }

        $dir = '/' . trim($dir, '/');

        return self::trimToRequest($dir, $requestUri);
    }

    /**
     * Some hosts expose the site under a shorter URL than SCRIPT_NAME suggests:
     * "/public/sites/fast-file-manager/help/public/index.php" is served as
     * "/fast-file-manager/help/". Drop leading segments until what is left is a
     * real prefix of the requested path; the longest match wins.
     */
    private static function trimToRequest(string $dir, string $requestUri): string
    {
        if ($requestUri === '' || $dir === '/') {
            return rtrim($dir, '/') . '/';
        }

        $path = (string) parse_url($requestUri, PHP_URL_PATH);
        $segments = explode('/', trim($dir, '/'));

        for ($i = 0, $count = count($segments); $i < $count; $i++) {
            $candidate = '/' . implode('/', array_slice($segments, $i)) . '/';
            if ($path === rtrim($candidate, '/') || str_starts_with($path, $candidate)) {
                return $candidate;
            }
        }

        return '/';
    }

    /**
     * The requested path with the base prefix removed, no surrounding slashes.
     * "" for the start page, "keybindings", "window-title", …
     */
    public static function route(string $requestUri, string $basePath): string
    {
        $path = (string) parse_url($requestUri, PHP_URL_PATH);
        if (str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return trim(rawurldecode($path), '/');
    }
}
