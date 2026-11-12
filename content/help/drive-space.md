---
title: Drive space
summary: See total capacity and free space for local Windows drives and control how often the list refreshes.
order: 550
source: docs/functions/DRIVE_SPACE.md
---

The Windows drives list (`drives://`) shows each local drive's total capacity and free space in the **Size** and **Free** columns. Network drives and drives whose capacity cannot be read are left blank. Values use binary units, including TB.

The list refreshes every 10 seconds while it is open. To change the interval, add this key to `Core Settings.json`:

```json
{
  "drive_space_refresh_seconds": 30
}
```

The setting takes effect next time you navigate. Set it to `0` to turn off automatic refresh; press F5 to update the list by hand.
