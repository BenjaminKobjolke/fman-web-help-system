---
title: Check for updates
summary: fman++ tells you when a new release is out, and you can check by hand at any time.
order: 360
source: docs/functions/check-for-updates.md
---

fman++ checks for a newer published release five minutes after its window
appears, then every 24 hours while it stays open. When there is one, a dialog
names both versions and offers to download and install it. Each release is
announced only once. The download has a cancelable progress dialog.

The automatic check stays quiet when you are up to date or the check fails, and
does not run in demo mode.

## Checking by hand

Run **Check for updates** from the command palette (`Ctrl+Shift+P`). It always
reports the result — up to date, failed, or a new release — and offers to
download it even for a release it has announced before. On Windows the
downloaded installer runs and fman++ quits; on macOS the downloaded DMG opens
while fman++ stays running. Linux opens the GitHub releases page. If a download
fails, fman++ shows an alert and opens that page as a fallback.

## Automatic updates on Windows

Automatic updates are off by default in installed copies. Run **Toggle
automatic updates** to enable or disable them, and **Set automatic update
interval** to choose a check interval from 1 to 720 hours (default: 24). When
an update is ready, a 30-second countdown offers **Update now** and **Cancel**.
Cancel or Esc postpones it until the next automatic check. Accepting an
automatic update closes fman++, installs without the setup wizard and restarts
the app. An all-users installation in Program Files needs one UAC confirmation
for each automatic update.

These commands also appear in portable copies, but automatic updates only work
in an installed Windows version. Manually checking from a portable copy
downloads `fmanPortable.zip`; after confirmation, fman++ replaces its program
files while keeping its data, then starts again. A manual Windows update uses
the setup wizard when automatic updates are off.

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `check_for_updates` | Check for updates | update, upgrade, new version, latest release, github | none |
| `toggle_automatic_updates` | Toggle automatic updates | auto update, automatic update, unattended update | none |
| `set_automatic_update_interval` | Set automatic update interval | update interval, update frequency, check frequency | none |

A stalled connection does not freeze fman++; the window stays responsive.
