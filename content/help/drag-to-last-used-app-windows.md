---
title: Drag to last used app
summary: Drop the selected files into the app you used last, steering the drag with the keyboard.
order: 340
source: docs/DRAG_TO_APP.md
---

**Drag to last used app** sends the selected local files — or the file under the
cursor — into another app by drag and drop, without you having to arrange the
two windows side by side. Windows only.

## How it works

1. Run **Drag to last used app** from the command palette. fman picks the most
   recently used app window and asks you to confirm.
2. fman brings that window to the front and moves the mouse to its centre.
3. The drag starts right away, at the window's centre:
   - `W` `A` `S` `D` or the arrow keys move the cursor — hold a key to keep
     moving.
   - `Q`, `Space` or `Enter` drops the files.
   - `Esc` cancels.

You can also move the real mouse and click to drop.
