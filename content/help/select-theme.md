---
title: Select theme
summary: Switch fman's colors from the command palette, applied immediately and remembered.
order: 300
parent: themes
source: docs/functions/select-theme.md
---

**Select theme** switches fman's color theme from the command palette and
applies it immediately — no restart.

## Usage

1. Press `Ctrl+Shift+P` and run **Select theme**.
2. A quicksearch list shows every installed theme. The active one is
   preselected and marked `current`. Type to fuzzy-filter, like any other
   palette list.
3. Pick one with Enter or a click. The panes, header, status bar, location bar,
   dialogs and the palette itself recolor right away, and the choice is
   remembered across restarts.

## Bundled themes

**Monokai** (default), **Dark**, **Light**, **Solarized Dark**,
**Solarized Light**, **Nord**, **Dracula**, **Gruvbox Dark**,
**High Contrast**, **WezTerm** and **Matrix**.

**Matrix** also ships slightly translucent (`"opacity": 0.92`). Use
**Set window opacity** to override that, or to fade any other theme.

## What a theme can change besides colors

- **Icons.** A theme may name an icon set and an icon size, so switching theme
  can change every icon in the panes. **Select icon set** overrides that, or
  picks a set without changing your colors.
- **Font.** A theme may name a font family too — eight of the eleven bundled
  themes do. **Select font** overrides that, or picks a family without changing
  your colors.

## Commands

| Command name | Palette label | Default key |
|---|---|---|
| `select_theme` | Select theme | none (palette only) |

*Change theme*, *color scheme*, *colors*, *dark mode* and *skin* find it too.
They are hidden keywords, so the row still reads **Select theme**.

```json
{ "keys": ["Ctrl+Alt+T"], "command": "select_theme" }
```

## Notes

- A file viewer that is **already open** — text, image or video — keeps its old
  colors until you close and reopen it. Those viewers copy the pane's colors
  when they open.
- Context menus on Windows and macOS stay native-looking under every theme.
- The choice is saved as `theme` in `%APPDATA%/fman/Local/Settings.json`.
