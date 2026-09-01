---
title: Fonts
summary: Which typeface fman draws its UI in — what ships with it, what a theme may ask for, and how to add your own.
order: 200
source: docs/FONTS.md
---

This is the *family*, not the size. How big the file list's text is belongs to
**Pane font size**; the two are independent, and neither undoes the other.

## Bundled families

fman ships eight families, so a theme can ask for a look without depending on
what your machine happens to have installed.

| Family | Kind | Used by | Licence |
|---|---|---|---|
| **Roboto** | sans | the Windows default | Apache 2.0 |
| **Open Sans** | sans | the Linux default | Apache 2.0 |
| **JetBrains Mono** | monospace | Dracula, WezTerm | OFL 1.1 |
| **Fira Code** | monospace | Gruvbox Dark | OFL 1.1 |
| **IBM Plex Sans** | sans | Nord | OFL 1.1 |
| **Public Sans** | sans | Solarized Dark, Solarized Light | OFL 1.1 |
| **Share Tech Mono** | monospace | Matrix | OFL 1.1 |
| **Atkinson Hyperlegible** | sans | High Contrast | OFL 1.1 |

Every family is offered under every theme — the pairing above is only what each
theme asks for by default. Monokai, Dark and Light name no font on purpose.

Roboto and Open Sans are the families fman has always drawn in, and they are the
only two that are not on every platform:

| Build | Ships |
|---|---|
| Windows | Roboto and the six (Open Sans dropped) |
| Linux | Open Sans and the six (Roboto dropped) |
| macOS | the six only — the default there is the system's Helvetica Neue |

## Switching

Open the command palette (`Ctrl+Shift+P`) and run **Select font**. The list is
every family Qt can see — the bundled ones *and* everything installed on your
system — with the active one preselected and marked `current`, and
**Theme default** first.

Picking one applies **immediately**, no restart, and is remembered across
restarts. **Theme default** forgets your choice so the active theme decides
again.

**Reset font** does the same thing without opening the picker, the way
**Reset font size** undoes a zoom. Use it when a theme you switched to keeps
drawing in the family you once picked.

The list is long, because it really is every installed family — type a few
letters instead of scrolling. The palette matches initials too, so `jbm` finds
*JetBrains Mono*.

### A family fman cannot find is not an error

Qt quietly falls back to its own font. fman cannot tell a typo from a family you
happen not to have installed, so it never refuses one. If a pick appears to do
nothing, the family is not installed under that exact name.

## What a theme can ask for

A theme may name a font family beside its colors:

```json
{
	"colors": { "pane_bg": "#000000" },
	"font": "Share Tech Mono"
}
```

The value is a family **name**, not a file: anything Qt can see, bundled or
installed — `Consolas`, `Menlo`, `DejaVu Sans Mono`. Leaving the key out means
"don't touch it", so switching theme always sets the family, fman's default
included.

Your own choice wins over the theme's and survives a theme switch, until you
pick **Theme default** again:

| # | Wins | Where it lives |
|---|---|---|
| 1 | Your **Select font** choice | `font` in `%APPDATA%/fman/Local/Settings.json` |
| 2 | The active theme's `"font"` | the theme's JSON file |
| 3 | fman's default for the platform | built in |

### The platform defaults

| Platform | Default family |
|---|---|
| Windows | `Roboto` |
| Linux | `Open Sans` |
| macOS | `Helvetica Neue` |

## Adding your own font

Every `.ttf` in a plugin's directory is registered when that plugin loads, and
the search is **recursive**. Dropping a font into your own plugin folder — or a
subfolder of it — makes its family available to `Theme.css`, to a theme's `font`
key and to **Select font**, with no packaging step. That is the same mechanism
the bundled families use.

To use it without writing a theme, set it in your own
`%APPDATA%/fman/Plugins/User/Settings/Theme.css`:

```css
* { font-family: "My Font"; }
```

That pins the family against every theme, the way pinning a color does.
**Select font** is the friendlier way to do the same thing, and unlike an edit
here it is undoable from the palette.
