---
title: Window opacity
summary: Make the fman++ window see-through, at a level that survives theme switches.
order: 90
parent: themes
source: docs/functions/window-opacity.md
---

fman++'s window can be made see-through from the command palette. The change
applies immediately and is remembered across restarts.

## Usage

1. Press **Ctrl+Shift+P** and run **Set window opacity**.
2. The list offers **Theme default**, then `100%` down to `70%` in 5% steps. The
   value in use is preselected and marked `current`.
3. Pick one. The window fades right away.

**Theme default** drops your choice again and lets the active theme decide. That
is the way back — not `100%`, which pins the window opaque under *every* theme,
including one that ships translucent.

*Transparency*, *opacity*, *alpha* and *translucent* find the command in the
palette too. It has no default key binding; bind one like any other command:

```json
{ "keys": ["Ctrl+Alt+O"], "command": "set_window_opacity" }
```

## Notes

- **The whole window fades, text included.** fman++ does not do background-only
  translucency.
- **Only the main window fades.** The command palette, prompts, alerts and
  progress dialogs are separate windows and stay fully opaque, so whatever you
  are reading or typing into never gets harder to read than the file list behind
  it.
- **The picker stops at 70%** because below that the file list gets hard to read
  against whatever is behind it. The underlying setting goes down to 0.3 if you
  bind your own command.
- **Your setting beats the theme's.** Once you pick a value it survives every
  theme switch, including to a theme that asks for its own opacity — such as
  **Matrix**. *Theme default* hands control back.
- **A file viewer that is already open fades with the window.** It is part of the
  window, unlike the color case, where an open viewer keeps its old colors after
  a theme switch.

## On Windows, another tool can undo it

The fade lives in a Windows window style flag. A window manager that overwrites
that style instead of adding to it — a common line in AutoHotkey maximize or
resize scripts — drops the flag and the window turns opaque again. fman++ does not
notice, so it still reports the old value. Pick the value again from the palette
to bring the fade back, or fix the script.
