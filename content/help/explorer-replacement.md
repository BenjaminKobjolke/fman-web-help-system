---
title: Explorer replacement
summary: Open common Windows folders in fman++ and switch back to Explorer when you need it.
order: 430
source: docs/EXPLORER_REPLACEMENT.md
---

On Windows, run **Toggle Explorer replacement** in the command palette to let an installed copy of fman++ open common folder locations. The setting `explorer_replacement` in `Local/Settings.json` is off by default.

When enabled, opening a folder or drive, pressing **Win+E**, or opening **This PC** brings up fman++. A folder or drive opens in the active pane if fman++ is already running; **This PC** opens the drives view. Control Panel, the Recycle Bin and other special folders continue to open in Explorer. The right-click **Open** action remains Explorer's action. Portable and development builds cannot enable this mode.

Press **F10** (**Open native file manager**) to open Explorer while replacement is enabled. If another program takes over folder opening, fman++ detects it at the next start, turns this setting off and reports the change. You can enable it again from the command palette.

Uninstalling fman++ removes the folder-opening entries it added. If you need to turn the redirect off manually, remove the application subkeys under `HKEY_CURRENT_USER\\Software\\Classes\\Directory\\shell` and `HKEY_CURRENT_USER\\Software\\Classes\\Drive\\shell`. If Explorer does not use the restored handlers immediately, sign out and back in.
