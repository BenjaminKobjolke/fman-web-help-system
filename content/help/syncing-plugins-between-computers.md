---
title: Syncing plugins between computers
summary: Keep a shared list of third-party plugins and choose which versions to install on each computer.
order: 490
parent: plugins
source: docs/PLUGIN_SYNC.md
---

Sign in to a fman++ account to keep one list of third-party plugins across your computers. Plugin sync is separate from syncing keyboard shortcuts and command palette names; see [Syncing settings between computers](syncing-settings-between-computers.md).

Run **Sign in** from the command palette and finish signing in through your browser. Run **Sign out** to remove the account from this computer; signing out does not delete the shared plugin list.

After a successful **Install plugin**, the app adds or replaces that plugin in the shared list. **Remove plugin** removes it. Plugins installed by hand without source information are not uploaded.

## Sync the list

the app runs **Sync plugins** once after startup when the first pane is ready. You can also run it from the command palette. If no account is signed in, startup sync does nothing; a manual sync reports that you are signed out.

- Plugins in your account list but not installed here appear in a checklist. Entries saved on this operating system start checked; entries saved on another operating system start unchecked and show that system. Selected plugins install at the saved version, not the latest one.
- Plugins previously shared from this computer but removed from the account list are offered for removal. the app deletes a local copy only after you confirm. If you decline, it stays pending for a later sync.
- Installed plugins with recognized source information that are missing from the account list are uploaded. Plugins installed by hand without source information are skipped.
- A plugin removed on this computer while offline is removed from the account list at the next successful sync.

When you skip or leave a plugin unchecked, the app remembers that choice for that version. It is offered again if its saved version changes. A network or account service error appears in the status bar; the next sync compares the current local and remote state again.

The per-computer sync record is stored in `Local/Plugin Sync.json` under the app's data directory. It is not part of the shared plugin list.
