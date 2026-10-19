---
title: View file
summary: Open the file under the cursor in fman++'s own viewer instead of an external application.
order: 310
parent: file-viewers
source: docs/functions/view-file.md
---

**View file** opens fman++'s internal viewer on the file under the cursor,
showing its contents inside the active pane instead of launching an external
application.

fman++ picks the viewer from the file:

- **Images** (`.png`, `.jpg`, `.jpeg`, `.gif`, `.bmp`, `.webp`, `.ico`, `.svg`)
  open the image viewer.
- **Videos** (`.mp4`, `.m4v`, `.mkv`, `.webm`, `.avi`, `.mov`, `.wmv`, `.flv`,
  `.mpg`, `.mpeg`, `.ogv`, `.3gp`, `.ts`) open the video viewer.
- **Everything else** is checked for binary content. Text-like files open the
  text viewer; a binary such as an `.exe` or a `.zip` shows an alert instead of
  being rendered as garbage.

## Usage

1. Put the cursor on a file.
2. Open the command palette, search "view" or "internal viewer", and run
   **View file**.
3. The file's contents fill the active pane. Press `Escape`, `Enter` or
   `Backspace` to close it and return to the file list — or press
   `Ctrl+Shift+P` for the viewer's own command palette.

## Commands

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `view_file` | View file | view, internal viewer, preview | none (palette only) |
| `view_file_in_other_pane` | View file in other pane | view in other pane | none (palette only) |

Those extra words are hidden keywords: they find the command without ever being
shown. Bind either like any other command:

```json
{ "keys": ["Ctrl+Alt+V"], "command": "view_file" }
```

## Previewing side by side

**View file in other pane** opens the same viewer for the file under the cursor
but mounts it in the **other** pane, so the current pane's file list stays
visible. Keyboard focus deliberately stays where it was, so you can keep moving
the cursor through the folder and preview each file as you go.

With only one pane open it falls back to viewing in place, and there the viewer
takes focus as usual.

## Notes

- **Enter and double-click are unaffected.** This is a separate, palette-only
  path — pressing Enter or double-clicking a file still opens it with the OS
  default application.
- Running the command on a directory, or on a path that is not a local file,
  shows an alert instead of opening the viewer.
- Running it with nothing under the cursor shows *"No file is selected!"*.
- **Next / previous file** inside an open viewer re-runs this command: it steps
  the pane cursor to the neighbouring file and routes to the matching viewer.
