---
title: File coloring
summary: Color file names by extension and tune the rules for your files.
order: 670
source: docs/functions/file-coloring.md
---

File coloring gives file names a color based on their extension. It is off by
default. Run **Toggle file coloring** from the command palette to turn it on or
off in both panes.

## Settings

The `file_coloring` setting in `Core Settings.json` enables the built-in rules.
Use **Toggle file coloring** to change it while fman++ is running. The setting
defaults to `false`.

Use `file_color_rules` to customize which names get which colors. Rules are
checked in order, and the first matching rule wins:

```json
{
  "file_coloring": true,
  "file_color_rules": [
    {"patterns": ["*.jpg", "*.png", "*.svg"], "color": "file_image_fg"},
    {"patterns": ["*.zip", "*.7z", "*.rar"], "color": "#fd971f"},
    {"patterns": ["*.exe", "*.bat", "*.ps1"], "color": "pane_new_fg"}
  ]
}
```

Patterns match file names without regard to case and support shell-style
wildcards such as `*.png`. A color can be a hex value or a color name from the
active theme. The default rules cover common image, archive, audio and video,
and program extensions, using the `file_image_fg`, `file_archive_fg`,
`file_media_fg` and `file_program_fg` theme colors. Invalid settings fall back
to the defaults, and malformed rules are ignored.

## How file colors appear

File colors replace the usual text color. Hidden files stay faded, and file-age
shading remains layered over the color. Selected files and the row under the
cursor keep their usual colors. You can customize the four file-color theme
colors in your [theme](themes.md).
