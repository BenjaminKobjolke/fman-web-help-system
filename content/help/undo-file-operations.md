---
title: Undo file operations
summary: Reverse the latest supported file operation with Ctrl+Z or Cmd+Z.
order: 650
source: docs/functions/undo.md
---

Use **Undo** to reverse the most recent supported file operation. History lasts only while fman++ is open and is cleared when it exits. Each press undoes one operation, starting with the latest.

Press **Ctrl+Z** on Windows or Linux, or **Cmd+Z** on macOS. You can also find **Undo** in the command palette (**Ctrl+Shift+P**).

Undo supports moving files to the trash, moving files between folders, renaming files and copying files. Undoing a copy asks before moving its copies to the trash. If another file occupies an original location, Undo skips that item and reports failures.

Restoring items from the trash is supported on Windows. On macOS and Linux, Undo reports that trash restoration is unsupported; other supported operations can still be undone. If there is no operation to undo, fman++ says “Nothing to undo.” Undo is not recorded as a new operation. Ctrl+Z or Cmd+Z in the in-pane text editor still undoes text edits.
