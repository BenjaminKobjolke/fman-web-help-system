<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Translation key constants — use these instead of raw strings for IDE
 * autocomplete and refactor safety.
 */
final class TranslationKeys
{
    public const APP_TITLE = 'app.title';
    public const KB_HEADING = 'keybindings.heading';
    public const KB_INTRO = 'keybindings.intro';
    public const KB_SEARCH_PLACEHOLDER = 'keybindings.search_placeholder';
    public const KB_NO_RESULTS = 'keybindings.no_results';
    public const KB_COL_KEYS = 'keybindings.col.keys';
    public const KB_COL_NAME = 'keybindings.col.name';
    public const KB_COL_COMMAND = 'keybindings.col.command';
    public const KB_FILTER_ALL = 'keybindings.filter.all';

    public const PLATFORM_ALL = 'platform.all';
    public const PLATFORM_WINDOWS = 'platform.windows';
    public const PLATFORM_MAC = 'platform.mac';
    public const PLATFORM_LINUX = 'platform.linux';
    public const PLATFORM_VIEWER = 'platform.viewer';
}
