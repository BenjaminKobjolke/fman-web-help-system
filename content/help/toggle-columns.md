---
title: Show and hide columns
summary: Hide the Size and Modified columns when you only care about file names.
order: 110
source: docs/functions/TOGGLE_COLUMNS.md
---

Use the command palette or right-click a column header to show or hide file-list
columns. Both panes change together, and the first column (normally Name)
always stays visible.

## Usage

1. Open the command palette and search "column".
2. Run a **Toggle … column** command to show or hide that column, or right-click
   a column header to choose any available column, including plugin columns.
3. Visibility choices apply to both panes and survive restarting fman++.

## Commands

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `toggle_size_column` | Toggle size column | show / hide size column | none (palette only) |
| `toggle_modified_column` | Toggle modified column | show / hide modified column, date | none (palette only) |
| `toggle_extension_column` | Toggle extension column | show / hide extension | none (palette only) |
| `toggle_version_column` | Toggle version column | show / hide version | none (palette only) |
| `toggle_attributes_column` | Toggle attributes column | show / hide attributes, permissions | none (palette only) |
| `toggle_fixed_column_widths` | Toggle fixed column widths | fixed / auto-fit column widths | none (palette only) |
| `toggle_name_without_extension` | Toggle name without extension | hide extension in name | none (palette only) |

Bind them like any other command:

```json
{ "keys": ["Ctrl+Alt+S"], "command": "toggle_size_column" }
```

## Notes

- Size, Modified, Extension, Version and Attributes can be toggled with palette
  commands; other columns, including plugin columns, can be toggled from the
  header menu. The first column cannot be hidden.
- Extension, Version and Attributes start hidden. Extension shows the last
  suffix (`archive.tar.gz` → `gz`); Version shows Windows executable and
  library versions; Attributes shows Windows flags or macOS/Linux permissions.
- **Toggle name without extension** hides the extension from Name while leaving
  it in the Extension column. Renaming still preserves it.
- **Toggle fixed column widths** switches from auto-fit widths to representative
  widths for Size and Modified; Name takes the remaining space. Dragged widths
  are remembered per pane across navigation and restarts.
- The change takes effect in both panes immediately, without navigating or
  resizing the window, and the remaining columns widen to fill the pane right
  away.
- Hidden plugin columns use their qualified names in the `hidden_columns` list
  in `Core Settings.json`; the built-in columns have their own settings there.
- On locations without a particular column, its visibility setting has no
  visible effect.
