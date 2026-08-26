---
title: Show and hide columns
summary: Hide the Size and Modified columns when you only care about file names.
order: 110
source: docs/functions/TOGGLE_COLUMNS.md
---

If you mostly care about file names, you can hide the **Size** and **Modified**
columns in the file panes.

## Usage

1. Open the command palette and search "column".
2. Run **Toggle size column** to hide the Size column; run it again to bring it
   back. Same for **Toggle modified column**.
3. Both panes always change together, and the choice survives restarting fman.

## Commands

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `toggle_size_column` | Toggle size column | show / hide size column | none (palette only) |
| `toggle_modified_column` | Toggle modified column | show / hide modified column, date | none (palette only) |

Bind them like any other command:

```json
{ "keys": ["Ctrl+Alt+S"], "command": "toggle_size_column" }
```

## Notes

- Only Size and Modified can be toggled — the Name column is always shown.
- The change takes effect in both panes immediately, without navigating or
  resizing the window, and the remaining columns widen to fill the pane right
  away.
- On locations that have no Size or Modified column at all — the Windows drives
  list, `This PC` — there is nothing to hide, so the setting has no visible
  effect there.
- The state lives in `Core Settings.json` under `hide_size_column` and
  `hide_modified_column`.
