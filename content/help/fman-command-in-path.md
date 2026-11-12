---
title: fman++ command in PATH
summary: Run fman++ from a Windows terminal by adding its folder to your user PATH.
order: 460
source: docs/FMAN_IN_PATH.md
---

On Windows, run **Toggle command in PATH** in the command palette to add or remove the current app's `bin` folder from your user PATH. After adding it, open a new terminal before typing `fman`.

The Windows installer adds `fman` to PATH automatically. If you remove it with the command, later installer updates keep it removed. Uninstalling fman++ removes its PATH entry.

Portable copies can also add their folder to PATH. Turn the command off before moving or deleting a portable folder because the PATH entry points to that location. This command is available only on Windows; Linux packages already install `/usr/bin/fman`.
