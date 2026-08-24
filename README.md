# Bitey AI — Enterprise WordPress Plugin

`bitey-ai` is the **global WordPress plugin/channel for Bitey AI Enterprise**.

It is installed on business WordPress sites and provides the website-facing integration between the business site and authorized Bitey enterprise AI services.

> **Boundary:** this repository is a WordPress integration/plugin. It is not the general `bitey-web` application and it is not a second AI brain.

## Product role

```text
Business WordPress site
        ↓
Bitey AI Enterprise WordPress Plugin
        ↓
Authorized Enterprise API
        ↓
Company AI Profile / knowledge / customer context
        ↓
Reasoning + research + business workflows
        ↓
Business response
```

The plugin is the **channel and WordPress integration layer**. Intelligence, private company data, memory, provider credentials, permissions and authoritative business decisions remain server-side.

## Responsibilities

- WordPress installation and lifecycle.
- Bitey AI website widget / entry point.
- Secure communication with an authorized AI backend/API.
- Conversation and session transport.
- Company/site configuration through WordPress admin.
- Channel metadata and language preferences.
- Front-end assets and enterprise widget UX.
- Localization and WordPress compatibility.
- Safe handling of authorized requests and attachments.

## Must NOT live here

- AI provider API keys or private provider secrets.
- Permanent customer/company memory.
- Cross-company knowledge.
- The general Bitey reasoning engine.
- A duplicate enterprise backend.
- BiteFixes-only business logic unless required by an explicit authorized contract.

## Ecosystem

| Repository | Product | Role |
|---|---|---|
| `bitey-web` | **Bitey IA** | General web AI experience + intelligence foundation |
| `bitey-ai` | **Bitey AI Enterprise WordPress Plugin** | Global WordPress enterprise channel |
| `bitefixes-backend` | **BiteFixes Backend** | Specialized BiteFixes enterprise backend/brain |
| `bitefixes-app` | **BiteFixes App** | BiteFixes mobile channel |

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
response
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
4. Do not copy the general Bitey intelligence engine into the plugin.
5. Test installation, activation, widget rendering and end-to-end API communication.
6. Build a validated ZIP before production installation.

## Installation

1. Build the validated plugin ZIP.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload and activate the ZIP.
4. Configure the authorized enterprise API and company/site settings.
5. Test a complete conversation from the WordPress widget to the authorized backend and back.

## Repository naming

The technical repository slug remains `bitey-ai`. The product role is **Bitey AI Enterprise WordPress Plugin**. Do not create a duplicate repository merely to change the display/product name.
