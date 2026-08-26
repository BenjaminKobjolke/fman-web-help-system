---
title: Licensing
summary: There is nothing to buy — every installation is activated, with no key or trial.
order: 170
source: docs/PURCHASING.md
---

There is nothing to buy. **Every installation of this fork is activated.**

No license, no license key, no trial period, no registration, no feature gating,
no "unregistered" nag screen. fman starts straight into the file manager, every
time.

## Nothing is contacted

There is no license server and never was one, so nothing about licensing sends
or checks anything over the network.

## Leftover files in your data directory

Neither of these is read any more, and both are harmless:

- `<DataDirectory>/Local/User.json` — an old license key. Ignored. You can
  delete it.
- `<DataDirectory>/Local/Session.json` — may still hold a stale `is_licensed`
  entry from an older build. Ignored, and no longer written.
