---
title: File type actions
summary: Choose which app or built-in command opens each kind of file.
order: 450
parent: file-viewers
source: docs/FILE_TYPE_ACTIONS.md
---

Choose what Enter and double-click do for a file type from **Open with... → Configure...**. Select **Always open .md files with...** and choose an app, **fman++ viewer**, **fman++ editor (F4)** or **Command line...**. To restore the operating system association, select **Stop always opening .md files with ...** for that suffix.

File actions are stored in the user data file `File Type Actions.json`. You can edit it directly:

```json
{
  ".md": {"command_line": ["C:\\Program Files\\Typora\\Typora.exe", "{file}"], "app": "Typora"},
  ".log": {"command": "view_file"},
  ".csv": {"command_line": ["C:\\Tools\\csvview.exe", "--sep", ";", "{file}"]}
}
```

`{file}` is replaced with the file path in each argument. If you leave it out, it is added at the end. The longest matching suffix wins, so `.tar.gz` can have a separate action from `.gz`. Matching ignores case. Files without an extension have no file type action.

If no action is configured, fman++ uses the system default app. If an action is invalid or cannot run, fman++ shows a status message and falls back to the system default. Run **Open with system default** to bypass a configured action once.

When you edit an app from **Open with → Configure → Edit app**, fman++ updates actions that refer to it. Removing an app from the Open with list leaves its saved actions in place. Add app and Edit app ask for arguments after the program name; Enter with only `{file}` keeps a plain app entry. `Apps.json` stores each app's command line. A standalone `{file}` expands to all selected files in one launch, while a placeholder embedded in an argument launches once per file.

On macOS, apps chosen from the Open with list use the system's `open -a` handling. A custom command line runs the selected executable path directly; selecting a `.app` bundle may require its inner executable.
