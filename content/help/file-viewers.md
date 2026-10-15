---
title: File viewers
summary: Preview text, images and video inside the pane itself, without launching another app.
order: 40
source: docs/viewers/FILE_VIEWERS.md
---

fman can show a file's contents **inside the active pane**, replacing the file
list until you close it — no external app launch, no waiting. You can peek at
(and, for text, quickly edit) a file without leaving fman.

## Opening a viewer

Put the cursor on a file and run **View file** from the command palette, or
**View file in other pane** to open it in the opposite pane and keep browsing.
Neither has a default key binding — bind your own if you want one:

```json
{ "keys": ["Ctrl+Alt+V"], "command": "view_file" }
```

| Command name | Palette label |
|---|---|
| `view_file` | View file |
| `view_file_in_other_pane` | View file in other pane |

*View*, *internal viewer* and *preview* find the command in the palette too.

**View file in other pane** keeps the keyboard focus in the current pane, so you
can keep moving the cursor down the folder and preview each file in the other
pane as you go. With only one pane open it views in place instead.

## The viewer is picked by file type

You don't choose which viewer opens:

| File type | Viewer | Recognized by |
|---|---|---|
| Images | Image viewer | `.png .jpg .jpeg .gif .bmp .webp .ico .svg` |
| Video | Video viewer | `.mp4 .m4v .mkv .webm .avi .mov .wmv .flv .mpg .mpeg .ogv .3gp .ts` |
| Text-like | Text viewer | everything else that is not binary |

| | Text | Image | Video |
|---|---|---|---|
| Editable | Yes (UTF-8, untruncated) | No | No |
| Zoom | Font size | Scale + pan | — (fills pane) |
| Extras | auto-reload / tail mode | animated GIFs play | play/pause, seek, volume (persists) |

## Shared behaviour

- **Close:** `Escape`, `Enter` or `Backspace` returns to the file list with the
  cursor back on the same file. Text edit mode with unsaved changes prompts
  first.
- **Switch panes:** `Tab` / `Shift+Tab`, same as the file list. The viewer stays
  open; `Tab` back returns focus to it. (In text edit mode `Tab` types a tab.)
- **Own command palette:** `Ctrl+Shift+P` opens a palette scoped to the viewer,
  separate from the global one. It also offers the pane commands that make sense
  for the file on screen.
- **Shared file shortcuts:** `F5` copies the viewed file to the other pane, `F6`
  moves it, and other suitable file-list shortcuts work too. Viewer-specific keys
  win.
- **Next / previous file:** **Next file** and **Previous file** in the viewer
  palette step to the neighbouring file in the folder, following the pane's sort
  order, without closing the viewer. **Advance only for same type** (on by
  default) keeps the image viewer from stepping into a video; turn it off to
  walk across every viewable file, switching viewer as needed.
- **Bindable keys:** viewer actions are rebound in a **separate**
  `Viewer Key Bindings (<OS>).json`, not the normal key bindings file.
- **One at a time per pane:** opening a viewer replaces whatever viewer is
  already open in that pane. The other pane is unaffected.
- **Theme-aware:** background and letterbox colors follow the active theme.

## When a file cannot be viewed

- A **binary** file (`.exe`, `.zip`, and similar — detected by reading a chunk
  and looking for a NUL byte) shows an alert instead of dumping garbled bytes
  into the text viewer.
- A **directory**, or a path that is not a local file, shows an alert.
- Nothing under the cursor shows "No file is selected!".

Pressing `Enter` or double-clicking still opens a file with the OS default
application — viewing is a separate command. If you would rather have `Enter`
use the internal viewer, see *Open or view*.
