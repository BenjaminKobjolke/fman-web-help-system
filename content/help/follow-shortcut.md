---
title: Follow shortcut
summary: Jump to where a Windows .lnk shortcut points, instead of launching it.
order: 380
source: docs/functions/follow-shortcut.md
---

Windows only. With the cursor on a `.lnk` shortcut, **Follow shortcut** takes the
pane to the shortcut's destination instead of launching it:

- **Destination is a folder** — the pane opens that folder.
- **Destination is a file** — the pane opens the file's folder and puts the
  cursor on the file.

## Usage

Open the command palette (`Ctrl+Shift+P`) on a shortcut and pick **Follow
shortcut**. The entry is only listed while the cursor is on a local `.lnk` file
(any case — `.LNK` counts).

There is no default key binding. To add one, put this in your
`Key Bindings (Windows).json`:

```json
{ "keys": ["Ctrl+L"], "command": "follow_shortcut" }
```

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `follow_shortcut` | Follow shortcut | lnk, shortcut, link target, destination, go to destination, go to target | none |

## Enter versus Follow shortcut

| Shortcut points at | `Enter` | Follow shortcut |
|---|---|---|
| a folder | opens the folder | opens the folder |
| a file | Windows launches it | opens its folder, cursor on the file |
| nothing reachable | Windows reports the error | alert: destination does not exist |

## Notes

- Some shortcuts have no file system destination at all — "advertised" shortcuts
  written by installers, and shortcuts to Control Panel items or *This PC*.
  Follow shortcut shows the "does not exist" alert for them; `Enter` still
  launches them.
- The shortcut's arguments and working directory are ignored; only its target
  matters.
- Symbolic links and junctions are not shortcuts: fman++ already follows those
  when you open them.
