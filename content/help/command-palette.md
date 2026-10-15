---
title: Command palette
summary: Run any command by name with fuzzy search, instead of hunting for a shortcut.
order: 10
source: docs/COMMAND_PALLETTE.md
---

The command palette is a fuzzy-search picker for running any command by name
instead of hunting for a key binding. Type part of a command's name, pick from
the filtered list, press Enter.

## Opening the palette

Press `Ctrl+Shift+P` on Windows and Linux, or `Cmd+Shift+P` on macOS.

## How searching works

- **Matching is fuzzy.** The characters you type only have to appear in order,
  so `vf` finds *View file*.
- **Word order does not matter.** Space-separated words match in any order, so
  `panes show` finds *Show all panes* just as `show panes` does. In-order and
  word-start matches rank first; any-order matches fall to the bottom of the
  list.
- **Shortcut hints.** Each entry shows the key it is currently bound to, read
  live from your key bindings — rebind a command and the hint updates.
- **It remembers your last pick.** Reopening the palette pre-selects the command
  you ran last, so repeating an action is Open, then Enter.
- **Scope.** The palette lists every command available for the active pane, plus
  the global application commands.

## Commands you can only reach from the palette

These have no default key binding, so the palette is the way in:

- **Select theme** — switch fman's colours, applied immediately.
- **Set window opacity** — make the window see-through, or hand the choice back
  to the theme.
- **Follow shortcut** — Windows only, and only listed while the cursor is on a
  `.lnk` file: goes to the shortcut's destination instead of launching it (also
  found by *lnk*, *link target*, *destination*). A folder is opened; for a file,
  its folder is opened with the cursor on it.
- **Center window** — puts fman back in the middle of the screen it is already
  on. You will also find it by typing *center window on screen*, *move window to
  center* or *middle*. It centres within the available screen area, so the
  taskbar does not push it off-centre, and on two monitors it stays on the
  monitor it was already on.

To give *Center window* a shortcut, add this to your key bindings:

```json
{ "keys": ["Ctrl+Alt+C"], "command": "center_window" }
```

## Hidden search keywords

A command can also be found by words that are **not** in its name. Those
keywords are never displayed: typing *transparency* selects the row that still
reads **Set window opacity**.

Add your own in `%APPDATA%/fman/Plugins/User/Settings/Command Keywords.json`
(the equivalent `Plugins/User/Settings/` folder on macOS and Linux), next to your
`Key Bindings (<OS>).json`:

```json
{
    "set_window_opacity": ["transparency", "opacity", "alpha"],
    "pack": ["zip", "7z", "tar", "compress"],
    "video_mute": ["sound", "volume", "audio"]
}
```

- **Exact matches come first.** Typing a command's whole name, or a whole
  keyword, puts that row at the very top — which is why *exit* offers **Quit**
  before *Extract to opposite*, whose name merely happens to contain `e`, `x`,
  `i` and `t` in that order.
- **Otherwise names match first.** A row found by its real name ranks above one
  found only by a keyword, and only a name match highlights the characters you
  typed.
- **The keys are command names** — the same ones you use in your key bindings,
  viewer commands such as `video_mute` and `viewer_next_file` included.
- **Your list replaces fman's** for any command you name there, so repeat the
  built-in terms you want to keep. Every other command is left untouched.
- Terms are lowercase, and matched the same fuzzy way names are.

## Viewers have their own palettes

Each in-pane file viewer has its own command palette, also on `Ctrl+Shift+P`,
reaching the commands that make sense for what you are looking at. While a
viewer is open the file list is hidden, so the global palette cannot reach it —
that is why viewers ship their own.

| Viewer | What its palette offers |
|--------|-------------------------|
| Text viewer | Exit, Edit file, Save / Save as…, Reload / Revert, auto-reload and tail toggles, font-size zoom, Next / Previous file, Advance-same-type toggle, Delete file, Rename file…, Close-after-delete toggle, Copy / Move and other pane commands (view mode, with a file), Find… / Find next / Find previous / Exit search mode |
| Image viewer | Zoom in / out, Fit to window, Actual size (100%), Reset zoom, Next / Previous file, Advance-same-type toggle, Delete file, Rename file…, Close-after-delete toggle, Copy / Move and other pane commands, Exit |
| Video viewer | Play / Pause, Restart, Mute / Unmute, Reset volume, Next / Previous file, Advance-same-type toggle, Delete file, Rename file…, Close-after-delete toggle, Copy / Move and other pane commands, Exit |

A viewer palette fuzzy-searches and shows shortcut hints exactly like the global
one. The entries change with context — the text viewer offers different commands
in view mode than in edit mode, and *Exit search mode* only while its search is
on.

Viewer-only entries are bound in a separate `Viewer Key Bindings (<OS>).json`
file. The pane commands that also appear there — Copy, Move and the like — are
ordinary global commands: their shortcuts, and Shift+Enter edits from the
palette, use your normal key bindings file.
