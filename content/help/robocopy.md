---
title: Robocopy
summary: Copy or move files with Windows' own robocopy, with tunable threads, retries and logs.
order: 350
source: docs/ROBOCOPY.md
---

Copy or move selected files and folders with Windows' native `robocopy.exe`.
Run **Copy with robocopy** or **Move with robocopy** from the command palette
(`Ctrl+Shift+P`). Both are Windows-only and hidden on macOS and Linux. Normal
`F5` / `F6` behaviour is unchanged, and there are no default key bindings or
context-menu entries.

## Usage

1. Select files or folders in the active pane. With nothing selected, the entry
   under the cursor is used.
2. Run **Copy with robocopy** or **Move with robocopy**.
3. Accept the opposite pane as the destination, or type an absolute drive or UNC
   folder path.

The destination receives each selected item by name, including empty
subfolders. Unselected siblings are left alone.

Only one operation runs at a time, including destination selection and cleanup.
Starting another reports **“A Robocopy operation is already active.”**

**Cancel** stops the running `robocopy.exe`; whatever was already copied or
moved stays as it is. If fman itself crashes during a transfer, `robocopy.exe`
keeps running.

## What to expect

- **Robocopy's own rules apply.** Exit codes 0–7 mean no reported copy failure;
  8 or higher is a failure. That does not prove every selected item was copied
  or moved.
- **No safety net.** There is no staging, rollback, undo or content
  verification. A canceled or failed operation can leave partial files at the
  destination and a partially moved selection. Move checks the selected items
  afterwards and reports what remains.
- **Data, attributes and timestamps** are copied.
- **Links are not followed.** Selected links and junctions are refused; links
  nested inside a selected folder are skipped. A junction at the destination can
  redirect writes, and overwriting a hard-linked destination file changes its
  other names too.
- **Refused paths:** relative paths, wildcards, device paths, alternate data
  streams, copying an item onto itself, and copying a folder into itself. Use
  plain absolute local-drive or UNC paths.
- **The progress display is a busy indicator**, with elapsed time and the
  selected items or current job — not byte progress. Jobs run one after another;
  robocopy's own multithreading provides the parallelism.
- **A move on the same drive can be faster with `F6`**, because it may rename
  instead of copying and deleting.

## Settings

Put overrides in `%APPDATA%\fman\Plugins\User\Settings\Robocopy.json` or
`Robocopy (Windows).json`, then run **Reload plugins**. Unknown keys, wrong types
and out-of-range values are rejected.

| Setting | Default | Effect |
|---|---|---|
| `threads` | `8` | 1–32. Robocopy `/MT` thread count. |
| `retries` | `1` | 0–10. Retries per failed operation. |
| `retry_wait_seconds` | `1` | 0–30. Wait between retries. |
| `restartable` | `false` | Use `/Z` for restartable network transfers. |
| `unbuffered` | `false` | Use `/J`, mainly for large files. |
| `log_enabled` | `false` | Write a transfer log. |
| `open_log_on_finish` | `false` | Open the log when done. Needs logging on. |
| `log_retention_count` | `20` | 1–100. How many logs to keep. |

## Logs

Logs are off by default. When on, they are written under
`<DATA_DIRECTORY>\Local\Robocopy\Logs` and contain paths, file names, command
arguments, robocopy's output, exit codes and the outcome — review a log before
sharing it.
