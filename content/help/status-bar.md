---
title: Status bar
summary: The one-line strip along the bottom — what it tells you, and how to switch it off.
order: 260
source: docs/STATUSBAR.md
---

The status bar is the strip along the bottom of fman++'s window. It shows the
latest message from fman++ or a plugin, and can also show a selection summary,
an Incognito marker, and controls for minimized progress dialogs.

## What it shows

There is a single message slot, left-aligned. Whatever wrote last wins.

| When | Message |
|---|---|
| Startup | `v1.7.8 ready.` — or `Updated to v1.7.8. Changelog` after a version change, where *Changelog* is a clickable link |
| Copy or cut to the clipboard | `Copying foo.txt`, `Cutting foo.txt and 3 other files`, `Copied C:\dir\foo.txt to the clipboard` |
| Reloading plugins | `Reloaded 2 plugins.` |
| Saving in the text viewer | `Saved`, `Saved as <path>`, `Could not save: <reason>` |
| Auto-reload and tail toggles | `Auto-reload on`, `Tail mode on`, `Auto-reload off`, `File changed on disk (not reloaded)` |
| Any other viewer action | `Reloaded from disk`, `Edit mode`, `Font size 12`, `Zoom 125%`, `Paused`, the name of the file Next or Previous landed on |
| Viewer navigation | `Advance only for same type: on`, `No further file to view` |
| Long-running work | a "doing X…" line for as long as the work runs, then cleared |
| Nothing recent | `Ready.` |

Messages either sit there until something replaces them, or carry a timeout
after which the bar falls back to `Ready.` — 2 to 5 seconds for the routine
ones, longer for hints. A message may contain links; they open in your browser.

## Selection summary

When a pane has selected files or folders, the summary can show a count and
total size, such as `3 files, 2 folders selected, 12.4 MB`. By default, sizes
include files but not folder contents. Run **Toggle selection summary
location** to show it in each pane, in the window bar for the focused pane, or
in both. Run **Toggle selection folder sizes** to include folder contents in a
background calculation; the default is off. Both commands are in the palette
and have no default shortcut.

## Incognito and minimized progress dialogs

While Incognito mode is on, a permanent **Incognito** marker appears at the
right of the bar. Toggle the mode to stop recording Go To visits; restarting
turns it off. See [Incognito mode](functions/go-to.md#incognito-mode).

Each minimized progress dialog adds an icon beside the marker. Hover to see
the task title and click to restore it. **Resume tool window** restores the
only minimized dialog directly or lets you choose when there are several;
**Minimize tool window** minimizes the most recently shown eligible dialog.

## Turning it off

Press `Ctrl+Shift+P` and run **Toggle status bar**. The bar disappears
immediately and the panes grow into the row it occupied — the window keeps its
size on screen. Running the command again brings it back, and the choice
survives a restart.

| Command name | Palette label | Platform | Default key |
|---|---|---|---|
| `toggle_status_bar` | Toggle status bar | all | none (palette only) |
| `toggle_incognito` | Toggle incognito mode | all | none (palette only) |
| `toggle_pane_status_lines` | Toggle pane status lines | all | none (palette only) |
| `toggle_pane_messages_in_status_bar` | Toggle pane messages in status bar | all | none (palette only) |
| `resume_tool_window` | Resume tool window | all | none (palette only) |
| `minimize_tool_window` | Minimize tool window | all | none (palette only) |

*statusbar*, *hide status bar*, *message bar*, *bottom bar* and *chrome* also
find the row. They are hidden keywords, so it still reads **Toggle status bar**.

```json
{ "keys": ["Ctrl+Alt+S"], "command": "toggle_status_bar" }
```

## Notes

- **Messages are not lost, just not drawn.** fman++ and plugins keep posting them
  while the bar is hidden, and the latest one is there when you bring the bar
  back. Nothing errors, nothing queues up.
- **Hiding it is not the same as hiding the title bar.** The status bar is an
  ordinary part of the window, so switching it off has none of the
  frameless-window caveats — the window stays draggable and closable.
- **The state survives a restart without a flash**, applied before the window is
  first shown.
- The state is saved as `status_bar_visible` in
  `%APPDATA%/fman/Local/Settings.json`. The key is only written while the bar is
  hidden; a visible bar is the absence of the key.

## Theming

A theme paints the bar through two tokens:

| Token | What it paints |
|---|---|
| `statusbar_bg_top` | Top of the vertical background gradient |
| `statusbar_bg_bottom` | Bottom of it — falls back to `statusbar_bg_top`, so naming only the one token gives a flat bar |

The text colour is `bright_fg` and the line above the bar is `border`; both are
shared with the rest of the chrome. The font size is set in `Theme.css` under
the `.statusbar` selector — 8pt by default, 11pt on macOS.

## Pane status lines

Each pane can show a short-lived message line below its file list or viewer.
The line is hidden when empty, and each pane keeps its own message. By default,
pane messages appear both there and in the window-wide status bar. Run **Toggle
pane status lines** to show or hide the lines, and **Toggle pane messages in
status bar** to control whether those messages also appear in the window bar.
Both settings persist across restarts and the commands have no default
shortcut. If pane lines are hidden, pane messages go to the window bar.

The pane line shares the status bar's theme by default; themes can customize
its background, text, and border independently.
