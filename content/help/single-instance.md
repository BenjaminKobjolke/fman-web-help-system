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
| `fman --single-pane` | Hides the other panes, then brings the running window forward. On a fresh start, opens with only the first pane visible. |
| `fman --new-instance "C:\some\folder"` | Always starts a separate window for this launch; later launches still go to the existing instance. |
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

The default is `true`. Create the file if it does not exist. Use
`fman --new-instance` when only one launch should open a separate window.

### Taskbar buttons and pinned folders

When `single_instance` is `false`, set `taskbar_grouping` to `false` in the
same settings file to give each fman++ window its own taskbar button. The
default is `true`; the setting is read at startup, and separate buttons do not
merge into a pinned fman++ icon. Windows also has a system-wide setting under
Settings > Personalization > Taskbar > Taskbar behaviors > “Combine taskbar
buttons and hide labels”.

On Windows, use **Pin folder to taskbar** to add the current pane's folder to
the taskbar button's **Pinned folders** list. Use **Unpin folder from taskbar**
to choose and remove a saved folder. Paths are stored in `taskbar_pinned_folders`
in `Core Settings.json`. Selecting an entry opens it in the active pane when
single-instance mode is on, or a new window when it is off. Windows limits the
number of entries according to the recent-items count in Start settings.

If the connection between instances cannot be set up for any reason, fman++ just
starts a normal instance instead — this never blocks startup.
