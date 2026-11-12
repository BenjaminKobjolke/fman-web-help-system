---
title: Portable fman++ for Windows
summary: Run fman++ from an extracted folder or USB drive with its profile stored beside the program.
order: 470
source: docs/PORTABLE.md
---

The portable Windows release is a zip you can extract and run without installing fman++. It keeps settings, plugins and other profile data in a `Data` folder beside `fman.exe`.

## Start a portable copy

Download `fmanPortable.zip` from the [fman++ GitHub Releases](https://github.com/BenjaminKobjolke/%66man/releases) page, extract it to a writable folder such as a USB drive, then open the extracted folder and run `fman.exe`. fman++ creates the `Data` folder when needed.

Keep the included `portable.txt` beside `fman.exe` to use the local `Data` profile. Delete the marker to use the usual Windows profile in `%APPDATA%\\fman` instead; this does not delete the existing `Data` folder. `FMAN_DATA_DIRECTORY`, when set by a launcher, takes precedence over both locations.

The portable folder must allow fman++ to create and remove files. If it is read-only or in a protected location such as `C:\\Program Files`, move or extract it somewhere writable before starting the app.

## Updates and multiple copies

Automatic updates are unavailable in portable copies. **Check for updates** downloads the new `fmanPortable.zip`; after you confirm, fman++ closes, replaces the program files, keeps the `Data` folder and files stored beside the program, and starts the updated copy. Leave the folder in place until the update finishes. Installed copies continue to use the installer update flow.

A portable copy and an installed copy can run at the same time with separate profiles. Most profile data moves with the portable folder, but some plugins store absolute paths that do not follow the folder if its drive letter changes. Update those plugin paths after moving the copy.

Portable copies can add themselves to the Windows PATH. before moving or deleting a folder that has been added.
