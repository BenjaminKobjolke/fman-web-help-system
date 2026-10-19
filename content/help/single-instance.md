---
title: Single instance
summary: Launching fman++ again reuses the window you already have — and how to turn that off.
order: 160
source: docs/SINGLE_INSTANCE.md
---

By default fman++ runs as a **single instance**. Launching `fman` again while it is
already running does not open a second window — the running instance handles the
launch instead.

## What happens on a second launch

| Command | Result |
|---|---|
| `fman "C:\some\folder"` | The running fman++ comes to the front and opens the folder in the **active pane** |
| `fman "C:\some\file.txt"` | Opens the file's parent folder in the active pane, cursor on the file |
| `fman` | The running window is simply raised and focused |
| `fman "C:\a" "C:\b"` | The first path opens in the active pane, the next in the other pane, and so on |

If no instance is running, fman++ starts normally.

## Turning it off

To allow multiple independent fman++ windows, set `single_instance` to `false` in
the app settings file:

```
<DataDirectory>/Local/Settings.json
```

```json
{
  "single_instance": false
}
```

The default is `true`. Create the file if it does not exist.

If the connection between instances cannot be set up for any reason, fman++ just
starts a normal instance instead — this never blocks startup.
