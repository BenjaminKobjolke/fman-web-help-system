---
title: Usage statistics
summary: See what usage statistics include and choose whether fman++ sends them.
order: 690
source: docs/USAGE_METRICS.md
---

Frozen releases of fman++ can send anonymous usage events to help improve the
app. Sending is off until you choose **Accept all** or **Only usage statistics**
in the consent question. Development builds never send usage statistics.

## What is sent

Each batch includes a random installation ID, the platform, the fman++ version,
and events with their occurrence time and properties. Events describe app
starts, commands, file interactions, context-menu use, tutorial progress and
shortcut handling. Properties may include command names, the shortcut pressed,
the tutorial step, how a context menu was opened, and text entered in the
shortcut dialog's feedback field.

Events have no dedicated fields for file or folder paths, names, contents or
account credentials. The feedback field is sent as text, though, so do not enter
personal or sensitive information there. The installation ID is generated on
your device; fman++ does not create an account for usage statistics, and event
batches have no dedicated name or email field.

## Choose whether to send statistics

The shared consent question offers **Accept all**, **Only error reports**,
**Only usage statistics** and **No thanks**. Closing it turns both error
reporting and usage statistics off. See [Error reports](app-error-reporting.md)
for more about the other choice.

Run **Toggle usage statistics** from the command palette to change the setting
while fman++ is open. Restart fman++ for the change to take effect.

If delivery fails, including while you are offline, that batch is dropped. It is
not retried or saved for later, and a delivery failure does not prevent you from
closing or using the app.
