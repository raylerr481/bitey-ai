# Bitey IA — WordPress Plugin

`bitey-ai` is the **WordPress integration and configurable Web channel for Bitey IA CRM SaaS**.

It is an integration/channel layer. The intelligence, authoritative memory, private company data, credentials and business rules remain server-side.

## Customer Web channel

The plugin provides the configurable web Bitey widget/globe. Together with WhatsApp and Telegram, it forms the three primary customer channels:

```text
WhatsApp ─┐
Telegram ─┼──> Bitey Conversation Engine ──> CRM + IA + Memory
Web globe ┘
```

The plugin does not replace the private Support Portal. For the BiteFixes pilot, the Portal remains an authenticated workspace for owner/admin/technician/worker personnel. Customers do not use that administrative login.

## SaaS tenant model

BiteFixes is the first production tenant. The plugin is being improved to work for other companies without duplicating the backend.

Tenant-facing configuration can include:

- company/display name
- assistant name
- logo/avatar
- colors and visual identity
- welcome message
- language and currency
- authorized backend/company identifier
- enabled Web channel configuration

The internal intelligence engine may remain named **Bitey**, while the visible assistant can be branded differently for each customer deployment.

## Enterprise role

The plugin provides:

- WordPress installation and lifecycle.
- Configurable Bitey widget/entry point.
- Secure communication with authorized enterprise APIs.
- Conversation/session transport.
- Site/company configuration.
- Channel metadata and language preferences.
- Front-end assets and widget UX.
- Localization and WordPress compatibility.

## Must not live in the plugin

- Provider API keys or private provider secrets.
- Authoritative permanent company memory.
- Cross-company private knowledge.
- A duplicate enterprise backend.
- Unnecessary BiteFixes-only business logic.

## Security

1. Keep provider credentials server-side.
2. Authenticate and authorize backend requests.
3. Validate all inbound/outbound data.
4. Never trust browser-supplied tenant identity without server validation.
5. Do not store authoritative company memory in front-end state.
6. Keep secrets out of JavaScript bundles and public configuration.
7. Keep enterprise context isolated by tenant and authorization.

## Compatibility principle

The existing BiteFixes integration is preserved. SaaS generalization is additive: existing endpoints, widget behavior and backend architecture continue to work while tenant branding and configuration are introduced.

## Installation

1. Build a validated plugin ZIP.
2. In WordPress open **Plugins → Add New → Upload Plugin**.
3. Upload and activate.
4. Configure the authorized enterprise API and tenant/site settings.
5. Test Web widget → API → CRM/IA response flow.
