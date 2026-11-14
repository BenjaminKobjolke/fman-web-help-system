---
name: fman-help-sync
description: Catch this help site up with everything that changed in the fman source repo's docs/ since the last sync, in one run. Use when asked to "update the help from fman changes", "sync the help system", "what changed in fman docs", "the docs moved on, update the site". Diffs the fman checkout against the commit recorded in tools/fman_docs_sync.txt, then creates or updates one help article per changed doc. For a single doc pointed at by hand, use fman-help-page instead.
---

# fman help sync

The fman repo moves faster than anyone remembers to run `fman-help-page` by hand,
so the site drifts. This skill closes the gap: one run, every doc that changed.

**This skill decides *which* articles to touch. It does not restate *how* to write
one.** Read `.claude/skills/fman-help-page/SKILL.md` and follow its *Rules*,
*What to keep vs drop* and *Format* sections for every article you create or
update. There is one copy of the rewrite rules; this is not it.

## How change is detected

No copy, no mirror, no script. The fman checkout is a git repo — diff it.

```
tools\fman_docs_sync.txt      <- one line: the fman commit this site is synced to
```

```
git -C <fman> log  --oneline     <sha>..HEAD -- docs/
git -C <fman> diff --name-status <sha>..HEAD -- docs/
git -C <fman> diff               <sha>..HEAD -- docs/THEMES.md
```

Read the `log` output, not just the diff. `FEATURE (keybindings): add vim-style
cursor shortcuts` tells you what the change was *for*; a raw hunk does not, and
the article should describe the feature, not the patch.

## Resolving the fman checkout

Never hardcode the path. `config/app.php` (gitignored, machine-local) holds:

```php
'fman_keybindings_doc' => 'D:/GIT/BenjaminKobjolke/fman/docs/KEYBINDINGS.md',
```

Docs dir = that file's directory. Repo root = its parent. `fman_core_dir` points
deep into `src/main/resources/...` and is for the keybindings generator, not for
this.

## Process

1. **Resolve paths** as above. Run `git -C <fman> status -sb` and report it. Do
   not pull, stash, commit or discard anything in fman. If the checkout is behind
   `origin`, stop with `OUTCOME: blocked` and
   `BLOCKER: fman checkout is behind origin`: diffing a stale checkout silently
   under-reports.

   If the checkout is dirty, check only the inputs the generator reads from the
   working tree with
   `git -C <fman> status --porcelain -- <paths>`: `docs/KEYBINDINGS.md`,
   `<fman_core_dir>/Key Bindings*.json` and `<fman_core_dir>/core/commands/`,
   using repo-relative paths for `<fman_core_dir>`. Any matching change means
   stop with `OUTCOME: blocked` and a `BLOCKER:` naming the files: the generator
   reads these inputs from the working tree. Docs are always read from `HEAD`
   (step 4), so dirty docs cannot reach an article and are only listed under
   **fman working tree**. List all dirty files there and continue when none of
   the generator inputs match. The change set comes from commits. A plan written
   from this skill must state this narrow check, not "stop if dirty", and must
   not add a check over the change-set docs.
   An approved plan written before this rule may still say "stop if dirty" or
   check the change-set docs. Follow the narrow check above instead, and name
   the difference under deviations in the report.
2. **Read the baseline** from `tools\fman_docs_sync.txt`. If the file is missing or
   empty, or `git -C <fman> cat-file -e <sha>^{commit}` fails — history rewritten,
   repo re-cloned — you have no baseline: run **Full reconcile** below instead.
3. **Collect the change set.** `log --oneline` for intent, `diff --name-status` for
   the files. Nothing changed → say so, then **still go to step 5** — key bindings
   come from fman source outside `docs/` and may have moved anyway.

