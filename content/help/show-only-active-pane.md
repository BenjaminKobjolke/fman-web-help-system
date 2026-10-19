---
title: Show only the active pane
summary: Collapse the dual-pane layout to one full-width pane, and back again.
order: 120
source: docs/functions/show-only-active-pane.md
---

You can collapse fman++'s dual-pane layout down to just the active pane, so it
fills the whole window.

## Usage

1. Open the command palette.
2. Run **Show only active pane**. The inactive pane is hidden, the active pane
   fills the window, and focus stays where it was.
3. Open the palette again — the entry now reads **Show all panes**. Run it to
   get the normal split back.

Only one of the two entries is offered at a time, depending on what is currently
visible.

## Commands

| Command name | Palette label | Shown when |
|---|---|---|
| `show_only_active_pane` | Show only active pane | two or more panes exist, all visible |
| `show_all_panes` | Show all panes | at least one pane is hidden |

Neither has a default key binding. Bind them like any other command:

```json
{ "keys": ["Ctrl+Alt+O"], "command": "show_only_active_pane" },
{ "keys": ["Ctrl+Alt+O"], "command": "show_all_panes" }
```

## Notes

- The state is not remembered: fman++ always starts with all panes visible.
- With only a single pane open, **Show only active pane** does not appear —
  there is nothing to collapse.
