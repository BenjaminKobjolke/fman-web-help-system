---
title: System tray
summary: Keep fman++ available in the Windows notification area while its window is hidden.
order: 520
source: docs/SYSTEM_TRAY.md
---

On Windows, fman++ can stay running in the notification area (system tray) while its window is hidden. Find the tray commands in the command palette with **Ctrl+Shift+P**. Closing the window still quits fman++; minimizing and hiding it are separate actions.

| Command | What it does |
|---|---|
| **Minimize to system tray** | Hides fman++ and shows its tray icon. Select the icon to restore the window. |
| **Toggle minimize to tray** | Makes the window's minimize button hide fman++ in the tray. The tray icon stays visible while this is on. |
| **Minimize to taskbar** | Minimizes the window as usual, leaving it on the taskbar. |
| **Toggle start with Windows** | In an installed build, starts fman++ hidden in the tray when you sign in. |
| **Toggle Explorer replacement** | In an installed build, opens folders, drives, Win+E and This PC in fman++. See [Explorer replacement](explorer-replacement.md). |

The tray icon menu has **Show** and **Quit**. Quit uses the app's normal exit path and saves the session. When minimize-to-tray is off, the icon appears only while the window is hidden; when it is on, the icon stays visible while fman++ runs.

## Start with Windows and hotkeys

In an installed build, **Toggle start with Windows** starts fman++ hidden in the tray when you sign in. The `start_with_windows` setting defaults to `false`.

You can set a Windows-wide key combination with `global_hotkey` in `Local/Settings.json` in the app's data directory. For example, add `"global_hotkey": "Ctrl+Alt+F"`. The key is not reserved until you set one. Supported modifiers are `Ctrl`, `Alt`, `Shift` and `Win`; the final key can be a letter, digit, function key F1–F24, Backspace, Tab, Enter, Escape, Space, PageUp, PageDown, End, Home, an arrow key, Insert or Delete. If another program already uses the combination, fman++ reports that and continues without it. Restart after editing the setting.

The `--tray` argument starts fman++ hidden in the tray. If another fman++ instance is already running, the launch is forwarded to it without opening a second window. Tray, hotkey and sign-in startup features are Windows-only; start-with-Windows is unavailable in portable and development builds.
