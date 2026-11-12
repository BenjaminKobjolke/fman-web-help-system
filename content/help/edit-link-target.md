---
title: Edit link target
summary: Change where a symbolic link, directory junction or Windows shortcut points.
order: 590
parent: follow-shortcut
source: docs/functions/edit-link-target.md
---

Put the cursor on a supported link and choose **Edit link target** from the command palette (**Ctrl+Shift+P**) or the Windows file context menu. The current destination is selected in the input box. Enter a new path and press Enter. Cancel, leave it blank or keep the current destination to make no change.

| Command name | Palette label | Keywords | Default key binding |
|---|---|---|---|
| `edit_link_target` | Edit link target | symlink, junction, shortcut, lnk, link target, retarget, change link, mklink | none |

Symbolic links can point to files or folders, and relative targets stay relative to the link's folder. Directory junctions and `.lnk` shortcuts use an absolute target path. Shortcut arguments, icon and working directory are preserved. This command is for local links; Windows shortcuts are Windows-only, and symbolic links depend on operating-system permissions. Creating symbolic links on Windows may require Developer Mode or an administrator account.

If the new target does not exist, fman++ asks whether to point the link there anyway. Choose **Yes** to continue or **No** to keep the current destination. If Windows refuses the change, fman++ reports the error and keeps the existing link.
