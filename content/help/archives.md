---
title: Archives
summary: Browse and edit zip, 7z and tar files as if they were folders.
order: 150
source: docs/ARCHIVES.md
---

fman++ browses and edits `.zip`, `.7z` and `.tar` archives as if they were
folders. You can step into one, look around, and copy files in or out the same
way you would with any directory.

## What you can do

- **Step into an archive** and list its contents like a folder.
- **Extract** files by copying them out.
- **Pack** files by copying them in.
- **Rename and delete** entries inside an archive.

Long-running operations show a progress bar that fills as the work proceeds.

## Canceling

Canceling an operation stops it immediately. While updating an existing archive
— packing, deleting or renaming inside it — the work happens in a temporary file
next to the archive, which fman++ deletes after a canceled run, so a canceled
operation does not leave a stray file behind. Your original archive is left as it
was.
