---
title: Right-button selection
summary: Mark files with the right mouse button while keeping a way to open their context menu.
order: 420
source: docs/RIGHT_BUTTON_SELECTION.md
---

Right-button selection is off by default, so right-click opens the context menu. Run **Toggle right-button selection** from the command palette to turn the mode on or off. The choice is saved in `%APPDATA%/fman/Local/Settings.json` under `right_button_selection`.

When the mode is on, right-click a file to mark or unmark it. Drag the right button over rows to apply the starting file's marked state to each row. Hold the right button on a file for one second to open the context menu for the selected files. Right-click empty space and open the menu with the keyboard as usual.

Use the left button on a file name to drag the file to another pane. Drag from the empty part of a row to select a range. Right-button dragging does not scroll the list automatically at its top or bottom edge.
