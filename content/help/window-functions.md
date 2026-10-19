---
title: Window functions
summary: Everything that changes the fman++ window itself — the bars, how see-through it is, and where it sits on screen.
order: 330
source: docs/WINDOW_FUNCTIONS.md
---

These are the commands that change fman++'s **window itself** rather than what is
inside it: how much of it you see, how solid it is, and where it sits on the
screen.

None of them has a default key binding. They are all reached from the command
palette (`Ctrl+Shift+P`), and all of them except **Center window** are
remembered across restarts.

| Command | What it does |
|---|---|
| `toggle_title_bar` | Hides the OS title bar, and the window frame with it |
| `toggle_menu_bar` | Hides fman++'s **Help** menu — **macOS only** |
| `toggle_status_bar` | Hides the message strip along the bottom |
| `set_window_opacity` | Makes the whole window see-through |
| `center_window` | Puts the window back in the middle of its screen |
| `minimize` | Minimizes the window |

The dim behind dialogs is the odd one out: it has no command, because it is not
something you switch on. It is a theme color.

## The bars around the panes

The title bar, the macOS menu bar and the status bar each toggle on their own,
and each stays off across restarts.

Two things worth knowing before hiding the title bar:

- A frameless window **cannot be dragged or closed with the mouse.** Read the
  caveats on **Toggle title bar and menu bar** first.
- The window keeps the screen space either way: hiding a bar grows the panes
  into the row it occupied rather than shrinking the window, and showing it
  again restores the exact previous size.

## How solid the window is

**Set window opacity** offers **Theme default**, then `100%` down to `70%`. The
whole window fades, text included — fman++ does not do background-only
translucency, and dialogs are separate windows that stay opaque.

**Theme default** is the way back to letting the theme decide. It is not the
same as `100%`, which pins the window opaque under every theme, including one
that ships translucent. A theme sets its own value with the `opacity` key; your
choice wins over it and survives a theme switch.

## Dimming behind dialogs

While a modal dialog is open — the command palette, prompts, alerts, the
file-open dialog — fman++ lays a **scrim** over the main window, so the dialog
reads against the file list instead of competing with it.

This works precisely *because* opacity does not reach dialogs: they are
top-level windows of their own, so dimming the main window leaves the dialog at
full contrast.

- It is the `scrim_bg` color token, and its **alpha byte is the strength**.
  `#80000000` — the default — is 50% black, `#40000000` a quarter, and
  `#00000000` no dim at all.
- **The progress dialog is excluded.** A copy can run for minutes, and a window
  dimmed that long costs more than the contrast is worth.
- There is no command and no setting: a theme owns it, like any other color.

## Center window

**Center window** puts fman++ back in the middle of the screen it is already on.

- Centered within the screen's *available* area, so a taskbar does not push the
  window off-centre.
- On multiple monitors it stays on the monitor it was on, rather than jumping to
  the primary one.
- Nothing is remembered — it is a one-shot move, not a mode.

*center window on screen*, *move window to center* and *middle* find it too.
They are hidden keywords, so the row still reads **Center window**.

```json
{ "keys": ["Ctrl+Alt+C"], "command": "center_window" }
```
