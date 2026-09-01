---
title: Select icon set
summary: Swap the OS icons for a set with an icon per file type, and set their size and color.
order: 230
parent: icons
source: docs/functions/select-icon-set.md
---

**Select icon set** picks which icons the file list draws, and there are
companion commands for how big and what color. By default fman shows the ones
your operating system supplies; the bundled **Material** set gives a distinct
icon to each language, config file and well-known folder name instead.

## Usage

1. Open the command palette with `Ctrl+Shift+P`.
2. Type *select icon set* and press `Enter`.
3. Pick **Material**, or **System** to go back. The list shows every installed
   set, with the active one preselected and marked `current`.

It applies **immediately** — both panes redraw, no restart — and is remembered
across restarts.

**Set icon size** works the same way and offers 16, 20, 24, 32 and 48 pixels,
plus **Theme default**. The rows grow with the icons, and the size applies to
the OS icons too, so it is useful even on **System**.

**Set icon color** recolors the set — Green, Cyan, Blue, Amber, Red, Magenta,
White or Grey, plus **Theme default**. Each icon keeps its own shading, so it is
a recolor rather than a silhouette. It has no effect on **System**: those icons
come from the OS, not from fman.

## The size follows the font zoom

`Alt+Up` and `Alt+Down` scale the icons along with the text, in the same
proportion, from whatever size is set here. So at `48`, zooming in grows the
icons from 48 — and **Set icon size** keeps showing `48`, because the zoom is not
saved as a size of its own. **Reset font size** puts both back.

## Commands

| Command name | Palette label | Default key |
|---|---|---|
| `select_icon_set` | Select icon set | none |
| `set_icon_size` | Set icon size | none |
| `set_icon_color` | Set icon color | none |
| `toggle_executable_icons` | Toggle real icons for programs and shortcuts | none |

The palette also finds **Select icon set** by *file icons*, *folder icons*,
*icon theme*, *icon set*, *material* and *vscode icons*; **Set icon size** by
*bigger icons*, *smaller icons*, *icon scale*, *large icons* and *row height*;
**Set icon color** by *icon tint*, *recolor icons*, *colorize icons*, *colour
icons* and *icon theme color*; and the toggle by *exe icon*, *program icon*,
*shortcut icon* and *application icon*.

```json
{ "keys": ["Ctrl+Alt+I"], "command": "select_icon_set" }
```

## Files that keep their OS icon

- **`.ico` files** always show themselves — an icon file *is* a picture of
  itself.
- **Programs and shortcuts** (`.exe`, `.lnk`) use the icon set by default. An
  icon set draws every program alike, while the OS icon says *which* program it
  is, so **Toggle real icons for programs and shortcuts** opts those two
  extensions back out. It is a toggle: run it again to undo.

## Themes

A theme may carry an icon set, a size and a color of its own, the same way
**Matrix** carries an opacity — and Matrix carries all three, which is what
makes its icons green.

Your own choice here **wins over the theme's** and survives a theme switch,
until you pick **Theme default** in *Set icon size* or *Set icon color* — or
**System** in *Select icon set* — to hand the decision back.
