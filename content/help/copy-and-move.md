---
title: Copy and move
summary: Copy, move and manage file transfers, including conflicts, queues and undo.
order: 400
source: docs/COPY_AND_MOVE.md
---

Use **Copy** (F5) and **Move** (F6) to transfer files between folders. You can also drag files or paste them from the clipboard. On Windows, dragging within the same drive moves files; dragging to another drive or network share copies them. Hold Ctrl to copy or Shift to move. Dropping files out of an archive always copies them.

You can minimize a Copy or Move progress dialog with **–** and restore it from its status bar icon.

## Transfer queue

Transfers use one global queue by default, so each copy or move starts after the one ahead of it finishes. This includes transfers started by dragging or pasting. Run **Toggle transfer queue** to turn queueing off or on; the setting is `queue_transfers` in `Core Settings.json`. A change affects new transfers, while transfers already waiting stay in the queue.

When transfers are waiting, select the status bar icon or run **Show transfer queue**. The running transfer appears first. You can reorder or remove waiting transfers, but cancel the running one from its progress dialog. A transfer that has to wait briefly shows **Transfer queued: …**. Delete, Pack, Rename, Symlink and Undo do not use this queue.

## Existing destination files

When a destination file already exists, the prompt can show the incoming and existing file sizes and modified dates. On Windows, local `.exe` and `.dll` files can also show their program versions. **Overwrite** is the default. Choose **Rename** to keep both files and enter a new name, or **Rename all** to use available numbered names for later conflicts. Folder conflicts merge their contents without a prompt.

On Windows, replacing a read-only file asks for confirmation by default. Choose **Yes**, **Yes to all**, **No** or **Abort**; **No** is the default. Run **Toggle read-only replace question** to turn the prompt off or on. The setting is `ask_before_replacing_read_only` in `Core Settings.json`. Replacement uses the incoming file's attributes.

Overwrite details are shown by default. Run **Toggle overwrite details** or set `show_overwrite_details` to `false` in `Core Settings.json` to hide them.

## Canceling a move

For local file-system moves, the app restores completed files to their original locations when you cancel, choose **Abort**, or an unexpected error stops the move. If the original location is no longer free, the file stays at the destination and the app reports the paths it could not restore. A destination file you approved replacing cannot be recovered by this rollback. Plugin file systems and moves run in an elevated process do not use this rollback.

## Other options

- **Toggle locked file check** checks on Windows whether another program has a file open before a cross-drive move or an overwrite. It is on by default; its setting is `check_locked_files` in `Core Settings.json`. Other platforms skip the check.
- **Toggle deselect after copy** is on by default. It deselects a copied item after all its transfer tasks succeed. Skipped, failed or unfinished items stay selected. Drag-and-drop, paste and elevated copies leave the selection alone. The setting is `deselect_after_transfer` in `Core Settings.json`.
- **Toggle folder tree** (Alt+F10) shows a folder-only tree in one pane. Use Right and Left to expand or collapse, Enter to open a folder in the other pane, and Esc to return to the file list. **Toggle tree follows cursor** makes the other pane follow the selected folder after a short pause; it is off by default and uses `tree_follow_cursor` in `Core Settings.json`.
- For Windows moves across drives and other copy details, see [Robocopy](robocopy.md).
