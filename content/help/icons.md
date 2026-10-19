---
title: Icons
summary: Which pictures the file list draws beside each file, how big, what color — and how to ship your own set.
order: 220
source: docs/ICONS.md
---

By default fman++ shows the icons your operating system supplies — the Windows
shell, GTK on Linux, the Finder's on macOS. That is the **System** set: one grey
page for every text-ish file, one folder for every folder.

An **icon set** replaces them with a picture per file type. One ships with fman++:

- **System** — the default. The OS icons.
- **Material** — the Material Icon Theme, the set VS Code users know: a distinct
  icon per language, per well-known config file and per well-known folder name.
  829 icons.

## Switching

Open the command palette (`Ctrl+Shift+P`) and run:

| Command | What it does |
|---|---|
| **Select icon set** | Pick a set. Both panes redraw immediately, no restart. |
| **Set icon size** | 16, 20, 24, 32 or 48 pixels, or **Theme default**. |
| **Set icon color** | Recolor the set — Green, Cyan, Blue, Amber, Red, Magenta, White, Grey — or **Theme default**. |
| **Toggle real icons for programs and shortcuts** | Opt `.exe` and `.lnk` back out of the set. |

None of them needs a restart, and all of them are remembered.

## Icon size

A whole number of pixels from **12 to 64**. It applies to the **System** set
too, so it is worth setting even if you keep the OS icons.

**The rows grow with it.** Row height comes from the icon size and the pane font
size together, so a file list at `48` is a very different thing to look at than
one at `16`. If the rows are taller than the font explains, this is why.

Leaving it unset means "don't touch it" — Qt draws at its own default, 16px.

### It follows the font zoom

`Alt+Up` and `Alt+Down` move the icons too: the pane font size zoom scales the
icons by the same proportion it scales the text, so one pair of keys zooms the
whole file list rather than leaving 16px icons beside 20pt text.

It scales from *your* size, not from 16: at **Set icon size** `48`, zooming in
grows the icons from 48. The 12–64 range still caps it, so a long zoom stops the
icons before it stops the text. **Reset font size** puts both back.

The zoom is not stored separately, so **Set icon size** keeps showing the size
you picked rather than whatever the current zoom happens to draw.

## Icon color

An icon set can be **recolored**. Each icon keeps its own light and dark areas
and takes the color's hue, so a Material icon still reads as itself rather than
flattening into a silhouette.

Only an icon set answers to this. The **System** icons are the shell's own
bitmaps, so a color has no effect until you pick a set.

An untinted icon stays a drawing, redrawn crisply at whatever size the view
asks for. Recoloring has to happen on pixels, so a tinted icon is rasterized
once at 128px and scaled from there. In practice that means edges resample
slightly differently, and 128px is the ceiling — at `icon_size` 64 on a HiDPI
screen Qt asks for exactly 128 real pixels, so there is no headroom above it.
Leaving **Set icon color** on **Theme default** keeps the sharper path.

## A theme can carry all three

Beside `colors`, a theme file may name an icon set, a size and a color:

```json
{
	"colors": { "pane_bg": "#2e3440" },
	"icons": "Material",
	"icon_size": 20,
	"icon_color": "#88c0d0"
}
```

**Matrix** is the bundled example: it asks for Material in green, which is why
it is the one theme whose icons match the rest of it.

**Your own choice wins over the theme's**, and survives a theme switch:

| | |
|---|---|
| 1. | Your setting, if you have one (`icon_set`, `icon_size`, `icon_color` in `Settings.json`) |
| 2. | What the active theme asks for |
| 3. | `System`, Qt's own 16px, and the icons' own colors |

Picking **System** in *Select icon set*, or **Theme default** in *Set icon size*
or *Set icon color*, drops your override so the theme decides again. Leaving a
key out of a theme means "don't touch it", so switching theme always sets all
three — back to the defaults included.

An unusable value is ignored like any other typo, and costs you only that one
thing: a size that is not a whole number or is outside 12–64, an `icons` value
naming a set that is not installed, or an `icon_color` that is not a color Qt
understands.

## Files that keep their OS icon

- **`.ico` files** always show themselves. An icon file *is* a picture of
  itself.
- **Programs and shortcuts** (`.exe`, `.lnk`) use the icon set by default. A set
  draws every program alike, while the OS icon says *which* program it is —
  worth more in a folder full of them. **Toggle real icons for programs and
  shortcuts** opts those two extensions back out.
- **Files on network drives** already avoid the OS icon, because asking the
  shell for a `.exe`'s icon on a share reads that file over the wire. An icon
  set answers there without touching the wire, so it is the cheaper option.

## Writing your own icon set

An icon set is a **directory**, not a file. Put it in
`%APPDATA%/fman/Icons/<Name>/` — `~/Library/Application Support/fman/Icons` on
macOS, `~/.config/fman/Icons` on Linux. The directory name is the set name, and
it shadows a bundled set of the same name.

```
My Icons/
	manifest.json
	svg/
		file.svg
		folder.svg
		python.svg
		...
```

`manifest.json` is the VS Code file icon theme shape, reduced to the five keys
fman++ reads. Every one is optional:

| Key | What it does |
|---|---|
| `fileNames` | Whole file name → icon name (`"dockerfile": "docker"`) |
| `fileExtensions` | Extension → icon name (`"ts": "typescript"`) |
| `folderNames` | Folder name → icon name (`"src": "folder-src"`) |
| `file` | The icon for a file nothing else matched |
| `folder` | The icon for a folder nothing else matched |

Matching order for a file is `fileNames`, then `fileExtensions`, then `file`. A
folder only ever consults `folderNames` and `folder` — a directory called
`foo.ts` is still a directory.

**The longest extension wins.** `fileExtensions` is matched against every dotted
suffix, longest first, so `foo.d.ts` takes the `d.ts` icon rather than the `ts`
one.

Lookups are case-insensitive and **every manifest key must be lower-case** —
fman++ lower-cases the file name, not your keys.

An icon name resolves to `svg/<name>.svg`. Naming an icon the set does not ship
is not an error: that file falls back to the OS icon. Names may only contain
letters, digits, `.`, `_` and `-`, so a manifest cannot reach outside its own
`svg/`.

Any format Qt can read works, but the file must be named `.svg`. SVG is what the
bundled set uses, and it is the only thing that stays sharp at `icon_size` 48.

You do not have to author a second set in another color: `icon_color` recolors
whatever set is active, and it works on a hand-written set exactly as it does on
Material. Author your set in its own colors and let the theme tint it.

Color is the only thing a theme changes *inside* an icon. Everything else about
the drawing is the file — a set is a folder of images, not color tokens.
