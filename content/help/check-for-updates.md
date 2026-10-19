---
title: Check for updates
summary: fman++ tells you when a new release is out, and you can check by hand at any time.
order: 360
source: docs/functions/check-for-updates.md
---

fman++ checks for a newer published release five minutes after its window
appears, then every 24 hours while it stays open. When there is one, a dialog
names both versions and offers to open the download page. Each release is
announced only once.

The automatic check stays quiet when you are up to date or the check fails, and
does not run in demo mode.

## Checking by hand

Run **Check for updates** from the command palette (`Ctrl+Shift+P`). It always
reports the result — up to date, failed, or a new release — and offers the
download page even for a release it has announced before.

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `check_for_updates` | Check for updates | update, upgrade, new version, latest release, github | none |

A stalled connection does not freeze fman++; the window stays responsive.
