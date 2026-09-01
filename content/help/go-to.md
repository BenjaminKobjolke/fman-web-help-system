---
title: Go to
summary: Jump a pane anywhere by typing a few letters, plus named commands for the folders that are awkward to type.
order: 280
source: docs/functions/go-to.md
---

**GoTo** jumps a pane to another directory without walking the tree. It is a
fuzzy-search prompt on `Ctrl+P` (`Cmd+P` on macOS), and a set of palette
commands cover the places that are awkward to type — your home directory,
Desktop, AppData, `%TEMP%`.

## Usage

1. Press `Ctrl+P` (`Cmd+P` on macOS).
2. Start typing. The list filters as you go: directories whose *path* starts
   with what you typed rank first, then ones whose *name* does, then substring
   matches, then loose character matches. Type `home` for your home directory,
   `desktop` for the Desktop.
3. Press `Tab` to complete the highlighted entry and keep typing below it.
4. Press `Enter`, or click, to open it in the active pane.

## Where the suggestions come from

Suggestions are the directories you have already visited, ranked by how often —
fman counts every path change in `Visited Paths.json`. Your home directory,
Desktop, Documents and Downloads are always in the list too, even if you have
never opened them in fman.

From three characters on, fman also asks the OS search index — Windows Search,
Spotlight on macOS — for up to ten more folders whose name starts with your
query, so a directory you have never opened in fman can still show up.

If a directory you pick has since been deleted, fman drops it from the history
instead of showing an error, so it stops being suggested.

## Typing paths

- A typed `~` is expanded: `~` alone goes home, `~/Downloads` one below it.
  Suggestions are shown tilde-shortened too, unless you are already typing an
  absolute path inside your home directory.
- On Windows a path starting with a single backslash resolves against the
  current drive, and a bare drive letter (`E:`) gets its backslash added.

## Jumping to a well-known folder

One command per destination, all palette-only and none bound to a key:

| Command name | Palette label | Goes to | Platform |
|---|---|---|---|
| `go_home` | Go home | `~` | all |
| `go_to_desktop` | Go to desktop | your Desktop | all |
| `go_to_documents` | Go to documents | your Documents | all |
| `go_to_downloads` | Go to downloads | your Downloads | all |
| `go_to_app_data` | Go to AppData | `%APPDATA%`; `~/Library/Application Support` on macOS; `$XDG_CONFIG_HOME` or `~/.config` on Linux | all |
| `go_to_temp` | Go to temp | `%TEMP%`, `/tmp` | all |
| `go_to_local_app_data` | Go to local AppData | `%LOCALAPPDATA%` | Windows |
| `go_to_program_data` | Go to ProgramData | `%PROGRAMDATA%` | Windows |
| `go_to_program_files` | Go to Program Files | `%ProgramW6432%` | Windows |
| `go_to_program_files_x86` | Go to Program Files (x86) | `%ProgramFiles%` | Windows |

These exist because GoTo cannot suggest them: it seeds its suggestions from the
*non-hidden* subdirectories of your home directory, so `AppData` and
`ProgramData` never appear, and `%TEMP%` lives outside your home directory
entirely. Typing the path still works — the commands just give it a name.

*Home*, *user profile*, *appdata*, *roaming*, *%temp%*, *installed programs* and
friends find these rows too. They are hidden keywords, so the row still reads
**Go home**.

## The rest of the navigation family

| Command name | Palette label | Default key |
|---|---|---|
| `go_to` | Go to | `Ctrl+P` (`Cmd+P` on macOS) |
| `go_up` | Go up | `Backspace`, `Ctrl+Up` (`Cmd+Up` on macOS) |
| `go_back` | Go back | `Alt+Left` (`Cmd+Left` on macOS) |
| `go_forward` | Go forward | `Alt+Right` (`Cmd+Right` on macOS) |
| `go_to_root_of_current_drive` | Go to root of current drive | `Ctrl+\` (Windows only) |

Bind any of them like any other command:

```json
{ "keys": ["Alt+Home"], "command": "go_home" }
```

## Notes

- **The everyday four are offered, but not promoted.** Home, Desktop, Documents
  and Downloads join the suggestion pool with a visit count of zero, so typing
  their name finds them instantly — but on an empty query they sort below the
  directories you actually visit. They are a safety net, not a shortcut bar.
- **They match on their name, not just their path.** Each shows a name on the
  right of the row — *Home*, *Desktop*, *Documents*, *Downloads* — and typing it
  finds the row even when the path shares no letters with it. That is what makes
  `home` reach `~`. Nothing in the path is underlined, because the match was not
  there.
- **They never enter your visit history**, so they gain no counts and are never
  pruned.
- **Desktop, Documents and Downloads are read from the registry**, not assumed
  to sit under `~`. OneDrive's "back up your folders" moves all three into
  `~/OneDrive`. Off Windows, and if the value is missing, fman falls back to
  `~/Desktop` and friends.
- **A destination that does not exist is hidden, not broken.** A machine without
  a Downloads folder simply has no **Go to downloads** row, so the palette never
  offers a jump that would fail.
- Everything opens in the **active pane**, and an unreadable directory is
  reported rather than raising an error.
