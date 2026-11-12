---
title: Scripting API
summary: Let local scripts read pane state and run commands through fman++ over a protected local API.
order: 540
source: docs/SCRIPTING_API.md
---

The scripting API lets local scripts read fman++ pane state and run commands. It is disabled by default. Run **Toggle scripting API** from the command palette to start or stop it; the command takes effect immediately. When it starts, the status bar shows the URL and token file path. If enabled in settings, it starts after plugins load.

## Connect safely

By default, the API listens only on this computer at `http://127.0.0.1:47447`. Every request needs a bearer token. fman++ creates the token in `%APPDATA%/fman/Local/ScriptingApiToken.txt` the first time the API starts and reuses it later. Treat this file as a secret: the token grants access to commands allowed by your settings.

HTTP traffic is unencrypted. Keep the default local-only connection unless you need access from your network. If you enable network access with `allowed_hosts`, only allow trusted addresses and keep the token private. Anyone who can connect from an allowed address and obtain the token can use the commands permitted by your access settings. A command allowlist limits command names, but plugins can change a command after that check; use `command_access: "all"` only when you trust every program that can reach the server and has the token.

Requests use UTF-8 JSON. Browser requests that include an `Origin` header are rejected, request bodies are limited to 1 MB, and errors return a JSON object with an `error` message.

## Read state and run commands

| Method and path | Purpose |
|---|---|
| `GET /v1/state` | Read active pane and pane paths, cursor and selection. |
| `GET /v1/commands` | List pane and application commands and the commands allowed by settings. |
| `POST /v1/command` | Run an allowed command, with optional arguments and pane. |

Panes are listed active-first, so the active pane has index `0`. Its path, cursor and selected items are application URLs; the cursor is `null` when no file is under it. A command defaults to the active pane and empty arguments. Application commands ignore the pane value. The response arrives when the command returns; a copy or move may continue in the background.

For example, a command request can contain `{"name":"open_directory","args":{"url":"file://C:/Windows"}}`. Each request must include the bearer token in its `Authorization` header. Use the token from the file path shown in the status bar; do not share it.

## Optional desktop automation

Automation routes are available only when `scripting_api.automation` is `true`; that setting defaults to `false`. The routes share the bearer token and network restrictions. They can post key and mouse events inside fman++ but cannot control native operating-system dialogs or global hotkeys. They can read a screenshot, the active dialog and files in a pane. Drags that start on a file-list viewport are rejected to prevent starting a file drag-and-drop operation. Automation routes return 403 while automation is disabled.

## Settings

Settings are in `%APPDATA%/fman/Local/Settings.json`:

```json
"scripting_api": {
  "enabled": false,
  "automation": false,
  "port": 47447,
  "allowed_hosts": [],
  "command_access": "allowlist",
  "allowed_commands": ["…defaults…"]
}
```

- `enabled` defaults to `false`. The palette command changes it immediately. Other setting changes apply the next time the API starts.
- `port` defaults to `47447` and must be between 1 and 65535.
- `allowed_hosts` defaults to an empty list. With no entries the server binds to `127.0.0.1`; entries allow matching IP addresses or CIDR networks.
- `command_access` defaults to `"allowlist"`. Set it to `"all"` to allow every command.
- If you provide `allowed_commands`, it replaces the defaults. The default list includes navigation, selection, pane switching, reload, sorting and path-copy commands. It excludes destructive and externally launching commands, and excludes **Toggle scripting API** itself.
