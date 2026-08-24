# Bitey AI — Enterprise WordPress Plugin

`bitey-ai` is the **global WordPress plugin/channel for Bitey IA Enterprise**.

It is installed on business WordPress sites and provides the website-facing integration between the business site and the authorized Bitey enterprise AI services.

> **Important boundary:** this repository is a WordPress integration/plugin. It is not the general Bitey IA web application and it is not a second AI brain.

## Role in the ecosystem

```text
Business WordPress site
        ↓
Bitey AI WordPress Plugin
        ↓
Authorized Bitey Enterprise API
        ↓
Company AI Profile / company knowledge / customer context
        ↓
Reasoning + research + business workflows
        ↓
Business response
```

The plugin is responsible for the **channel and WordPress integration layer**. Intelligence, private company data, memory, provider credentials, permissions and authoritative business decisions remain server-side.

## Responsibilities

- WordPress installation and lifecycle.
- Bitey AI widget / website entry point.
- Secure communication with the authorized AI backend.
- Conversation/session transport.
- Company/site configuration exposed through the WordPress admin UI.
- Channel metadata and language preferences.
- Front-end assets and enterprise widget UX.
- Localization and WordPress compatibility.
- Safe handling of authorized attachments and requests.

## Must NOT live here

- AI provider API keys.
- Permanent customer/company memory.
- Cross-company knowledge.
- Provider-specific business secrets.
- The general Bitey reasoning engine.
- BiteFixes-specific business logic unless explicitly required by an authorized integration contract.

## Relationship to the other repositories

| Repository | Product | Role |
|---|---|---|
| `bitey-web` | **Bitey IA** | General web AI experience and intelligence foundation |
| `bitey-ai` | **Bitey AI Enterprise Plugin** | WordPress enterprise channel/integration |
| `bitefixes-backend` | **BiteFixes Backend** | Specialized BiteFixes enterprise backend/brain |
| `bitefixes-app` | **BiteFixes App** | Mobile BiteFixes channel |

`bitey-ai` may consume an authorized enterprise API exposed by the broader Bitey architecture or by a specialized company backend. It must not silently become a duplicate backend.

## Development rules

1. Keep the plugin WordPress-native and modular.
2. Keep secrets server-side.
3. Validate and authorize all backend requests.
4. Never mix one company's private context with another company's context.
5. Preserve backward compatibility for existing installations when possible.
6. Add tests for security-sensitive and API-contract changes.
7. Do not copy the entire Bitey reasoning engine into the plugin.
8. Do not use the plugin as the source of truth for company memory.

## Installation

Build the validated plugin ZIP, then install it through **WordPress → Plugins → Add New → Upload Plugin**.

For production, validate the complete flow:

```text
WordPress → Plugin → Authorized API → AI/enterprise context → response
```

## Repository status

The repository is public and uses `main` as its default branch. The repository name `bitey-ai` is intentionally retained as the technical slug; the product role is **Bitey AI Enterprise WordPress Plugin**.
