---
title: Window title and bars
summary: What sits at the top of the fman++ window — the two-pane path in the title, and how to take the bar away.
order: 30
source: docs/WINDOW_TITLE_AND_BARS.md
---

The fman++ window title shows the current path of both panes, so you can tell two
windows apart from the taskbar or a window switcher. There is nothing to
configure.

## Format

```
fman++ - file manager - <left pane path> | <right pane path>
```

For example:

```
fman++ - file manager - C:\test | D:\GIT\BenjaminKobjolke\fman\debug
```

- A fixed `fman++ - file manager` prefix.
- Then the pane paths in **left, right** order, separated by ` | `.

## Behaviour

- **It updates live.** Navigating either pane — opening a folder, going up,
  jumping via the location bar — refreshes the whole title, not just that pane's
  half.
- **Unrenderable paths are skipped** rather than shown as garbage. If neither
  pane has a path that can be shown, the title falls back to just the prefix.

## The bars around the panes

Two of the bars fman++ sits inside can be switched off from the command palette,
one at a time, and each stays off across restarts.

| Command | What it hides |
|---|---|
| **Toggle title bar** | The OS title bar — the title above, and the window frame with it. Every platform. |
| **Toggle menu bar** | fman++'s **Help** menu. **macOS only** — there is no menu on Windows or Linux, so the command never appears in the palette there. |

Hiding the title bar hides the window title with it. fman++ still sets the string;
there is just nothing drawing it, and it comes back untouched when the bar does.

The window keeps the screen space it had either way: hiding the title bar grows
the panes into the row the bar occupied instead of shrinking the window, and
showing the bar again puts the window back at exactly the size it was.

The bar *below* the panes has its own command, **Toggle status bar**, on every
platform.
