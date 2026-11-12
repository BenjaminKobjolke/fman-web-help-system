---
title: Archives
summary: Browse and edit zip, 7z and tar files as if they were folders.
order: 150
source: docs/ARCHIVES.md
---

fman++ browses and edits `.zip`, `.7z` and `.tar` archives as if they were
folders. `.rar` and compressed TAR archives open as read-only folders, so you
can browse and copy files out but cannot change their contents. The supported
compressed TAR extensions include `.tar.xz`/`.txz`, `.tar.gz`/`.tgz`,
`.tar.bz2`/`.tbz2`/`.tbz` and `.tar.zst`/`.tzst`.

## What you can do

- **Step into an archive** and list its contents like a folder.
- **Extract** files by copying them out.
- **Pack** files by copying them in.
- **Rename and delete** entries inside writable archives.
- **Copy across archive boundaries.** Moving an item between an archive and a
  regular folder, or between two archives, copies it first and removes the
  source only after the destination appears. A failed or canceled copy leaves
  the source in place. Moving within one archive remains a rename.

Long-running operations show a progress bar that fills as the work proceeds.

## Canceling

Canceling an operation stops it immediately. While updating an existing archive
— packing, deleting or renaming inside it — the work happens in a temporary file
next to the archive, which fman++ deletes after a canceled run, so a canceled
operation does not leave a stray file behind. Your original archive is left as it
was.

## Password-protected archives

Copying a file out of an encrypted zip or 7z archive prompts for its password
when needed. A successful password stays in memory until fman++ closes; it is
not written to disk. A wrong password clears the remembered value and lets you
try again. **Pack to archive with password** asks you to enter the password
twice. Zip uses AES-256, which Windows Explorer's built-in zip support cannot
open; use 7-Zip File Manager or WinRAR. Zip passwords must use ASCII
characters; 7z passwords can include non-ASCII characters. Archive filenames
remain visible, and fman++ cannot browse 7z archives with encrypted filenames
or delete and rename entries inside an encrypted solid 7z archive.

Compressed TAR archives are read-only. Browsing them uses a temporary
uncompressed copy, which can require disk space about as large as the
uncompressed TAR while the archive is open. Zstandard TAR browsing requires a
7-Zip build with zstd support; the bundled build supports it, while Arch's
system 7za may not.
