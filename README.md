# Bitey AI — WordPress Channel

**Bitey AI** is the official WordPress communication channel/plugin for the Bitey platform. It is a delivery path for conversations with users; it is deliberately **not** the intelligence core.

## Platform architecture

```text
WordPress user
      ↓
bitey-ai
      ↓
bitefixes-backend / Bitey IA
      ↓
Company AI Profile + authorized context
      ↓
knowledge / memory / intelligent web research / workflows
      ↓
external AI collaboration when useful
      ↓
response
```

There is one Bitey brain. The same backend intelligence serves WordPress, the public `bitey-web` facade, `bitefixes-app` mobile and future authorized channels.

## Responsibilities

The plugin transports authorized:

- user messages;
- conversation/session identity;
- supported language preference;
- permitted attachments;
- channel metadata required by the backend.

The plugin must not become the source of truth for company knowledge, customer memory, business rules or provider credentials. Intelligence orchestration, authorization, Company AI Profile, research, memory and tenant isolation remain server-side.

## Conversation continuity

The channel must preserve conversation identity across turns and pass the stable conversation/session identifier to the backend. Previously established facts must remain available to later turns; the plugin must never reset the conversation merely because a new message was sent.

## Public experience

Users should experience a normal, coherent company assistant. Internal architecture, provider routing, evaluation and private learning mechanisms remain server-side unless intentionally exposed as product functionality.

## Security

- Never expose provider API keys in browser JavaScript.
- Treat WordPress as an untrusted channel client.
- Authenticate backend requests.
- Never allow one tenant's private context to cross into another tenant.
- Handle attachments and identity metadata only according to backend authorization rules.

## Installation

1. Obtain the validated installable ZIP produced by GitHub Actions.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload and activate the ZIP.
4. Configure the authenticated Bitey backend endpoint and company/tenant settings.
5. Verify the widget with an end-to-end conversation test.

## Repository relationship

- `bitefixes-backend` — authoritative Bitey IA and intelligence core.
- `bitey-ai` — this WordPress channel/plugin.
- `bitey-web` — public web facade for a ChatGPT-like Bitey experience.
- `bitefixes-app` — mobile channel for accessing BiteFixes and Bitey.

## Status

Active development. Channel changes should be validated against the shared backend contract and end-to-end conversation tests before production installation.
