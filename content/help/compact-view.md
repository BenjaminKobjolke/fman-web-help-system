---
title: Compact view
summary: Show file names in several columns without column headers, like Total Commander's Brief view.
order: 660
source: docs/views/COMPACT_VIEW.md
---

Compact view shows only file names in several columns, like Total Commander's Brief view. Each pane has its own mode, and fman++ restores it when it reopens the session.

To switch the active pane, run **Toggle compact view** in the command palette. There is no default keyboard shortcut, but you can assign one in the key bindings settings.

Names fill each column from top to bottom, then continue in the next column. Columns use the width of the longest name, up to the pane width. Longer names end in an ellipsis; hover over one to see its full name. File icons remain visible.

Use the usual sort commands and key bindings. Compact view has no column header, so choose a sort in the command palette or use its key binding. Selection, rename, copy, move, open, drag-and-drop and context-menu commands remain available. Left and Right follow their configured key bindings; without a binding, the list moves to the previous or next column.
