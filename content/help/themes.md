---
title: Themes
summary: Switch between eleven bundled color themes, or write your own in one small JSON file.
order: 80
source: docs/THEMES.md
---

fman ships eleven color themes and can load your own. Switching applies
immediately and is remembered across restarts.

## Switching theme

1. Press **Ctrl+Shift+P** and run **Select theme**.
2. The list shows every installed theme, the active one preselected and marked
   `current`. Type to fuzzy-filter, like any palette list.
3. Pick one. The panes, header, status bar, location bar, dialogs and the
   palette itself recolor right away.

Bundled: **Monokai** (the default, fman's classic look), **Dark**, **Light**,
**Solarized Dark**, **Solarized Light**, **Nord**, **Dracula**, **Gruvbox
Dark**, **High Contrast**, **WezTerm**, **Matrix**.

*Change theme*, *color scheme*, *colors*, *dark mode* and *skin* find the
command in the palette too. It has no default key binding; bind one like any
other command:

```json
{ "keys": ["Ctrl+Alt+T"], "command": "select_theme" }
```

Two things do **not** recolor live:

- A **file viewer that is already open** (text, image or video). Those take
  their colors from the pane when they open — close and reopen the file.
- **Context menus** on Windows and macOS. They imitate the native OS menu on
  purpose and stay light under every theme.

## Writing your own theme

A theme is **one JSON file** listing only the colors it changes:

```json
{
	"colors": {
		"pane_bg": "#2e3440",
		"pane_fg": "#d8dee9",
		"pane_selected_fg": "#88c0d0"
	}
}
```

- **The file name is the theme name.** `Nord.json` shows up as *Nord*.
- Put it in `%APPDATA%/fman/Themes/` on Windows,
  `~/Library/Application Support/fman/Themes` on macOS, or
  `~/.config/fman/Themes` on Linux. A file there shadows a bundled theme of the
  same name.
- Unknown color names and invalid values are ignored, not fatal — a typo costs
  you that one color, it never stops fman from starting.
- Values are any color Qt understands: `#rgb`, `#rrggbb`, `white`. A value
  containing `;`, `{` or `}` is rejected.

### You only need the 15 core colors

Colors you leave out inherit from a parent before falling back to the default.
Setting `pane_bg` alone also moves `base_bg` (what the text viewer paints on),
and setting `border` alone moves every separator and bevel in the quicksearch
popup.

| Name | What it colors |
|---|---|
| `pane_bg` | File list / dialog / overlay / filter-bar background |
| `pane_fg` | File rows |
| `pane_fg_dir` | Directory rows |
| `pane_selected_fg` | Text of selected files |
| `pane_cursor_bg` | Cursor row, and the pane's focus rectangle |
| `header_bg` | Column-header gradient, input borders |
| `muted_fg` | Header text, labels, checkboxes, item descriptions |
| `bright_fg` | Input text, status-bar text, highlighted matches |
| `border` | Overlay / filter-bar border, status-bar top edge |
| `input_bg` | Text fields (rename editor, prompts) |
| `window_bg` | Window and dialog chrome |
| `statusbar_bg_top` | Status-bar gradient |
| `popup_bg` | Quicksearch / command-palette background |
| `popup_item_fg` | Command-palette entry titles |
| `popup_selected_bg` | Selected entry in the palette |

Everything else is derived from these; override one only if the inherited value
looks wrong. The derived names are `base_bg`, `readonly_fg`, `input_border`,
`locationbar_border`, `palette_midlight`, `statusbar_bg_bottom`,
`popup_query_border`, `popup_query_inner_border`, `popup_divider_top`,
`popup_divider_bottom`, `popup_input_border`, `popup_input_bg`,
`popup_input_fg`, `popup_input_border_top` / `_left` / `_right`,
`main_window_bg`, `button_bg`, `alt_row_bg`, `button_fg`, `palette_mid`,
`palette_dark` and `palette_shadow`.

### Transparency

A theme may also make fman's window see-through. This is the one thing in a
theme file that is not a color, so it sits beside `colors`, not inside it:

```json
{
	"colors": { "pane_bg": "#000000" },
	"opacity": 0.92
}
```

- `1.0` is fully opaque, `0.3` the most transparent fman allows. A value outside
  that range, or one that is not a number, is ignored.
- The whole window fades, text included. Dialogs are separate windows and stay
  opaque.
- Leaving the key out means fully opaque, not "keep what the last theme had" —
  switching themes always sets the opacity.
- Your own choice beats the theme's; see *Window opacity*.
- The bundled **Matrix** theme ships slightly translucent.

## Fonts, padding and pinning a color

Colors are one thing, sizes another — a theme file cannot change font size or
padding. Those live in a stylesheet, and yours goes in:

```
%APPDATA%/fman/Plugins/User/Settings/Theme.css
```

That file loads last, so it wins over every theme. Edit it to change font sizes
or padding, or to pin a color a theme keeps changing. To use a theme's own color
in it, write the color's name with a `$`:

```css
.statusbar { color: $pane_fg; }
```

Put **only what you change** in that file. Copying fman's whole base stylesheet
into it copies its color values too, and those then override every theme — the
classic symptom is a command palette that stays light-on-dark after you switch
to a light theme.

The selectors it understands are `*` (everything), `th` (column headers),
`.statusbar`, `.locationbar`, `.quicksearch-query`, `.quicksearch-item`, and
`.quicksearch-item-title` / `-highlight` / `-hint` / `-description`.
