---
title: Clickable path
summary: Navigate to a parent folder from the location bar with one click.
order: 390
source: docs/CLICKABLE_PATH.md
---

Click a parent folder in the location bar to open it. The cursor selects the folder you came from. Hover over a parent name to preview the path prefix that will open.

Click the current folder name or the empty space after the path to open Go To, as usual.

Clickable paths are on by default. Run **Toggle clickable path** in the command palette to turn them off or on. The choice is saved in `%APPDATA%/fman/Local/Settings.json` under `clickable_path`.

Long paths shorten the middle folders to `…`. Click the ellipsis to choose a hidden folder; its tooltip shows the full path. Go To shortens long paths the same way, while keeping folders that contain letters matching your search visible.
