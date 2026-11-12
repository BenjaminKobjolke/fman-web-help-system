---
title: Window title and bars
summary: What sits at the top of the fman++ window — the two-pane path in the title, and how to take the bar away.
order: 30
source: docs/WINDOW_TITLE_AND_BARS.md
---

The fman++ window title shows the folder names from both panes, so you can tell
windows apart from the taskbar or a window switcher. It updates as either pane
navigates.

## Format

```
<left folder> | <right folder> - fman++
```

For example:

```
test | debug - fman++
```

- Folder names in **left, right** order, separated by ` | `, followed by
  ` - fman++`. A folder name is the part after the last slash; trailing
  separators are ignored. Drive roots and URLs without a folder segment keep
  their full text.

## Behaviour

- **It updates live.** Navigating either pane — opening a folder, going up,
  jumping via the location bar — refreshes the whole title, not just that pane's
  half.
- **Unrenderable paths are skipped** rather than shown as garbage. If neither
  pane has a path that can be shown, the title is just `fman++`.

## The bars around the panes

Two of the bars fman++ sits inside can be switched off from the command palette,
one at a time, and each stays off across restarts.

| Command | What it hides |
|---|---|
| **Toggle title bar** | The OS title bar — the title above, and the window frame with it. Every platform. |
| **Toggle menu bar** | fman++'s **Help** menu. **macOS only** — there is no menu on Windows or Linux, so the command never appears in the palette there. |
| **Toggle pane location bars** | The full folder path line above each pane. |

**Toggle pane location bars** hides or restores the path line above both panes.
It is visible by default, and your choice is remembered across restarts in
`pane_location_bars_visible` in `%APPDATA%/fman/Local/Settings.json`.

Hiding the title bar hides the window title with it. fman++ still sets the string;
there is just nothing drawing it, and it comes back untouched when the bar does.

The window keeps the screen space it had either way: hiding the title bar grows
the panes into the row the bar occupied instead of shrinking the window, and
showing the bar again puts the window back at exactly the size it was.

The bars and messages below the panes have three commands: **Toggle status
bar** hides the window-wide message strip, **Toggle pane status lines** shows
or hides each pane's message line, and **Toggle pane messages in status bar**
controls whether pane messages also appear in the window bar. Their settings
persist across restarts; see [Status bar](status-bar.md).
