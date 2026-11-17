---
title: File mention (@)
summary: Find files and folders below the current folder by typing @.
order: 700
source: docs/functions/file-mention.md
---

## Usage

Press **`@`** in a pane to open the file mention popup. You can also type `@` at
the start of Go To to switch to it. Type part of a name to find files and
folders below the current folder.

Names that start with your search rank first, followed by matches in the path.
For several words, each word must appear in the path, in any order. For example,
`pdf ryd` finds `ryd/invoice.pdf`. With a single word, loose letter matches in
the name come last; they do not match folder names. Folders end in `/`.

Press **`Enter`** to open the selected file's folder with the file highlighted,
or to open the selected folder. Delete `@` to switch back to Go To.

The default key is `@`. On Windows, `Ctrl+Alt+@` also works; on a German
keyboard, that is `AltGr+Q`. While `@` is bound, you cannot type it into the
pane's live filter. Rebind `file_mention` if you need to use `@` in that filter.
See the [keybindings page](/keybindings) for binding details.

## Indexing

The popup builds its list while it is open, starting with nearby files. It stops
when you close it or after 200,000 entries. Linked folders appear in the list,
but the search does not enter them. The popup works in local and network folders,
not inside archives or on FTP.

## Settings

`file_mention_excluded` in `Core Settings.json` lists folder names to leave out
of the search. The default list is:

```json
"file_mention_excluded": [".obsidian", ".git", ".trash", ".stfolder", ".stversions", "node_modules"]
```

Changes apply the next time you open the popup. Use **Exclude folder from @ file
search** to add a folder name, or **Remove folder from @ file search
exclusions** to remove one.
