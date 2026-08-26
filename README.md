# Bitey IA — WordPress Plugin

`bitey-ai` is the **WordPress plugin and enterprise web channel for Bitey IA**.

It is an integration/channel layer. **It is not the Bitey IA Supracerebro.**

## Ecosystem boundary

```text
BITEY IA
  └── bitey-web = Supracerebro
          │
          ├── bitey-ia-app = mobile channel
          │
          └── bitey-ai = WordPress enterprise channel
```

The plugin connects an authorized WordPress site to the appropriate enterprise API. Intelligence, authoritative memory, private company data, provider credentials and business decisions remain server-side.

## Enterprise role

The plugin provides:

- WordPress installation and lifecycle.
- Bitey IA widget/entry point.
- Secure communication with authorized enterprise APIs.
- Conversation/session transport.
- Site/company configuration.
- Channel metadata and language preferences.
- Front-end assets and enterprise widget UX.
- Localization and WordPress compatibility.

## Bitey IA Empresarial

For authorized business deployments, the plugin can expose the **Bitey IA Empresarial** experience. Bitey IA Empresarial maintains Bitey IA's architecture and capabilities while operating with the authorized business context supplied by the enterprise backend.

Within BiteFixes, that context can include CRM, customers, tickets, services, knowledge and workflows. BiteFixes operational/private context remains scoped to authorized BiteFixes flows.

## Must not live in the plugin

- Provider API keys or private provider secrets.
- Authoritative permanent company memory.
- Cross-company private knowledge.
- The general Bitey IA Supracerebro.
- A duplicate enterprise backend.
- Unnecessary BiteFixes-only business logic.

## Ecosystem

| Repository | Product | Role |
|---|---|---|
| `bitey-web` | **Bitey IA Web** | General Bitey IA Supracerebro/web channel |
| `bitey-ia-app` | **Bitey IA App** | Mobile channel of the same Bitey IA |
| `bitey-ai` | **Bitey IA WordPress Plugin** | This WordPress enterprise channel |
| `JobIA` | **JobIA** | Employment/opportunity product |
| `bitey-trainer` | **Bitey Trainer** | Internal intelligence engine of JobIA; not an app |
| `bitey-system-bots-trading` | **Bitey System Bots Trading** | Independent trading module |
| `bitey-system-bots-trading-app` | **Bitey SBT App** | Mobile app for the trading module |
| `bitefixes-backend` | **BiteFixes Backend** | Specialized enterprise backend |
| `bitefixes-web` | **BiteFixes Web** | BiteFixes website/frontend |
| `bitefixes-app` | **BiteFixes App** | BiteFixes mobile channel |

## Security rules

1. Keep provider credentials server-side.
2. Authenticate and authorize backend requests.
3. Validate all inbound/outbound data.
4. Never trust browser-supplied tenant identity without server validation.
5. Do not store authoritative company memory in front-end state.
6. Keep secrets out of JavaScript bundles and public configuration.
7. Keep enterprise context isolated by tenant and authorization.

## Installation

1. Build a validated plugin ZIP.
2. In WordPress open **Plugins → Add New → Upload Plugin**.
3. Upload and activate.
4. Configure the authorized enterprise API and site/company settings.
5. Test a complete widget → API → enterprise response flow.
