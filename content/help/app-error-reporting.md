---
title: Error reports
summary: Choose whether fman++ sends unexpected errors, and see what a report contains.
order: 680
source: docs/APP_ERROR_REPORTING.md
---

Released builds of fman++ can send unexpected errors to help the maintainers
find and fix crashes. Automatic reporting starts only after you consent.
Debug and development builds never send reports.

## What a report contains

A report includes the error kind, platform, fman++ version, exception type,
message and stack trace. The operating system version and third-party plugin
folder name are included when available.

Before delivery, fman++ removes email addresses, bearer tokens, JWT-shaped
values, recognized token formats, and values following names such as
`password`, `token`, `secret` and `key`. Messages are limited to 2,000
characters and stack traces to 32,000 characters. The service validates,
redacts and limits reports again. Reports do not include request headers,
query strings, request bodies or an IP address stored with the report.

## Choose whether to send reports

The first time you start fman++, it shows the tutorial instead of the consent
question. On a later start, the question appears if either your error-reporting
or usage-statistics choice has not been saved. Choose one of these options:

- **Accept all** to enable both error reports and usage statistics.
- **Only error reports** to enable error reports and leave usage statistics off.
- **Only usage statistics** to enable usage statistics and leave error reports off.
- **No thanks** to turn both off.

Closing the question also turns both off. You can change the error-reporting
setting at any time by running **Toggle error reports** from the command
palette. When automatic reporting is off, an error dialog offers **Send
report** for that individual error. Sending one report does not turn on
automatic reporting.

Offline reports and failed deliveries are dropped. They are not saved for later.
See [Usage metrics](usage-metrics.md) for details about the separate usage
statistics choice.
