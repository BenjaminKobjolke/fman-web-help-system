---
title: Pane sizes
summary: Set equal or custom widths for the file panes and adjust the active pane in small steps.
order: 580
source: docs/functions/pane-sizes.md
---

Run **Set pane sizes** from the command palette. Choose **Equal** or set the active pane to 25%, 33%, 67% or 75%; the remaining width is shared evenly by the other panes. You can also run **Widen active pane** or **Narrow active pane** to change the active pane by five percentage points. Each command accepts a `step` argument for a different amount, and these commands do not make a pane narrower than 10%.

You can bind the commands to keys in `Key Bindings.json`. For example:

```json
{ "keys": ["Ctrl+Alt+7"], "command": "set_pane_sizes", "args": {"sizes": [75, 25]} }
{ "keys": ["Ctrl+Alt+Right"], "command": "widen_active_pane", "args": {"step": 10} }
```

`set_pane_sizes` without a `sizes` argument opens the preset picker. A supplied list sets relative widths directly and must contain one positive number per pane. The commands are hidden when there is only one pane or any pane is hidden. Pane widths are saved with the window state and restored at startup.
