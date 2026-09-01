---
title: Toggle title bar and menu bar
summary: Take the window frame away for a borderless fman — and how to get it back once there is nothing left to click.
order: 320
parent: window-title
source: docs/functions/window-bars.md
---

Two commands turn off window chrome fman does not need. Each toggles on its own,
applies immediately, and is remembered across restarts.

- **Toggle title bar** — the OS title bar around the window. Every platform.
- **Toggle menu bar** — fman's **Help** menu. **macOS only:** that menu is the
  only one fman has, and it lives in the system menu bar at the top of the
  screen rather than inside fman's window. On Windows and Linux fman has no menu
  at all, so the command is not registered and does not appear in the palette.

## Usage

1. Press `Ctrl+Shift+P` and run **Toggle title bar** — or, on macOS,
   **Toggle menu bar**.
2. The bar disappears right away. Running the same command again brings it back.

There is no on/off list to pick from: each command flips the bar it names.

## Commands

| Command name | Palette label | Platform | Default key |
|---|---|---|---|
| `toggle_title_bar` | Toggle title bar | all | none (palette only) |
| `toggle_menu_bar` | Toggle menu bar | macOS only | none (palette only) |

*Frameless*, *borderless*, *window frame* and *caption* find the first;
*help menu*, *hide menu* and *chrome* find the second where it exists. They are
hidden keywords, so the rows still read **Toggle title bar** and
**Toggle menu bar**.

Bind them like any other command:

```json
{ "keys": ["Ctrl+Alt+T"], "command": "toggle_title_bar" },
{ "keys": ["Ctrl+Alt+M"], "command": "toggle_menu_bar" }
```

## Before you turn the title bar off

**A window with no title bar has no close button and cannot be dragged.** It is
a real frameless window, so the OS draws nothing to grab: no drag, no
double-click to maximize, no edge resizing, no snapping to a screen half.

`Ctrl+Shift+P` → **Toggle title bar** is the way back, and it is worth binding a
key to it *before* you first turn the bar off. While the bar is gone,
**Center window** still moves the window, as does the OS's own move shortcut
(`Alt+Space`, then `M`, on Windows).

Hiding the title bar also takes away the topmost row of the window, so on
Windows and Linux the palette is then only reachable by its key binding.

## Notes

- **The window keeps the screen space it had.** Hiding the title bar grows the
  panes into the row the bar occupied instead of shrinking the window by it;
  showing it again restores the size the window had before the toggle.
- **The state survives a restart without a flash.** Both bars are applied before
  the window is first shown, so a hidden bar is never briefly visible at startup.
- **Hiding the title bar hides the window title with it.** fman still sets the
  two-pane path string, nothing draws it, and it returns unchanged when the bar
  does.
- **On macOS the menu bar is the system one**, at the top of the screen rather
  than inside fman's window. **Toggle menu bar** hides the **Help** menu there.
  **Toggle title bar** works everywhere.
- **The two bars never sit on top of each other.** On macOS the Help menu is in
  the system bar, not below fman's title bar; on Windows and Linux there is no
  menu.
- The state is saved as `title_bar_visible` and `menu_bar_visible` in
  `%APPDATA%/fman/Local/Settings.json`, beside `theme` and `window_opacity`. A
  key is only written while its bar is hidden — a visible bar is the absence of
  the key.
