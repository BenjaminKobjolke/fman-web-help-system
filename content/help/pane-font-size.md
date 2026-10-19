---
title: Pane font size
summary: Zoom both file panes in and out with Alt+Up / Alt+Down, and reset to the theme's size.
order: 100
source: docs/functions/pane-font-size.md
---

You can zoom the font of both file panes on the fly, and reset it back to the
theme's original size.

## Usage

1. **Alt+Up** makes the text in both panes larger, **Alt+Down** makes it
   smaller. Both panes always change together.
2. Search "font size" in the command palette for the same two actions, plus
   **Reset font size**, which is palette-only.
3. The chosen size is remembered and survives restarting fman++.

This zooms only the two file-list panes. The text viewer and image viewer have
their own zoom, which reuses these same shortcuts while a viewer is open.

## Commands

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `increase_pane_font_size` | Increase font size | zoom in, larger pane font, bigger | `Alt+Up` |
| `decrease_pane_font_size` | Decrease font size | zoom out, smaller pane font, smaller | `Alt+Down` |
| `reset_pane_font_size` | Reset font size | default font size, reset zoom | none (palette only) |

Rebind them like any other command:

```json
{ "keys": ["Ctrl+0"], "command": "reset_pane_font_size" }
```

## Notes

- Only the two file-list panes are affected. The status bar, location bar,
  column headers and command palette keep their own font size.
- **It respects a custom theme.** If your own `Theme.css` sets a different base
  font size, the first zoom press steps from *that* size. Resetting removes the
  override completely, so the pane goes back to rendering your theme exactly as
  authored.
- The size is clamped between 6pt and 40pt; zooming past either end does
  nothing further.
- **Go to parent folder is on Ctrl+Up on Windows and Linux.** Alt+Up used to do
  that, and moved to make room for zooming. On Mac it was already Cmd+Up. On
  every platform it is also still on **Backspace**.
