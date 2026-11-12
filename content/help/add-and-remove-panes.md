---
title: Add and remove panes
summary: Add file lists to the window or remove the active pane while keeping at least one.
order: 570
source: docs/functions/add-remove-panes.md
---

Run **Add pane** in the command palette to add a file list at the right. It opens in the active pane's folder and receives focus. Run **Remove pane** to close the active pane; focus moves to the next one, wrapping to the first when needed.

There is always at least one pane, and the pane count is restored when fman++ starts again. **Add pane** is hidden when no pane is hidden; **Remove pane** is shown only when more than one pane exists and no pane is hidden. While a pane is hidden with **Show only active pane**, both commands stay hidden until **Show all panes** restores the layout.

Neither command has a default key binding, but you can bind either in `Key Bindings.json`. With one pane, Copy and Move still work; their destination prompt starts at the current folder, which you can edit. **Open in left pane** and **Open in right pane** are hidden. **Alt+F2** shows volumes in the only pane, and **Tab** has no other visible pane to focus.
