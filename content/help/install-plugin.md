---
title: Install plugin
summary: Find fman++ plugins on GitHub and install one from the palette, without a restart.
order: 250
parent: plugins
source: docs/INSTALL_PLUGINS.md
---

**Install plugin** finds third-party plugins on GitHub, GitLab, Forgejo and in
the fman++ catalog, downloads one, unpacks it into your data directory and loads
it without a restart.

## Running it

Press `Ctrl+Shift+P` and run **Install plugin**. There is no default key
binding, and the command works regardless of which pane has focus.

It also takes an optional `github_repo` parameter of the form `owner/name`.
Passing it skips the picker and installs that repo directly — this is how fman++'s
own "you pressed a key that does nothing" dialog offers you
`mherrmann/ArrowNavigation` and `mherrmann/SwitchPanesWithArrowKeys`.

## What happens

1. **Discover** — fman++ searches GitHub for non-archived repositories tagged
   with both `fman` and `plugin`, GitLab and Forgejo hosts, and the fman++ catalog.
   Results are cached for the rest of the fman++ session.
2. **Pick** — results from the different sources appear together in a
   quicksearch picker. GitHub results show a description and star count;
   GitLab and Forgejo results show their host and star count; catalog results
   show their description and source. Plugins already installed are filtered
   out.
3. **Review** — when you pick a repository, fman++ shows its optional
   `description.md` in a review screen. If the file is missing or unavailable,
   the screen says no description is available. Back returns to the picker
   with your search text; Install continues.
4. **Resolve a version** — fman++ asks for the repo's latest release tag. If the
   repo has never published a release, it falls back to the newest commit on the
   default branch. So an unreleased plugin still installs; it is just pinned to
   a commit.
5. **Download and unpack** into `%APPDATA%\fman\Plugins\Third-party\<repo name>`.
   Catalog ZIP files are checked against their SHA-256 value before installing.
6. **Load** it in place, at its correct position in the load order, and show
   *Plugin 'X' was successfully installed.*

The steps that block show a status message while they run — *Fetching available
plugins…*, *Downloading X…*.

## The order of the list

The picker lists **most recently pushed first**, with the most-starred first
among equally recent ones. Stars break ties only: a 900-star plugin last touched
in 2019 still ranks below a one-star plugin pushed this morning.

That is deliberately not GitHub's own relevance order — for a query that is
nothing but two topics, relevance is near-uniform and the resulting order is
effectively arbitrary. It is also not GitHub's "recently updated", which any
metadata change bumps: renaming a repo or editing its description would float an
abandoned plugin to the top.

A repo that has never been pushed to sorts to the bottom, where the star count
decides the order.

## Rate limits and privacy

- fman++ talks to the public GitHub API **anonymously** — no token, no sign-in.
  Anonymous calls are limited to **60 requests an hour** per IP, and search to
  **10 a minute**. A normal install is three or four requests, so on a shared or
  NAT'd address you may still hit the limit.
- fman++ sends no identifying data with these requests. GitHub sees your IP and
  the default user agent, and nothing in the URL but the query.
- **Nothing is contacted until you run the command.** fman++ does not poll GitHub
  in the background, and there is no update check for installed plugins.

## Updating a plugin

There is no **Update plugin** command. Updating means **Remove plugin**, then
**Install plugin** again.

After an install, fman++ records the repo and the installed version in the
plugin's `Plugin.json`:

```json
{ "url": "https://api.github.com/repos/mherrmann/ArrowNavigation", "ref": "v1.0.2" }
```

`ref` is a release tag, or a commit SHA when the plugin had no release.
**List plugins** reads it back and shows it beside each third-party plugin,
abbreviating a SHA to its first 8 characters.

## When it goes wrong

| Symptom | Cause |
|---|---|
| *Could not fetch available plugins: `<reason>`.* | Network, DNS or TLS failure during the search. |
| Nothing in the picker | No eligible repo carries both the `fman` and `plugin` topics, or you already have all of them. |
| *Plugin X seems to already be installed.* | The destination directory exists. Remove it first — nothing is ever overwritten. |
| HTTP 403 | GitHub rate limit. Wait, or install by `owner/name` to spend fewer requests. |

If loading fails after the download, no success alert appears — but the files
are already on disk, so remove the directory, or use **Remove plugin**, before
retrying.

When signed in to a fman++ account, successful installs update that account's
shared plugin list, and **Remove plugin** removes the entry. Local plugins
without saved source details are not uploaded. See
[Syncing plugins between computers](plugin-sync.md) for account sync details.

## Installing without GitHub

Nothing about fman++'s plugin loader requires GitHub. Copy a plugin directory into
`%APPDATA%\fman\Plugins\User\` and run **Reload plugins**.

On macOS and Linux that path is `~/Library/Application Support/fman/Plugins/User`
and `~/.config/fman/Plugins/User`; setting `FMAN_DATA_DIRECTORY` moves it
anywhere.