4. **Handle each entry by its status:**

   | Status | Do |
   |---|---|
   | `M` | Grep `content/help/*.md` for `source: <path>`. Found → update that file in place, keeping its filename, `order` and `parent`. Read the doc **in full from the commit** with `git -C <fman> show HEAD:<path>`, never from the working tree. The diff shows what moved, the committed doc shows what the article should say. Not found → treat as `A`. |
   | `A` | Apply the publish gate. Passes → new article per `fman-help-page` (slug from the doc's **H1** kebab-cased, not the filename; `order` = highest existing + 10). Fails → skip and list it in the report. |
   | `R` | Rewrite the `source:` line on the matching article to the new path, then handle the content as `M`. Renames are the main way `source:` goes stale — nothing validates it at build time. |
   | `D` | **Report only. Never delete an article automatically.** A deleted doc often means the doc was merged elsewhere, not that the feature is gone, and the article is hand-written work that the diff cannot reconstruct. |

   Every doc read in this run (`A`, `M`, `R`, and publish-gate decisions
   included) comes from `git -C <fman> show HEAD:<path>`, because the baseline
   written in step 6 is `HEAD` and the article must match it.

5. **Always run `tools\generate.bat`, then `tools\run_tests.bat`** — even when no
   doc changed, for the key bindings (below). A bad slug, a duplicate slug, a
   reserved slug, a missing `title`/`summary` or a dangling `parent:` fails loudly
   here.

6. **Only after both pass**, write the new baseline:
   `git -C <fman> rev-parse HEAD` → `tools\fman_docs_sync.txt`. A failed run must
   leave the old SHA in place so the next run retries the same range.
7. **Report** a table — doc → article → created / updated / skipped / orphaned —
   plus which sections you dropped from each rewrite, and whether
   `public\data\keybindings.json` changed.

## Key bindings are always regenerated

The `/keybindings` page is **not** written by hand and is **not** driven by the
`docs/` diff. `tools\generate.bat` re-parses it out of the fman checkout on every
run, from three inputs:

```
<fman_core_dir>\Key Bindings.json                 the bindings themselves
<fman_core_dir>\Key Bindings (Windows|Mac|Linux).json
<fman_core_dir>\core\commands\*.py                the aliases tuples -> display names
docs\KEYBINDINGS.md                               the "Bindable viewer commands" table
```

Two of those three live **outside `docs/`**, so a changed shortcut produces an
empty `docs/` diff. That is why step 5 runs unconditionally: run the generator
even when the change set is empty, then check whether
`public\data\keybindings.json` actually moved and say so in the report.

The scoped dirty-file check in process step 1 protects only the key binding
inputs read by the generator from uncommitted edits. The generator still runs
when the change set is empty because key bindings can change outside `docs/`.

Never hand-edit `public\data\keybindings.json`. A shortcut is changed in the
fman repo; the next generator run reverts anything typed here.

## The publish gate

New docs become articles automatically, so be deliberate about what gets published.

**Publish only what a person *using* fman can act on.** A doc qualifies if it
describes behaviour visible in the app: a command, a key binding, a setting, a
view, a message the user sees.

A doc whose whole subject is building, maintaining or releasing fman, or writing
plugins against its internals, does not. The reader of this site is running the
file manager, not developing it — publishing that content makes the whole site
look like it is addressed to someone else, and it is the same mistake as copying
an `## Implementation` section.

- **Fails the gate:** `CREATE_NEW_RELEASE.md`, `PLUGINS_API.md`, anything under
  `docs/bugs/`.
- **Never an article:** `KEYBINDINGS.md`. Key bindings are parsed out of the fman
  source by `tools\generate.bat` and served at `/keybindings`. A hand-written
  article would go stale against the generated table.
- **A doc that mixes both still gets an article**, written from the user-facing
  part only. `fman-help-page`'s *What to keep vs drop* table already says how.

An **overview doc that maps a group of features still passes** if it carries
behaviour of its own — a command, a setting, a caveat — that no other article
covers. `WINDOW_FUNCTIONS.md` is the example: it looks like an index, but it is
the only doc describing `center_window`, `minimize` and the scrim behind
dialogs. Skip such a doc only when every sentence in it is a pointer to another
article.

When a new doc is a facet of an article that already exists — one viewer of
several, one knob of a feature — give it `parent:` rather than another start-page
card. One level only.

## Full reconcile

Run when there is no usable baseline, or when asked to check the site against fman
from scratch.

1. For each `content/help/*.md`, check its `source:` path resolves under the fman
   docs dir. A miss means the doc was renamed or removed: find where it went
   (`git -C <fman> log --diff-filter=RD --name-status -- docs/`) and repoint the
   article, or report it as orphaned.
2. List every doc under the fman docs dir with no article pointing at it. Put each
   through the publish gate; create an article for those that pass.
3. Generator, tests, then seed `tools\fman_docs_sync.txt` with fman `HEAD`.

## Gotchas

- **`source:` is the only doc→article map.** No manifest, no index. It is never
  validated at build time, so a stale one fails silently and forever — which is
  exactly what happened to `WINDOW_TITLE.md`. Fixing `source:` on a rename is not
  bookkeeping, it is the whole mechanism.
- **Write the SHA last.** Before the generator and tests pass, the run is not done.
- **Commit `public/data/*.json` with the markdown.** The deployed site serves only
  the JSON; the markdown is build input.
- **`docs/HOW_TO_ADD_CONTENT.md` is the human-facing twin** of this skill and
  `fman-help-page`. A rule learned here belongs there too.
- Deploy is separate and manual: `deploy\update_and_deploy.bat`. Do not run it
  unless asked.

## Related

- `fman-help-page` — one doc, pointed at by hand. Owns the rewriting rules; this
  skill defers to them.
- `fman-keybindings` — different target entirely: the user's own bindings under
  `%APPDATA%`. Writes nothing in this repo.
