---
title: Text viewer
summary: Read and quickly edit a text file inside the pane, with zoom, reload and tail mode.
order: 50
parent: file-viewers
source: docs/views/TEXT_VIEWER.md
---

The text viewer shows a file's contents directly inside the active pane, in
place of the file list. It opens read-only, so you can peek at a file without
waiting on an external editor — and switch it to editable when you want to make
a quick change.

## Usage

1. Put the cursor on a file and run **View file** from the command palette (or
   **View file in other pane** to open it in the opposite pane and keep this
   pane's file list visible).
2. The contents fill the pane. Arrow keys move the cursor, **Shift+arrow**
   selects, the mouse selects too, and long lines wrap to the pane width.
   **Home** / **End** jump to the top / bottom of the file and **PgUp** /
   **PgDown** page through it. (That is view mode; while editing, Home / End go
   to the start / end of the line as usual.)
3. **Escape**, **Enter** or **Backspace** closes the viewer and puts the cursor
   back on the same file.
4. **Tab** switches to the other pane. The viewer stays open; **Tab** again
   brings focus straight back to it.
5. **Ctrl+Shift+P** opens the viewer's own command palette.

## Editing

The viewer opens read-only. Press **Ctrl+Shift+P** for its own palette — a
fuzzy-search picker scoped to this viewer, separate from fman++'s global command
palette.

**In view mode:** *Exit viewer*, *Edit file*, *Reload from disk*,
*Enable/Disable auto-reload*, *Enable/Disable tail mode (follow end)*,
*Increase font size*, *Decrease font size*, *Reset font size*.

**In edit mode:** *Save file*, *Save file as…*, *Revert / reload from disk*,
*Enable/Disable auto-reload*, *Enable/Disable tail mode (follow end)*,
*Increase font size*, *Decrease font size*, *Reset font size*, *Exit viewer*.

- **Not every file can be edited.** *Edit file* only makes the buffer editable
  if the file was not size-truncated and its bytes are strict UTF-8 — the
  replacement decoding used for display is lossy, so saving it back would
  corrupt the original. A file that does not qualify gets an alert explaining
  why instead of edit mode.
- **Tab behaves differently in edit mode.** In view mode it switches panes; once
  editing, it types a literal tab character. Use *Exit viewer* to leave edit
  mode.
- **Unsaved changes are protected.** *Exit viewer* prompts Save / Discard /
  Cancel; *Revert / reload from disk* prompts Discard / Cancel. Running **View
  file** on a different file while this pane has unsaved edits prompts too — the
  new file is not opened until you save, discard or cancel.
- **Line endings.** Saving a file that used CRLF rewrites it with LF. If you
  need CRLF preserved, edit that file in an external editor instead.
- **Save file as…** asks for a full destination path and continues editing that
  new path.

## Reload and auto-reload

- **Reload keeps your place.** *Reload from disk* and *Revert / reload from
  disk* preserve the scroll position and cursor location instead of jumping back
  to the top.
- **Auto-reload** is an opt-in toggle per open file: the viewer watches the file
  and reloads whenever it changes, still preserving scroll position. It is
  session-only — every newly opened file starts with it off.
- **Tail mode** is the log-following variant: each reload jumps to the end of the
  file, like `tail -f`. You can switch between plain auto-reload and tail mode
  without turning auto-reload off first.
- **Unsaved edits are never overwritten.** If the file changes on disk while you
  have unsaved changes, the reload is skipped and the status bar shows
  `File changed on disk (not reloaded)`.

## Zoom

The viewer has its own font-size zoom, independent of the file panes' zoom.

- Whatever keys **Increase font size** / **Decrease font size** are bound to
  (`Alt+Up` / `Alt+Down` by default) also zoom the viewer's text — there is no
  separate binding to learn, and it works in edit mode without interfering with
  typing.
- The same two actions plus a palette-only **Reset font size** are in the
  viewer's palette, each showing its current shortcut.
- The chosen size is remembered separately from the pane zoom and survives a
  restart, until you reset it.

Rebinding `increase_pane_font_size` / `decrease_pane_font_size` changes what the
viewer listens for too — it looks up the current binding rather than assuming
`Alt+Up` / `Alt+Down`.

## Bindable commands

In view mode for a file, suitable file-list shortcuts such as `F5` (Copy) also
work.

The palette actions are viewer-only commands you bind in your own
`Viewer Key Bindings (<OS>).json` — a separate file from the normal key
bindings. A rebind always beats the default key.

| Command | Default key | Mode | Action |
|---|---|---|---|
| `text_edit` | none (palette only) | view | Edit file |
| `text_reload` | none (palette only) | view | Reload from disk |
| `text_toggle_auto_reload` | none (palette only) | view | Enable/disable auto-reload |
| `text_toggle_tail` | none (palette only) | view | Enable/disable tail mode |
| `text_save` | none (palette only) | edit | Save file |
| `text_save_as` | none (palette only) | edit | Save file as… |
| `text_revert` | none (palette only) | edit | Revert / reload from disk |
| `text_go_to_top` | Home (view mode) | both | Jump to the top of the file |
| `text_go_to_bottom` | End (view mode) | both | Jump to the bottom of the file |
| `text_page_up` | PgUp (view mode) | both | Page up |
| `text_page_down` | PgDown (view mode) | both | Page down |
| `viewer_close` | Escape/Enter/Backspace | both | Close viewer |
| `viewer_switch_panes` | Tab | view only | Switch panes |
| `viewer_open_palette` | Ctrl+Shift+P | both | Open viewer command palette |
| `viewer_next_file` / `viewer_previous_file` | none (palette only) | view | View next / previous file |
| `viewer_toggle_same_type_advance` | none (palette only) | view | Toggle "advance only for same type" |

## Behaviour

- **Read-only by default.** You can always navigate and select text — for
  copying, say — but the buffer only becomes writable after *Edit file*.
- **Word-wrapped** at the pane's width, no horizontal scrolling.
- **Size-capped.** At most 2 MB of a file is read; anything larger is truncated
  with a trailing notice, so a huge file cannot freeze the UI.
- **Encoding-safe.** Content is decoded as UTF-8 with invalid bytes replaced by a
  placeholder character, rather than guessing an encoding or refusing the file.
- **Plain text only.** A `.md` file is shown as raw text, not rendered.
- **Follows the active theme.**
