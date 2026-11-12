---
title: Toggle hidden files
summary: Show dotfiles and Windows hidden files, or change their faded appearance.
order: 610
source: docs/functions/hidden-files.md
---

Use **Toggle hidden files** (**Ctrl+.**) to show or hide dotfiles and files that Windows marks as hidden. Shown hidden entries have gray icons and paler names; hidden folders stay brighter than hidden files. Selection keeps its usual color, and you can still select, rename and drag these entries.

## Change the faded look

Set `"dim_hidden_files": false` in `Core Settings.json` to show hidden entries with their normal text and icons. The setting defaults to `true`. It takes effect the next time you toggle hidden files, reload Core or restart fman++.

## Set or remove the Windows hidden attribute

On Windows, use **Set hidden attribute** or **Remove hidden attribute** in the command palette. The command acts on selected files or, if none are selected, the file under the cursor. It only changes the Windows hidden attribute; it does not move, rename or delete files. Refresh open panes to see the change. An alert names files fman++ could not change, such as files in protected folders where it cannot request administrator rights.
