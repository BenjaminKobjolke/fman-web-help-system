# Deploy — fman web-help-system

Regenerates the keybindings data from fman's source, then uploads the site to FTP.

## Files

| File | Purpose |
|------|---------|
| `update_and_deploy.bat` | Full cycle: generate `keybindings.json` then upload. **Use this.** |
| `sync_help.bat` | Upload only (calls `deploy_mirror.bat`). |
| `deploy_mirror.bat` | ftp-sync runner (up mirror + hash cache). Not run directly. |
| `mirror.ini` | FTP credentials + target dir. **Not committed** (secrets). |
| `mirror.ini.example` | Template for `mirror.ini`. |
| `.ftp_sync_cache.db` | Local SHA-256 cache — tracks what changed. Auto-created. |

## One-time setup (fresh machine / repo re-clone)

1. **ftp-sync tool** must exist at `D:\GIT\BenjaminKobjolke\ftp-sync` (with `main.py`).
   Uses `uv run main.py`. Change `FTP_SYNC_DIR` in `deploy_mirror.bat` if elsewhere.

2. **FTP credentials** — copy the template and fill in:
   ```
   copy deploy\mirror.ini.example deploy\mirror.ini
   ```
   Set `FTP_HOST`, `FTP_USER`, `FTP_PASS`. Keep:
   - `DIRECTION = up`
   - `LOCAL_DIRECTORY = D:\wamp64\www\fman-web-help-system` (project root)
   - `FTP_DIRECTORY = /public/sites/fast-file-manager/help` (remote target)
   - `HASH_CACHE_FILE = ...\deploy\.ftp_sync_cache.db`

3. **App config** — the generator reads fman source paths from `config/app.php`:
   ```
   copy config\app.php.example config\app.php
   ```
   Point these at the local fman clone:
   ```php
   'fman_core_dir'        => 'D:/GIT/BenjaminKobjolke/fman/src/main/resources/base/Plugins/Core',
   'fman_keybindings_doc' => 'D:/GIT/BenjaminKobjolke/fman/docs/KEYBINDINGS.md',
   ```

4. **PHP deps** — `composer install` (needs vendor/ for the generator + runtime).

## How content updates from fman

`tools/generate_keybindings.php` (via `App\KeybindingGenerator`) reads from the fman
clone:
- `Plugins/Core/Key Bindings*.json` — keys + commands (all/Windows/Mac/Linux)
- `Plugins/Core/core/commands/*.py` — `aliases` for human-readable names
- `docs/KEYBINDINGS.md` — viewer-mode bindings

Writes `public/data/keybindings.json`. The deployed site serves only that JSON — it
does NOT read fman source at runtime.

## Deploy

```
deploy\update_and_deploy.bat
```

Regenerates the JSON, then mirrors the project to FTP. Excludes come from
`.deployignore` in the project root (dev-only files: tests, phpstan, README, etc.).
`config/app.php` IS uploaded (runtime needs it); `mirror.ini` and the cache are not.

Upload-only (no regenerate): `deploy\sync_help.bat`.

## Notes

- Deletions: ftp-sync tracks changes via the local hash cache. Stale remote files
  from before the cache existed are not pruned automatically — delete manually if needed.
- The site relies on the root `.htaccess` rewriting into `public/`, so it works
  self-contained under the remote `.../help/` directory.
