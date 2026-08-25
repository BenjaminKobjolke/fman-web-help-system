# fman web-help-system

A user-facing, server-rendered PHP help site for fman. First entry: a searchable,
mobile-friendly reference of **all keyboard shortcuts**, generated from fman's own
source files so it never drifts from the real bindings.

## How it works

- `tools/generate_keybindings.php` reads fman's `Key Bindings*.json`, the command
  `aliases` in `Plugins/Core/core/commands/*.py`, and the viewer tables in
  `docs/KEYBINDINGS.md`, then writes `public/data/keybindings.json`.
- `public/index.php` renders that data with Twig into a grouped, accessible table
  (works with JavaScript disabled).
- `public/assets/js/search.js` adds fuzzy search (fuse.js) and platform filtering by
  showing/hiding the already-rendered rows — no second data source.

## Requirements

- PHP 8.5+
- Composer
- Runtime libs: `twig/twig`, `monolog/monolog`, `xida/php-localization`
- Dev: `phpunit/phpunit`, `phpstan/phpstan`, `friendsofphp/php-cs-fixer`
- Vendored (no CDN): fuse.js 7.1.0 in `public/assets/vendor/fuse/`

## Setup

```bash
cd web-help-system
composer install
cp config/app.php.example config/app.php   # adjust if needed
php tools/generate_keybindings.php          # writes public/data/keybindings.json
```

## Run

Serve the project so the root `.htaccess` rewrites into `public/` (Apache/Laragon),
or use PHP's built-in server pointed at `public/`:

```bash
php -S localhost:8000 -t public
```

Then open <http://localhost:8000/>.

## Development

```bash
tools\run_tests.bat                     # PHPUnit
php vendor/bin/phpstan analyse          # static analysis (level 5)
php vendor/bin/php-cs-fixer fix         # PSR-12 formatting
```

## Regenerating after fman changes its bindings

Re-run `php tools/generate_keybindings.php` and commit the updated
`public/data/keybindings.json`. The deployed site only needs that JSON file — it does
not read fman's source at runtime.
