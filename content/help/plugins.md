---
title: Plugins
summary: Install, list, remove and reload plugins — and what fman tells you when two of them collide.
order: 240
source: docs/PLUGINS.md
---

fman is plugins all the way down. The file list, archive support and most
commands in the palette live in the shipped **Core** plugin. The shipped
**Robocopy** plugin adds Windows-only copy and move commands. Both load through
the same mechanism your own plugins use.

## Where they live

| Directory | What goes there |
|---|---|
| The shipped `Plugins` folder | The **Core** and **Robocopy** plugins. Load first, and are never reloaded at runtime. |
| `%APPDATA%\fman\Plugins\Third-party` | Plugins installed with **Install plugin**. |
| `%APPDATA%\fman\Plugins\User` | Your own. `User\Settings` always loads **last**, so its JSON wins. |

On macOS and Linux, `%APPDATA%\fman` is `~/Library/Application Support/fman` and
`~/.config/fman`.

Load order is shipped, then third-party, then user. A plugin is a directory
containing a Python package — a subdirectory with an `__init__.py`.

## The commands

All four are in the command palette (`Ctrl+Shift+P`), and none has a default key
binding:

| Command | Does |
|---|---|
| **Install plugin** | Lists GitHub repos tagged `fman` and `plugin`, downloads the latest release into `Plugins\Third-party\`, and loads it immediately. |
| **List plugins** | Fuzzy-search every installed plugin; picking one opens its directory in the pane. Third-party entries show the installed release tag or commit. |
| **Remove plugin** | Unloads a third-party plugin and deletes its directory. Only third-party ones are offered — plugins under `User\` are yours to delete by hand. |
| **Reload plugins** | Unloads and reloads every third-party and user plugin, in place. |

## Reload plugins

**Reload plugins** is the edit-test loop: change a plugin's `.py` file, run it,
see the change — no restart.

- It unloads in reverse load order, then loads again in load order. Every
  registration a plugin made — commands, listeners, file systems, columns,
  viewers, fonts, CSS, key bindings, context menus — is undone, so nothing
  leaks.
- **Pane locations are preserved** across the reload. A pane sitting inside a
  file system that gets reloaded would otherwise lose its path.
- **Core is not reloaded.** Only third-party and user plugins. To pick up a
  change in Core, restart fman.
- **fman itself is not reloaded** either. When you are working on fman's own
  source, **Reload plugins** runs the version that was loaded at startup.

## Two plugins, one package name

Python package names are global, so two plugins whose package directories have
the same name are the same module as far as Python is concerned — and only one
of them can win, the one that loads last.

This is easy to hit without noticing, because a plugin's *directory* name and
its *package* name are two different things:

```
%APPDATA%\fman\Plugins\Third-party\SyncSelectedFilesToOtherPaneForWindows\
    sync_selected_files_to_other_pane_for_windows\__init__.py

%APPDATA%\fman\Plugins\User\FManSyncSelectedFilesToOtherPaneForWindows\
    sync_selected_files_to_other_pane_for_windows\__init__.py
```

Two differently-named plugin folders, one package name — typically the same
plugin installed twice: once from the plugin list, once as a git clone you edit.

fman says so on load, rather than letting you wonder why your edits do nothing:

```
Two plugins contain a package named 'sync_selected_files_to_other_pane_for_windows':

C:\...\Plugins\Third-party\SyncSelectedFilesToOtherPaneForWindows\sync_selected_files_to_other_pane_for_windows\__init__.py
C:\...\Plugins\User\FManSyncSelectedFilesToOtherPaneForWindows\sync_selected_files_to_other_pane_for_windows\__init__.py

Only the second one is used. Please remove one of them.
```

It is a warning, not a refusal: loading continues and the second copy wins. Fix
it by deleting one of the two plugin directories, then restarting fman or
running **Reload plugins**.

The same alert appears if a plugin's package name collides with a module fman
has already imported — a package called `json` or `core`, say. The two paths in
the message tell the two cases apart.
