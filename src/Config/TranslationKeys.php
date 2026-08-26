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
    public const APP_NAME = 'app.name';
    public const APP_TAGLINE = 'app.tagline';

    public const NAV_HOME = 'nav.home';
    public const NAV_KEYBINDINGS = 'nav.keybindings';

    public const SEARCH_PLACEHOLDER = 'search.placeholder';
    public const SEARCH_NO_RESULTS = 'search.no_results';
    public const SEARCH_GROUP_HELP = 'search.group.help';
    public const SEARCH_GROUP_SHORTCUTS = 'search.group.shortcuts';

    public const HOME_TITLE = 'home.title';
    public const HOME_INTRO = 'home.intro';
    public const HOME_DOWNLOAD = 'home.download';
    public const HOME_DOWNLOAD_NOTE = 'home.download_note';
    public const HOME_SOURCE = 'home.source';
    public const HOME_TOPICS = 'home.topics';
    public const HOME_KB_CARD_SUMMARY = 'home.kb_card_summary';

    public const HELP_CONTENTS = 'help.contents';
    public const HELP_BACK = 'help.back';

    public const NOT_FOUND_TITLE = 'not_found.title';
    public const NOT_FOUND_BODY = 'not_found.body';

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
