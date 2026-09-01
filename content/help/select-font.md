---
title: Select font
summary: Pick the typeface fman draws its UI in, from the palette, applied immediately.
order: 210
parent: fonts
source: docs/functions/select-font.md
---

**Select font** picks which font family fman draws its UI in. The default is
the one fman has always used on your platform; six more ship with it, and every
font installed on the machine is offered too.

## Usage

1. Open the command palette with `Ctrl+Shift+P`.
2. Type *select font* and press `Enter`.
3. Pick a family. The list is every font Qt can see, with the active one
   preselected and marked `current`, and **Theme default** first.

It applies **immediately** — no restart — and is remembered across restarts.

**Theme default** is the way back: it forgets your choice so the active theme
decides again, which for a theme that names no font means fman's default for the
platform (`Roboto` on Windows, `Open Sans` on Linux, `Helvetica Neue` on macOS).

**Reset font** does exactly that without making you scroll a list of every font
on the machine.

The list is long, because it is genuinely every installed family — type a few
letters of the one you want. The palette matches on initials too, so `jbm` finds
*JetBrains Mono*.

## This is the family, not the size

`Alt+Up` and `Alt+Down` change how **big** the file list's text is. This command
changes **which typeface** the whole UI uses. They are independent: zooming never
changes the family, and picking a font never resets a zoom.

## Commands

| Command name | Palette label | Default key |
|---|---|---|
| `select_font` | Select font | none |
| `reset_font` | Reset font | none |

*typeface*, *font family*, *change font*, *monospace*, *ui font* and *text font*
also find **Select font**; *default font*, *theme font*, *reset typeface* and
*clear font* find **Reset font**. They are hidden keywords, so the rows read as
above.

```json
{ "keys": ["Ctrl+Alt+F"], "command": "select_font" }
```

## Fonts fman bundles

Six families ship with fman on top of the two it has always used (`Roboto` and
`Open Sans`), so a theme can ask for one without depending on what your machine
has installed:

| Family | The theme that uses it |
|---|---|
| JetBrains Mono | Dracula, WezTerm |
| Fira Code | Gruvbox Dark |
| IBM Plex Sans | Nord |
| Public Sans | Solarized Dark, Solarized Light |
| Share Tech Mono | Matrix |
| Atkinson Hyperlegible | High Contrast |

You can pick any of them under any theme — the pairing is only what that theme
asks for by default.

## Themes

A theme may name a font of its own, and switching theme applies it. Your own
choice **wins** and survives a theme switch, until you pick **Theme default** or
run **Reset font**:

1. Your **Select font** choice, if you have made one.
2. Otherwise the active theme's `"font"`.
3. Otherwise fman's default for the platform.

A family fman cannot find is **not** an error — Qt quietly falls back to its own
font. So if a pick appears to do nothing, the family is not installed under that
exact name.
