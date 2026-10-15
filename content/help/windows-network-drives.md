---
title: Windows network drives
summary: Browsing UNC shares stays responsive, and how to get real file icons back.
order: 20
source: docs/WINDOWS_NETWORK_SUPPORT.md
---

Opening a UNC path — `\\server\share`, or an RDP-redirected drive such as
`\\tsclient\C\...` — keeps fman responsive, and fills the listing in far fewer
network round trips than a plain file-by-file walk would.

## What to expect on a share

- **Both panes stay responsive** while a share is open, not just the one showing
  it.
- **Hidden files behave exactly as they do locally.** On Windows, hidden-ness
  comes from the file attribute alone — there is no dot-prefix rule — so
  `.gitignore` stays visible and Git's hidden `.git` folder stays hidden.
- **A row that cannot be loaded is tried once**, not retried forever. Use
  *Reload* or *Refresh* to try it again.
- **Files on a network path get a generic icon per file extension** rather than
  the real icon read out of each file. Local paths are unaffected.

## Getting real icons back on network drives

Run **Toggle network drive icons** from the command palette — it is also listed
as *Show real icons on network drives*. It flips the setting, reloads both panes,
and persists across restarts.

The setting lives in `Core Settings.json`:

```json
{
  "network_file_icons": true
}
```

The default is `false`, meaning generic icons. Expect slower, per-file behaviour
on high-latency shares while it is turned on: real icons for files like `.exe`
and `.dll` are stored inside the file, so showing them means reading every file
over the wire.

## The Network… entry in the drive list

Everything above is about UNC paths. The *Network…* entry in the drive list is a
separate thing — it browses the list of servers and shares Windows knows about.

From the network overview, **Open native file manager** (`F10`) opens Explorer's
Network page. Inside a server, it opens `\\SERVER` in Explorer.

- **Listing the network no longer opens every server it finds.** It lists
  servers, and their shares one level down, without connecting to them. Before,
  a single unreachable machine could hold up the whole list for seconds.
- **Rows appear while the folder is still being read.** A listing that takes
  longer than half a second starts showing what it has so far instead of waiting
  for the whole enumeration. Fast local folders are unchanged — they finish
  before the first flush. So is a huge folder on a fast drive: one that turns up
  more than 1000 files in that half second keeps showing the loading message
  until it is completely listed, then appears in one go.
- **A slow pane tells you it is working.** After a short delay the pane paints a
  message in its centre with a seconds counter that keeps ticking, so a slow
  listing never looks like a frozen application.

On a *Network…* pane the message names what it is waiting for:

```
Searching network shares…

Waiting for Windows to return the network browse list (14 s).
A machine that is switched off or unreachable can hold this up.
```

Everywhere else — including all of macOS and Linux — you get the generic form:

```
Loading…

Still reading this folder (2 s).
```

The *Network…* entry is Windows-only. On macOS and Linux a mounted SMB share is
an ordinary local path (`/Volumes/...`, `/mnt/...`), so a slow one shows the
generic message.
