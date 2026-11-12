---
title: Remember last visited item
summary: Return to a folder with the item you last had under the cursor already selected.
order: 630
source: docs/functions/remember-last-visited.md
---

When you return to a location, fman++ selects the file or subfolder you last had under the cursor there. It remembers each level of a visited path, so after visiting a deep folder, its parent selects the next folder on that path. The selection is saved across restarts and works with local folders, archives, FTP/SFTP and plugin locations.

This feature is on by default. Set `remember_last_visited` in `Core Settings.json` to `false`, or run **Toggle remember last visited** in the command palette to switch it while fman++ is open. When off, existing history remains, but no new visits are recorded or selected. Run **Clear last visited history** to remove the saved history.

Incognito mode does not record new visits, but it can still select an item remembered earlier. History is stored in `Last Visited.json` next to `Visited Paths.json` and keeps up to 1,000 locations; oldest entries are dropped first when the limit is exceeded. For remote URLs that include a password, fman++ removes the password before saving or looking up the location.
