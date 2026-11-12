---
title: Drag selection
summary: Select a range of rows by dragging from the empty part of a file row.
order: 410
source: docs/DRAG_SELECTION.md
---

Drag from the empty part of a row to select a range. A click alone does not change the selection. Dragging a file name still starts a file drag to another pane or app.

A plain drag replaces the selection with the rows between the starting row and the pointer. Hold Ctrl (or Cmd on macOS) as you start dragging to add the range to the selection. Move back toward the starting row to shrink the range. At the edge of the list, the view scrolls and extends the selection; in compact view, it scrolls horizontally.

Drag selection is on by default. Run **Toggle drag selection** to turn it off; dragging from the empty part of a row then drags the file. The choice is saved in `%APPDATA%/fman/Local/Settings.json` under `drag_selection`.
