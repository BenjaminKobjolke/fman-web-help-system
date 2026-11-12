---
title: File comments
summary: Add comments to files and folders and see them in the Comment column.
order: 440
source: docs/FILE_COMMENTS.md
---

fman++ stores comments in a `descript.ion` file in the same directory. The format is compatible with Total Commander, so both applications can read and update the same comments.

## Add or edit a comment

Select one or more files or folders and press **Ctrl+Shift+Z** on Windows or Linux, or **Cmd+Shift+Z** on macOS. If nothing is selected, fman++ edits the item under the cursor. The editor starts with a comment only when all selected items have the same one. Press Enter to save or Shift+Enter to add a line. Saving an empty comment removes it.

## Show comments in the file list

The Comment column is included by default. It appears in directories that contain comments and hides automatically in directories without them. It shows the first line of each comment. Use the column header menu or **Toggle comment column** to show or hide it; a manual choice applies across directories. After copying or moving comments into a directory, the column appears when you next visit or refresh it.

Comments are supported for local files and folders, not archive or FTP locations or network roots. Comments follow items when copied, moved or renamed. Successful trash and permanent deletion remove them; Undo restores comments for trashed items and moves them back with restored files. A read or write problem appears in the status bar and does not change the file operation.

Each directory has one `descript.ion` file. fman++ preserves entries it does not recognize and keeps an existing file's encoding when possible. New files use UTF-8 with a byte-order mark; when a comment cannot be represented in an existing encoding, fman++ switches the file to that format.
