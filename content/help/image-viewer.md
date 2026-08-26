---
title: Image viewer
summary: Preview images in the pane, with zoom, panning and animated GIFs.
order: 60
parent: file-viewers
source: docs/views/IMAGE_VIEWER.md
---

The image viewer shows an image inside the active pane, in place of the file
list — the text viewer's counterpart for image files, so you can look at a
`.png` or `.jpg` without launching an image app. Always read-only.

## Usage

1. Put the cursor on an image and run **View file** from the command palette —
   the same command as for text; fman picks the viewer by file type. (**View
   file in other pane** opens it in the opposite pane instead.)
2. The image fills the pane, scaled to fit by default.
3. **Escape**, **Enter** or **Backspace** closes the viewer, cursor back on the
   same file.
4. **Tab** switches to the other pane; the viewer stays open, and **Tab** again
   brings focus back to it.
5. **Ctrl+Shift+P** opens the viewer's own command palette.

## Zoom

The viewer has its own scale zoom, independent of the text viewer's font zoom
and the file panes' zoom.

- Whatever keys **Increase font size** / **Decrease font size** are bound to
  (`Alt+Up` / `Alt+Down` by default) also zoom the image — the same shortcut the
  text viewer reuses, so there is nothing extra to learn.
- The viewer's palette (`Ctrl+Shift+P`) has **Zoom in** and **Zoom out**, plus
  **Fit to window**, **Actual size (100%)** and **Reset zoom**. Fit to window
  and Reset zoom both return to fit mode.
- The chosen scale is remembered across restarts until you reset it or return to
  fit mode.
- Zooming in from fit mode steps from the scale currently on screen, so the
  image does not jump on the first press.

## Pan

Once you zoom past the pane's size, scrollbars appear and the **arrow keys** pan
the image. In fit mode there is nothing to pan — the whole image is visible.

## Behaviour

- **Read-only.** There is no edit mode; an image cannot be modified in place.
- **Animated GIFs play** automatically while open, and scaling applies to the
  animation, not just one frame.
- **One viewer at a time per pane.** Opening an image while a text viewer (or
  another image) is open in that pane replaces it. The other pane is unaffected.
- **Follows the active theme** — the letterbox background matches the file
  list's color.
- **Recognized extensions:** `.png`, `.jpg`, `.jpeg`, `.gif`, `.bmp`, `.webp`,
  `.ico`, `.svg`. Anything else opens in the text viewer instead.

## Bindable commands

Beyond zoom, the viewer's actions are viewer-only commands you bind in your own
`Viewer Key Bindings (<OS>).json` — a separate file from the normal key
bindings. A rebind always beats the default key.

| Command | Default key | Action |
|---|---|---|
| `image_reset_zoom` | none (palette only) | Fit to window |
| `image_actual_size` | none (palette only) | Actual size (100%) |
| `viewer_close` | Escape/Enter/Backspace | Close viewer |
| `viewer_switch_panes` | Tab | Switch panes |
| `viewer_open_palette` | Ctrl+Shift+P | Open viewer command palette |
| `viewer_next_file` / `viewer_previous_file` | none (palette only) | View next / previous file |
| `viewer_toggle_same_type_advance` | none (palette only) | Toggle "advance only for same type" |
