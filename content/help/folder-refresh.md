---
title: Folder refresh
summary: Refresh open local, network and FTP folders automatically to show changes made elsewhere.
order: 560
source: docs/functions/FOLDER_REFRESH.md
---

Periodic refresh re-reads open folders so changes made by other programs appear without leaving the folder. It runs only while fman++ is the active window. Switching back to fman++ already refreshes both panes.

Use the command palette to toggle refresh separately for local, network and FTP folders:

- **Toggle local folder refresh**
- **Toggle network folder refresh**
- **Toggle FTP folder refresh**

Each category is off by default and has its own interval, initially five seconds. The toggles take effect immediately. Edit `Core Settings.json` to change these settings:

| Setting | Default | Purpose |
|---|---|---|
| `refresh_local_folders` | `false` | Enable refresh for local folders |
| `local_folder_refresh_seconds` | `5` | Local folder interval in seconds |
| `refresh_network_folders` | `false` | Enable refresh for network folders |
| `network_folder_refresh_seconds` | `5` | Network folder interval in seconds |
| `refresh_ftp_folders` | `false` | Enable refresh for FTP, FTPS and SFTP folders |
| `ftp_folder_refresh_seconds` | `5` | FTP folder interval in seconds |

Hand-edited interval changes take effect the next time you change folders. FTP providers show their usual “Loading…” and “Ready.” messages on each refresh.
