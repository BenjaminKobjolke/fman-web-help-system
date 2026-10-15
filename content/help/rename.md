---
title: Rename
summary: Rename inline in the file list, with a Retry that keeps the name you typed when the file is locked.
order: 290
source: docs/functions/rename.md
---

**Rename** renames the file or folder under the cursor by editing its name in
place in the file list — no dialog. A failed rename offers **Retry**, so the
name you typed is never thrown away just because another program had the file
open.

For moving a file somewhere else, or renaming *and* moving in one step, use
**Move** (`F6`). Rename deliberately refuses paths.

## Usage

1. Put the cursor on a file or folder.
2. Press `F2` or `Shift+F6` — or run **Rename** from the command palette, or pick
   **Rename** from the file's right-click menu.
3. The name becomes an editable field in place, with part of it preselected.
4. Type the new name and press `Enter`. `Esc` cancels.

After a successful rename the cursor follows the item to its new name.

## Commands

| Command name | Palette label | Default key | Shown when |
|---|---|---|---|
| `rename` | Rename | `F2`, `Shift+F6` | something is under the cursor |

It is also in the right-click menu of any item, file or folder. It is
deliberately absent from the menu you get on empty space in a pane, where there
is nothing to rename.

## What gets preselected

- **For a file**, everything up to the extension, so typing replaces the base
  name and keeps `.txt`. The double extensions `.tar.gz`, `.tar.xz` and
  `.pkg.tar.xz` count as one extension.
- **For a folder**, the whole name — folders have no extension to protect.

## When it refuses

- **A name containing a path separator**, or `..` or `.`, is refused with
  *"Relative paths are not supported. Please use Move (F6) instead."* On Windows
  both \ and / count.
- **A name that already exists** is refused with *"`<name>` already exists!"* —
  rename never silently overwrites. Move is the command that asks about
  overwriting.
- **An empty or unchanged name** does nothing at all, silently.

Case-only renames do work: `Foo` to `foo` is not treated as "already exists",
even on a case-insensitive filesystem.

## Retry on failure

If the rename fails, the alert offers **Retry** (the default, so `Enter`
retries) and **Cancel**:

```
Access was denied trying to rename pdf-to-jpg to _old_pdf-to-jpg.
                    [ Retry ]  [ Cancel ]
```

Retry re-attempts the same rename with the same target name. The usual cause is
another program holding the file or folder open — an editor, a terminal sitting
in that directory, a git GUI. Close it, press `Enter`, done. Cancel and `Esc`
both give up.

A permission failure produces the "Access was denied" wording above; any other
failure says *"Could not rename X to Y."* Both offer Retry.

In a folder that needs administrator rights (`C:\Program Files\…`) the rename
raises a UAC prompt instead — Retry could never succeed there. See
*Administrator rights for protected folders*.

## Notes

- **Usually instant.** The new name always lands in the same directory, so on a
  local filesystem the progress dialog never gets a chance to appear.
- **Inside an archive it is real work.** 7-Zip rewrites the archive, with a
  progress bar and a working Cancel.
