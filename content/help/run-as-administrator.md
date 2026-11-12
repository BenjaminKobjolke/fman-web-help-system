---
title: Run as administrator
summary: Start a local program with administrator rights after approving the Windows prompt.
order: 640
source: docs/functions/run-as-administrator.md
---

On Windows, put the cursor on a local program, batch file, installer or another local file. Search for **Run as administrator** in the command palette or choose it from the file context menu, then approve the Windows User Account Control prompt.

When several files are selected, fman++ starts each separately, so Windows may show a prompt for each file. Folders cannot be run this way.

| Command name | Palette label | Keywords | Default key binding |
|---|---|---|---|
| `run_as_administrator` | Run as administrator | admin, elevated, elevate, uac, runas | none |

You can bind the command in `Key Bindings.json`, for example:

```json
{ "keys": ["Ctrl+Shift+Enter"], "command": "run_as_administrator" }
```

Canceling the Windows prompt leaves fman++ open without an error. Windows decides whether a file type supports this action; if it cannot run the file, fman++ shows the error from Windows.
