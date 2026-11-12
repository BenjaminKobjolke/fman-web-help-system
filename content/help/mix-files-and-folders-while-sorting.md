---
title: Mix files and folders while sorting
summary: Sort files and folders together in each pane instead of keeping folders in a separate group.
order: 620
source: docs/functions/mixed-sorting.md
---

Run **Toggle mixed file and folder sorting** to change the current pane. The other pane keeps its own choice. Each pane's preference is saved in `Panes.json` and restored when fman++ starts.

When mixed sorting is on, files and folders are ordered together in the Name, Size, Modified, Extension, Version and Attributes columns. For Size, folders count as zero and are sorted by name alongside empty files. Plugin columns keep their usual sorting unless the plugin provides a mixed-sort value.
