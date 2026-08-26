---
title: Window title
summary: The window title shows both panes' paths, kept in sync as you navigate.
order: 30
source: docs/WINDOW_TITLE.md
---

The fman window title shows the current path of both panes, so you can tell two
windows apart from the taskbar or a window switcher. There is nothing to
configure.

## Format

```
fman - file manager - <left pane path> | <right pane path>
```

For example:

```
fman - file manager - C:\test | D:\GIT\BenjaminKobjolke\fman\debug
```

- A fixed `fman - file manager` prefix.
- Then the pane paths in **left, right** order, separated by ` | `.

## Behaviour

- **It updates live.** Navigating either pane — opening a folder, going up,
  jumping via the location bar — refreshes the whole title, not just that pane's
  half.
- **Unrenderable paths are skipped** rather than shown as garbage. If neither
  pane has a path that can be shown, the title falls back to just the prefix.
