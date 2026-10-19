---
title: Administrator rights for protected folders
summary: Copy, move, rename, create and delete in Program Files and other protected folders via a UAC prompt.
order: 370
source: docs/functions/elevation.md
---

fman++ runs without administrator rights. When a file operation targets a folder
that needs them — `C:\Program Files\…`, `C:\Windows\…` — fman++ hands that one
operation to Windows, which shows the usual UAC prompt and carries it out. fman++
itself stays unelevated: programs you start from it, drag and drop and network
drives all keep working as before.

Windows only, local files only.

## Usage

Nothing to learn — use the normal commands. In a protected folder they raise
one UAC prompt per operation:

| Command | In a protected folder |
|---|---|
| New file (`Shift+F4`) | UAC prompt, file is created, editor opens |
| New folder (`F7`) | UAC prompt, folder is created |
| Copy (`F5`), paste, drag and drop | UAC prompt, Windows' own copy dialog |
| Move (`F6`), cut and paste | UAC prompt, Windows' own move dialog |
| Rename (`F2`) | UAC prompt |
| Delete, Delete permanently | UAC prompt (after fman++'s own confirmation) |

Declining the prompt cancels the operation. New file and New folder then say
*"You do not have enough permissions to create …"*; the others do nothing.

## Notes

- **Windows' dialogs, not fman++'s.** An elevated copy or move is done by Windows,
  exactly as in Explorer, so progress, "replace or skip" and folder-merge
  questions come from Windows for that operation. Everywhere else you still get
  fman++'s own dialogs.
- **One prompt for the whole selection.** Copying 50 files asks once.
- **Moving out counts too.** Moving a file *out of* a protected folder needs
  rights there, because the original is removed, so it is elevated as well.
  Copying out is not — reading needs no rights.
- **A destination folder that does not exist yet** inside a protected folder is
  created first, with a UAC prompt of its own — so two prompts. Likewise New
  folder with a nested name (`a\b`) asks once per missing level.
- **A single locked file in a normal folder** is not covered: the check is per
  folder. Such a file fails as before — Rename's **Retry** is the answer there.
- **Saving from an editor** is the editor's business. A file opened from a
  protected folder still cannot be saved unless the editor elevates itself.
- **Not in archives or on network locations.**
