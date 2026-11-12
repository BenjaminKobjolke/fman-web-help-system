---
title: File-age shading
summary: Shade local file rows according to their modified date, with options for the scale and style.
order: 600
source: docs/functions/file-age-shading.md
---

File-age shading changes how file rows look according to their modified date. It is off by default. Run **Toggle file age shading** in the command palette to turn it on or off; fman++ briefly reports the new state. The setting applies in both panes and remains in effect when you change folders.

Only local files are shaded. Other file systems, missing timestamps and unreadable dates are left unchanged. Future modified dates count as age zero. Selected rows and the cursor row keep their normal appearance.

The options live in `Core Settings.json`:

| Setting | Default | Effect |
|---|---|---|
| `file_age_shading` | `false` | Enables the built-in file-age shading. The command palette toggle changes it while fman++ is running. |
| `file_age_scale` | `"log"` | `"log"` fades smoothly; `"steps"` uses four age levels: under one day, under seven days, under thirty days, and thirty days or older. |
| `file_age_style` | `"transparency"` | `"transparency"` fades the row, `"tint"` blends its text with the theme's `pane_new_fg` color, and `"bold"` bolds files modified within the last day. |
| `file_age_full_days` | `365` | Positive number of days at which the logarithmic scale reaches its maximum. Older files are fully shaded. |

Invalid values fall back to defaults. Transparency bottoms out at 40% opacity. In the stepped scale, the levels are 0 below one day, 1/3 below seven days, 2/3 below thirty days and 1 from thirty days onward. `file_age_full_days` affects the logarithmic scale.
