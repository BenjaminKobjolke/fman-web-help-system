# fman web-help-system

A user-facing, server-rendered PHP help site for fman: a start page pointing at the
download, prose help articles, and a searchable, mobile-friendly reference of **all
keyboard shortcuts** generated from fman's own source files so it never drifts from
the real bindings. One search box covers the articles and the shortcuts together.

## Pages

| URL | What it is |
|---|---|
| `/` | Start page — download links, site-wide search, one card per topic |
| `/keybindings` | The shortcut table, with its own platform filter |
| `/<slug>` | A help article, e.g. `/command-palette` |

`public/index.php` is the front controller; `public/.htaccess` routes anything that
is not a real file into it. The URL prefix is derived from `SCRIPT_NAME` by
`src/BasePath.php`, so the same code works at a document root and in a subdirectory
without a configured base path.

## How it works

Everything the site serves is generated once and committed — nothing reads fman's
source, or parses markdown, at request time.

```
fman source (Key Bindings*.json, commands/*.py, docs/KEYBINDINGS.md)
content/help/*.md  (end-user articles, front matter + markdown)
        │
        └── php tools/generate.php
                ├── public/data/keybindings.json   126 bindings
                ├── public/data/help.json          rendered article HTML
                └── public/data/search.json        flat index for the search box
```

- `src/KeybindingGenerator.php` parses the fman source; `src/HelpGenerator.php`
  renders `content/help/*.md` with commonmark (GFM tables on, raw HTML escaped) and
  gives every `h2`/`h3` a stable id for the on-page contents list.
- `src/SearchIndexBuilder.php` flattens both into `search.json`. Article HTML is
  deliberately excluded — the index is downloaded by the browser.
- `public/assets/js/site-search.js` fetches that index **on first focus** and
  fuzzy-searches it (fuse.js). A shortcut hit links to `/keybindings#kb-…`; the
  keybindings page reads that hash, selects the matching platform filter so the row
  is not hidden, and scrolls to it.
- `public/assets/js/search.js` still owns the keybindings table's own filter, over
  the already-rendered rows — no second data source, works with the table as served.

## Adding content

Articles live in `content/help/<slug>.md` — the filename is the URL slug, the front
matter carries `title` / `summary` / `order` / `source`. Shortcuts are not written
here at all; they are parsed out of the fman source on every generator run. Either
way the workflow is: edit the source file, run `tools\generate.bat`, commit both.

**[docs/HOW_TO_ADD_CONTENT.md](docs/HOW_TO_ADD_CONTENT.md)** is the full guide — front
matter reference, what to keep and drop when rewriting an fman doc for end users,
reserved slugs and the other ways the build can reject a file.

The `fman-help-page` skill in `.claude/skills/` automates the rewrite: point it at a
doc under the fman repo's `docs/` and it creates or updates the matching article.

## Requirements

- PHP 8.5+
- Composer
- Runtime libs: `twig/twig`, `monolog/monolog`, `xida/php-localization`
- Dev: `phpunit/phpunit`, `phpstan/phpstan`, `friendsofphp/php-cs-fixer`,
  `league/commonmark` (generator only — the served site reads the committed JSON)
- Vendored (no CDN): fuse.js 7.1.0 in `public/assets/vendor/fuse/`

## Setup

```bash
composer install
cp config/app.php.example config/app.php   # point fman_core_dir at your fman checkout
tools\generate.bat                          # writes the three files in public/data/
```

## Run

Serve the project so the root `.htaccess` rewrites into `public/` — Apache, wamp or
Laragon. Pretty URLs need mod_rewrite, so PHP's built-in server is not enough for
anything but `/`.

## Development

```bash
tools\generate.bat                      # keybindings + help + search index
tools\run_tests.bat                     # PHPUnit
php vendor/bin/phpstan analyse          # static analysis (level 5)
php vendor/bin/php-cs-fixer fix         # PSR-12 formatting
```

`tests/GeneratorTest.php` reads `config/app.php` to find the fman checkout and skips
if it is absent.

## Regenerating after fman changes

Re-run `tools\generate.bat` and commit the updated files under `public/data/`. The
deployed site needs only those — it does not read fman's source at runtime.
`deploy\update_and_deploy.bat` does both: regenerate, then FTP-mirror.

## Reuse for another app

Copy the site and edit `config/app.php`: set `app_name`, `download_url` and
`source_url` (either URL may be empty to hide its link). Update the `{app}`
placeholders and other app-specific copy in `lang/en.json` and `lang/de.json`, then
replace the articles in `content/help/`. The fman keybindings generator is optional:
leave `fman_core_dir` unset or empty, and add `public/data/keybindings.json` only if
the app has shortcuts. Its JSON is an array of rows with `keys`, `command`, `name`,
`platform` and optional `args`, matching `src/Keybinding.php`.

Remove the fman-only skills under `.claude/skills/fman-help-*` and
`tools/fman_docs_sync.txt` from the copied project.
