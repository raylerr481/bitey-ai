# Bitey IA — WordPress Plugin

`bitey-ai` is the **Bitey IA WordPress plugin**. It is an integration/channel layer for WordPress sites; it is not the general Bitey IA Web brain.

## Role

The plugin provides the configurable Web widget/globe and securely connects a WordPress site to the authorized BiteFixes/Bitey backend.

For BiteFixes deployments, the plugin exposes **Bitey IA Empresarial**, whose behavior is contextual to the company, tenant, authorized knowledge, memory, business rules and enabled tools.

The separate repository `raylerr481/bitey-web` is **Bitey IA Web**, the general/integral AI architecture. That general AI can coordinate models, research, tools and specialized modules, but this WordPress plugin does not become or replace that core.

## Flow

```text
WordPress
   ↓
Bitey IA Plugin
   ↓
Web widget / globe
   ↓
Authorized API
   ↓
BiteFixes Backend / enterprise backend
   ↓
Bitey IA Empresarial
   ↓
CRM / memory / knowledge / authorized automation
```

## SaaS / tenant configuration

The plugin can expose configuration for:

- company/display name
- assistant name
- logo/avatar
- visual identity
- welcome message
- language and currency
- authorized tenant/site identifier
- enabled Web channel options

The backend remains authoritative for tenant identity, authorization, private data and business rules.

## Must not live in the plugin

- Provider API keys or private secrets.
- Authoritative permanent company memory.
- Cross-tenant knowledge.
- A duplicate CRM.
- A duplicate general Bitey IA brain.
- Unrestricted enterprise business logic.

## Security

1. Keep secrets server-side.
2. Treat browser input as untrusted.
3. Validate tenant authorization server-side.
4. Do not expose private CRM/company data to unauthorized clients.
5. Keep authoritative memory and business rules in backend systems.

## Infrastructure and cost policy

Bitey/BiteFixes follows a **free-first, no-surprise-cost architecture**.

- Prefer free services, open-source software, or free tiers without automatic billing risk.
- Do not add providers requiring a payment card merely to start or capable of unexpected entry/egress, API, traffic, storage, or execution charges.
- **Railway is explicitly excluded** from BiteFixes/Bitey infrastructure.
- Cloudflare is permitted when the free tier is sufficient and any later cost occurs only after a clearly defined usage threshold; no paid plan or automatic billing may be enabled without explicit approval.
- Every new service must be checked for pricing, billing behavior, limits, card requirements, and overage behavior before adoption.
- If a service can generate costs without an explicit decision first, use a safer alternative.
- This policy is documentation-only and does not modify existing working integrations.

## Compatibility

Existing BiteFixes WordPress integration remains the compatibility target. Generalization is additive and must not move BiteFixes CRM/SaaS ownership into this plugin or into Bitey IA Web.

## Installation

1. Build a validated plugin ZIP.
2. In WordPress open **Plugins → Add New → Upload Plugin**.
3. Upload and activate.
4. Configure the authorized enterprise API and tenant/site settings.
5. Test Web widget → API → Bitey IA Empresarial flow.
