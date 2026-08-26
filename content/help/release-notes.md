---
title: Release notes
summary: Browse what changed in each fman release without leaving the app.
order: 140
source: docs/views/RELEASE_NOTES.md
---

You can read fman's release notes inside the app, newest release first, without
opening a browser.

## Usage

1. Open the command palette and run **Release Notes**.
2. The list shows every bundled release, newest first, labeled
   `<version>_<build>` with its date as a hint. Type to fuzzy-filter, like any
   palette list.
3. Pick one. Its title, `<version>_<build> — date`, and notes fill the active
   pane, the same way **View file** does. **Escape**, **Enter** or **Backspace**
   closes it and returns to the file list.

## Command

| Command name | Palette label | Also found by | Default key |
|---|---|---|---|
| `show_release_notes` | Release Notes | show release notes, changelog, what's new | none (palette only) |

Bind it like any other command:

```json
{ "keys": ["Ctrl+Alt+R"], "command": "show_release_notes" }
```

## Notes

- **The entry is hidden when there is nothing to show.** If this build ships no
  release notes, the command does not appear in the palette at all — no dead
  entry, no empty picker.
- **Newest first**, sorted numerically by version and then build number, so
  `1.7.5_10` sorts above `1.7.5_9`.
- **In your language where available.** The notes are shown in the system's
  detected language when that release has a translation for it, and in English
  otherwise. The date hint in the picker is always the English one.
- **Always read-only.** There is no file on disk behind this text, so the viewer
  never offers *Edit file* here.
- Notes are plain text — the title, then `<version>_<build> — date`, then one
  bullet per entry.
