# How to add content

Everything this site serves is generated once and committed. Nothing reads the fman
source, and nothing parses markdown, at request time. So every content change is the
same two steps: **edit a source file, then run the generator.**

```
content/help/*.md ─┐
                   ├─ tools\generate.bat ─→ public/data/*.json ─→ the site
fman source ───────┘
```

| You want to change | Edit | Then |
|---|---|---|
| A help article | `content/help/<slug>.md` | `tools\generate.bat` |
| A keyboard shortcut | the **fman repo** — see below | `tools\generate.bat` |
| Site wording (nav, buttons, labels) | `lang/en.json` + `lang/de.json` | nothing, it is read per request |

Never hand-edit anything under `public/data/`. It is build output and the next
generator run overwrites it.

## Adding a help article

One file per article. The filename is the URL slug: `content/help/window-title.md`
is served at `/window-title`.

```markdown
---
title: Window title
summary: The window title shows both panes' paths, kept in sync as you navigate.
order: 30
source: docs/WINDOW_TITLE.md
---

The fman window title shows the current path of both panes...

## Format

...
```

### Front matter

Flat `key: value` lines between two `---` markers. Not YAML — no nesting, no lists,
no multi-line values. A colon inside the value is fine; only the first one splits.

| Key | Required | What it does |
|---|---|---|
| `title` | yes | Page heading, browser title, and the start-page card title |
| `summary` | yes | One sentence. Shown on the start-page card and in search results |
| `order` | no | Sort position among the start-page cards. Steps of 10 |
| `source` | in practice | The fman doc this was derived from, repo-relative |
| `parent` | no | Slug of the article this is a subpage of |

`source` is what lets a later update find the existing article instead of creating a
second one for the same feature. Keep it even when you write an article by hand.

### Subpages

An article with `parent: <slug>` becomes a subpage: it drops off the start page and
is reached through its parent, which lists it under *In this section*. The subpage
gets a breadcrumb link back. Both pages stay in the search index and keep their own
URL — nothing is nested in the path.

```markdown
---
title: Text viewer
summary: Read and quickly edit a text file inside the pane.
order: 50
parent: file-viewers
source: docs/views/TEXT_VIEWER.md
---
```

Subpages are **one level deep**. The generator throws if the parent slug does not
exist, or if it is itself a subpage. `order` still sorts them — within their parent's
list instead of on the start page.

### Body

- Starts at `##`. The `#` H1 belongs to the source doc — the site renders `title` as
  the heading, so a second one in the body just duplicates it.
- `h2` and `h3` get stable ids automatically and become the "On this page" list.
  That list only appears when an article has more than one heading.
- GitHub-flavoured tables work. Fenced code blocks work.
- Raw HTML is escaped, not rendered. Write markdown.

### Where it shows up

Nothing to register. The start page lists whatever is in `help.json`, and the search
index is rebuilt from it, so adding the file and running the generator is the whole
job.

## Turning an fman doc into an article

The docs in the fman repo are written maintainer-first. Most of them end in an
`## Implementation` section listing `src/` paths, class names and test files, and
several are framed as a changelog ("used to freeze", "now does X"). None of that can
be published as-is — a reader of this site cannot open those files.

An article is a **rewrite**, not a copy:

| Source section | Do |
|---|---|
| Intro, what the feature does | Keep, lead with it |
| Key bindings, command-palette entries | Keep **verbatim** — they must match what the palette shows, or search fails the user |
| Settings, their JSON snippet, the default | Keep. Usually the most useful part of the page |
| Observable behaviour, messages the user sees | Keep |
| `## Why it was slow`, benchmarks, rationale | Drop |
| `## Implementation`, `src/` paths, test lists | Drop |
| `## Known remaining slow paths` | Drop, unless it changes what a user should do |
| Links to other `docs/*.md` | Drop the link, keep the sentence — those files are not published |

Rewrite postmortem framing into present tense: not "opening a UNC path used to make
the window unresponsive", but "opening a UNC path keeps fman responsive".

Never invent. Every setting, key binding, command name and default in an article
must appear in the source doc. If the doc does not state a default, do not guess one.

### The skill does this for you

`.claude/skills/fman-help-page/SKILL.md` encodes all of the above. Point it at a doc
under the fman repo's `docs/` — "make a help page out of docs/THEMES.md" — and it
derives the slug, finds an existing article by its `source:` line to update in
place, rewrites the body, and runs the generator and the tests.

It derives the slug from the doc's **H1**, kebab-cased, not from the filename: the
filenames are inconsistent and one is misspelled (`COMMAND_PALLETTE.md` holds
`# Command palette` → `command-palette`).

## Changing a keyboard shortcut

Shortcuts are **not** written here. They are parsed out of the fman source on every
generator run:

- `Plugins/Core/Key Bindings*.json` — the bindings themselves, per platform
- `Plugins/Core/core/commands/*.py` — the `aliases` tuples, which become the
  human-readable action names
- `docs/KEYBINDINGS.md` — the "Bindable viewer commands" table

So: change the binding in the fman repo, then run `tools\generate.bat` here and
commit the updated `public/data/keybindings.json`. Editing that JSON directly is
pointless — the next run reverts it.

`config/app.php` points at your fman checkout via `fman_core_dir` and
`fman_keybindings_doc`. It is gitignored, so each machine sets its own paths.

## Site wording and translations

UI strings — nav, buttons, the search placeholder, the 404 page — go through
`lang/en.json` and `lang/de.json`, keyed by the constants in
`src/Config/TranslationKeys.php`. Add the constant, then the value in **both**
language files. These are read per request; no generator run needed.

Article bodies are English only. There is one markdown file per article, and no
per-language content tier.

## After editing

```bash
tools\generate.bat      # rebuilds keybindings.json, help.json and search.json
tools\run_tests.bat     # the generator fails loudly on a bad slug or missing key
```

Then commit the source file **and** the regenerated files under `public/data/`. The
deployed site serves only the JSON.

Deploying is separate and manual: `deploy\update_and_deploy.bat` regenerates and
then FTP-mirrors. Do not run it as part of a content edit unless you mean to publish.

## Gotchas

- **A duplicate slug fails the build**, it does not silently pick a winner. If you
  rename an article, delete the old file in the same change.
- **`keybindings`, `assets`, `data` and `index` are reserved slugs.** The router
  claims them before it looks up an article; the generator throws if you use one.
- **Filenames must be lowercase kebab-case.** `Window_Title.md` is rejected.
- **The front matter block must open on line 1** with `---`, no blank line above it.
- Pretty URLs need mod_rewrite. Serve through Apache/wamp/Laragon so the root
  `.htaccess` rewrites into `public/`; PHP's built-in server only gives you `/`.
