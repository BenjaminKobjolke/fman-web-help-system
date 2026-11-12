---
title: Syncing settings between computers
summary: Keep your keyboard shortcuts and command palette names in sync through your fman++ account.
order: 480
source: docs/SETTINGS_SYNC.md
---

Sign in to the same fman++ account on each computer to sync your custom keyboard shortcuts, command names and search keywords. Settings are kept separately for Windows, Mac and Linux.

the app syncs your own copies of `Key Bindings (<OS>).json`, `Viewer Key Bindings (<OS>).json`, `Command Titles (<OS>).json` and `Command Keywords (<OS>).json`. Built-in defaults are not uploaded. An absent user file counts as empty; the files stay on each computer, with a separate sync record in `Local/Settings Sync.json` under the app's data folder.

the app checks for changes after startup and sign-in, and when you run **Sync settings** from the command palette. Edits made in the app's shortcut or command palette editors are sent right away. If you edit a settings file by hand, the app notices at the next startup or when you run **Sync settings**.

## Choose which copy to keep

If both your computer and your account changed the same setting since the last sync, choose **Keep this computer's**, **Use the account's** or **Combine both**. Combining keeps this computer's value when both copies define the same shortcut or command, and adds entries found only in the account copy. The choice applies to one settings file at a time. Press Escape to leave both copies unchanged; the app asks again at the next sync.

On a computer's first sync, the app asks when the local file and account copy differ. If they match or one side is empty, it can sync without asking. When signed out or offline, local shortcuts and palette edits continue to work. If a sync cannot reach the API, run **Sync settings** again after reconnecting.
