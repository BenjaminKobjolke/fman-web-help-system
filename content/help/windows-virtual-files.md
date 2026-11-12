---
title: Windows virtual files
summary: Copy attachments and other files offered by Windows apps even when they are not saved on disk yet.
order: 530
parent: copy-and-move
source: docs/VIRTUAL_FILES.md
---

Some Windows apps offer files without creating them on disk first. For example, Outlook can offer attachments or whole emails. Copy or paste an offered item into the active fman++ pane; emails are saved as `.msg` files.

fman++ uses its regular copy operation for the transfer, so its confirmations, overwrite prompts, progress, Undo and file-system behavior still apply. Temporary batches older than one day are cleaned up before a new batch is created; younger batches may still be in use by an active copy.

If fman++ cannot set up support for native drag-and-drop, drops keep their previous behavior. Pasting from the clipboard still works. Dragging files from fman++ into an Outlook message is a separate action and is not changed by virtual-file support.
