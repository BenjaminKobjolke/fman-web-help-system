---
title: Scroll margin
summary: Keep a chosen number of rows visible above and below the keyboard cursor.
order: 500
source: docs/SCROLL_MARGIN.md
---

The scroll margin keeps rows visible above and below the keyboard cursor, giving you more context while moving through or selecting files. Near the first or last file, the margin shrinks so the cursor can reach the end. Mouse clicks do not move the list.

Set `scroll_margin` in `%APPDATA%/fman/Local/Settings.json`:

```json
{
  "scroll_margin": 3
}
```

The default is `0`, which turns the margin off. Larger values keep the cursor nearer the middle of the visible list. Invalid values count as `0`. Restart fman++ after changing this setting.
