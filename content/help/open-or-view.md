---
title: Open or view
summary: Make Enter open files in fman++'s internal viewer without losing folder navigation.
order: 130
parent: file-viewers
source: docs/functions/open-or-view.md
---

**Open or view** opens fman++'s internal viewer on the file under the cursor, the
same as **View file** — but falls back to the normal **open** action whenever the
internal viewer cannot handle the target, instead of showing an alert.

It exists for people who want the internal viewer as their default open action
without losing folder navigation, and without binaries such as `.exe` or `.zip`
being garbled by the text viewer.

## Setting it up

There is no default binding — opt in from your own
`Key Bindings (<OS>).json`. The usual setup swaps the default `Enter` and
`Shift+Enter` roles:

```json
[{ "keys": ["Enter"], "command": "open_or_view" },
 { "keys": ["Shift+Enter"], "command": "open" }]
```

With that, `Enter` opens files in the internal viewer and still navigates into
folders, and `Shift+Enter` becomes the normal open-with-the-OS action.

## What it does with each kind of target

| Under the cursor | What happens |
|---|---|
| A folder | Navigates into it, same as pressing `Enter` normally |
| An image, video or text file | Opens the internal viewer |
| A binary, archive or executable | Opens with the OS default application |
| A path that is not a local file | Opens with the OS default application |
| Nothing | Same as pressing `Enter` normally |

## Command

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `open_or_view` | Open or view | open (internal viewer for files) | none (opt-in) |
