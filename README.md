# Bitey IA — Enterprise WordPress Plugin

`bitey-ai` is the **WordPress plugin and enterprise web channel for Bitey IA**.

Its purpose is to integrate authorized business WordPress sites with enterprise Bitey IA services. It is a channel/integration layer, not the Bitey IA supracerebro and not a duplicate enterprise backend.

> **Boundary:** `bitey-web` is the general Bitey IA supracerebro; `bitey-ia-app` is its Android client. This repository is the enterprise WordPress channel.

## Product role

```text
Business WordPress site
        ↓
Bitey IA Enterprise WordPress Plugin
        ↓
Authorized Enterprise API
        ↓
Company context / knowledge / customer context
        ↓
Enterprise reasoning + research + workflows
        ↓
Business response
```

The plugin provides the WordPress channel and integration layer. Intelligence, private company data, memory, provider credentials, permissions and authoritative business decisions remain server-side.

## Responsibilities

- WordPress installation and lifecycle.
- Bitey IA website widget / entry point.
- Secure communication with an authorized enterprise API.
- Conversation and session transport.
- Company/site configuration through WordPress admin.
- Channel metadata and language preferences.
- Front-end assets and enterprise widget UX.
- Localization and WordPress compatibility.
- Safe handling of authorized requests and attachments.

## Must NOT live here

- AI provider API keys or private provider secrets.
- Permanent authoritative customer/company memory.
- Cross-company knowledge.
- The general Bitey IA supracerebro.
- A duplicate enterprise backend.
- BiteFixes-only business logic unless required by an explicit authorized contract.

## Ecosystem

| Repository | Product | Role |
|---|---|---|
| `bitey-web` | **Bitey IA Web** | General Bitey IA supracerebro and Cloudflare web application |
| `bitey-ia-app` | **Bitey IA App** | General Bitey IA Android application |
| `bitey-ai` | **Bitey IA Enterprise WordPress Plugin** | This enterprise WordPress channel |
| `bitefixes-backend` | **BiteFixes Backend** | Specialized BiteFixes enterprise backend/intelligence |
| `bitefixes-app` | **BiteFixes App** | BiteFixes mobile channel |
| `bitefixes-web` | **BiteFixes Web** | BiteFixes.com website/frontend |

## Enterprise tenant model

Each WordPress installation represents an authorized company/site context.

```text
WordPress site / tenant
        ↓
plugin configuration
        ↓
authorized API request
        ↓
tenant/company context
        ↓
enterprise response
```

The plugin must never allow one company's private context to leak into another company.

## Security rules

1. Keep provider credentials server-side.
2. Authenticate and authorize backend requests.
3. Validate all inbound and outbound data.
4. Never trust browser-supplied tenant identity without server validation.
5. Do not store authoritative company memory in WordPress front-end state.
6. Keep secrets out of JavaScript bundles and public configuration.
7. Add regression tests for security-sensitive changes.

## Development rules

1. Keep the plugin WordPress-native and modular.
2. Preserve backward compatibility when practical.
3. Keep API contracts explicit and versioned.
4. Do not copy the general Bitey IA intelligence engine into the plugin.
5. Test installation, activation, widget rendering and end-to-end API communication.
6. Build a validated ZIP before production installation.

## Installation

1. Build the validated plugin ZIP.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload and activate the ZIP.
4. Configure the authorized enterprise API and company/site settings.
5. Test a complete conversation from the WordPress widget to the authorized backend and back.

## Repository naming

The technical repository slug remains `bitey-ai`. The product role is **Bitey IA Enterprise WordPress Plugin**.
