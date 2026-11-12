---
title: Sort arrow placement
summary: Choose where the sort arrow appears in a column header.
order: 510
parent: toggle-columns
source: docs/SORT_ARROW.md
---

By default, the sort arrow appears beside the sorted column's label, for example `Name ▲`. Set `sort_arrow_position` in `%APPDATA%/fman/Local/Settings.json` to choose another placement:

| Value | Placement |
| --- | --- |
| `after` | After the label (default) |
| `before` | Before the label |
| `edge` | At the right edge of the column, as in earlier versions |

Restart fman++ after changing the setting. If a narrow column cannot fit the label and arrow side by side, the arrow stays at the right edge so it does not overlap the label. A custom theme that defines its own header arrows is not affected by this setting.
