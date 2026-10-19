---
title: Video viewer
summary: Play a video inside the pane with keyboard controls, and volume that is remembered.
order: 70
parent: file-viewers
source: docs/views/VIDEO_VIEWER.md
---

The video viewer plays a video inside the active pane, in place of the file
list, so you can check a `.mp4` or `.mkv` without launching a player. Read-only,
keyboard-controlled, no on-screen sliders.

## Usage

1. Put the cursor on a video and run **View file** from the command palette —
   the same command as for text and images; fman++ picks the viewer by file type.
   (**View file in other pane** opens it in the opposite pane instead.)
2. The video fills the pane and starts playing immediately. A `current / total`
   time readout — `0:12 / 3:45` — sits below it.
3. **Escape**, **Enter** or **Backspace** closes the viewer, cursor back on the
   same file.
4. **Tab** switches to the other pane. The viewer stays open and keeps playing;
   **Tab** again brings focus back to it.
5. **Ctrl+Shift+P** opens the viewer's own command palette.

## Controls

Suitable file-list shortcuts such as `F5` (Copy) also work while viewing a video.

| Key | Action |
|---|---|
| Space | Play / pause |
| Left / Right | Seek -5s / +5s |
| Up / Down | Volume +5 / -5 (0-100), flashes `Volume: N` on screen |
| Escape / Enter / Backspace | Close viewer |
| Tab / Shift+Tab | Switch panes |
| Ctrl+Shift+P | Open viewer command palette |

**Palette entries:** *Play / Pause*, *Restart*, *Mute / Unmute*, *Reset volume*,
*Exit viewer*. Mute has no default key — bind one yourself if you want it.

**Volume and mute persist across sessions.** The last value you set with
Up/Down, *Reset volume* or *Mute / Unmute* comes back the next time you open any
video.

## Behaviour

- **Read-only**, same as the image viewer.
- **One viewer at a time per pane.** Opening a video while a text or image
  viewer (or another video) is open in that pane replaces it. The other pane is
  unaffected.
- **No zoom.** A video fills the pane at its native aspect ratio.
- **Letterboxed with the pane's theme color.**
- **Recognized extensions:** `.mp4`, `.m4v`, `.mkv`, `.webm`, `.avi`, `.mov`,
  `.wmv`, `.flv`, `.mpg`, `.mpeg`, `.ogv`, `.3gp`, `.ts`. Anything else opens in
  the text or image viewer instead.

## Bindable commands

Every action above is also a viewer-only command you can bind in your own
`Viewer Key Bindings (<OS>).json` — a separate file from the normal key
bindings. A rebind always beats the default key.

| Command | Default key | Action |
|---|---|---|
| `video_toggle_pause` | Space | Play / pause |
| `video_seek_forward` | Right | Seek +5s |
| `video_seek_backward` | Left | Seek -5s |
| `video_volume_up` | Up | Volume +5 |
| `video_volume_down` | Down | Volume -5 |
| `video_mute` | none | Toggle mute |
| `video_reset_volume` | none | Volume to 100 |
| `video_restart` | none | Restart from 0:00 |
| `viewer_close` | Escape/Enter/Backspace | Close viewer |
| `viewer_switch_panes` | Tab | Switch panes |
| `viewer_open_palette` | Ctrl+Shift+P | Open viewer command palette |
| `viewer_next_file` / `viewer_previous_file` | none | View next / previous file |
| `viewer_toggle_same_type_advance` | none | Toggle "advance only for same type" |

## Playback

Video is decoded by libmpv, which brings its own codecs — `mp4`, `mkv`, `webm`,
`avi` and `mov` play the same on Windows, macOS and Linux regardless of what is
installed on the system.

- **On Windows there is nothing to set up.** The first time you open a video,
  fman++ downloads the required library once into your user data directory, with a
  real progress dialog you can cancel. Cancel it, or let it fail, and you get an
  alert instead of the viewer — nothing else breaks.
- **On macOS and Linux, install mpv yourself:** `brew install mpv` or
  `apt install libmpv2`. fman++ finds it automatically.
- If the library is missing, only the video viewer is affected. Browsing files
  and every other command keep working.
