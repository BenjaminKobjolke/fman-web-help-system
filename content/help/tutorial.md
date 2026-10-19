---
title: The tutorial
summary: The speech bubble that teaches GoTo, the command palette and the two panes — and how to run it again.
order: 270
source: docs/TUTORIAL.md
---

The tutorial is fman++'s onboarding tour: a small speech bubble that walks you
through GoTo, the command palette and the two panes.

It starts by itself on the first run, in the left pane. Afterwards you can start
it again from the command palette (`Ctrl+Shift+P` → **Tutorial**), and on macOS
from the **Help** menu. If you abort the tour on a first run and then reach for
the mouse, fman++ offers it once more.

A second tour, **Cleanup guide**, uses the same bubble to walk you through
tidying a folder.

## Where the bubble sits

Bottom right of the window, with a small margin, above the status bar when that
is shown. The position is deliberately stable: the bubble does not follow the
cursor, the active pane, or a dialog.

It moves in exactly one case — when an open dialog would overlap it, it hops to
just **above** that dialog, still right-aligned.

## Driving it from the keyboard

The tour is meant to teach the keyboard, so it can be driven from the keyboard.

- **Enter** activates the focused button, which is painted as the default one so
  you can see where Enter would go.
- **Arrow keys** move between the buttons — Left and Up back, Right and Down
  forward, wrapping around.
- **Alt and the underlined letter** activates a button wherever focus is.
- The **last** button is the affirmative one by convention, and it is the one
  focused when a step opens.

## Where the keyboard goes

Each step decides for itself whether the bubble takes focus:

| The step asks you to… | Keyboard goes to |
|---|---|
| Press a key in the pane — "press *Tab*", "press *F5*", "type its name" | the directory pane |
| Acknowledge and move on — "Yes"/"No", "Continue", "Close" | the bubble's buttons |

While a bubble holds the keyboard, fman++'s own shortcuts do not fire and the
pane's cursor row loses its highlight. Both come back when the step closes and
focus is handed back to the pane, so the next step's instructions land where you
expect.
