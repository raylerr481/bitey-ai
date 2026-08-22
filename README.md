# Bitey AI — WordPress Channel

**Bitey AI** is the WordPress/web communication channel of the Bitey platform. It is the delivery path for conversations with users; it is deliberately **not** the intelligence core.

## Platform evolution model

Bitey is evolving as a platform with three complementary layers:

- **Bitey Channel** — communication via WordPress/web and, through the shared backend architecture, future WhatsApp, voice/phone, app and API channels.
- **Bitey IA** — the platform's own evolving intelligence, grounded in the company it serves and the authorized context available to the conversation.
- **Bitey Evolution** — longitudinal observation and evaluation used to measure improvement, regression and learning over time.

```text
User
 ↓
Bitey Channel
 ↓
Bitey Backend
 ↓
Company AI Profile + authorized context
 ↓
Bitey IA / external AI collaboration
 ↓
Response
 │
 └──→ asynchronous evaluation + evolution markers
```

## Public-channel rule

The public chat must not expose internal learning or architecture concepts. Do not display phrases or UI referring to:

- "Aprendiz empresarial";
- external AI providers as an implementation detail;
- internal evaluation;
- Company AI Profile construction;
- private documents or internal ingestion workflows;
- how Bitey learns internally.

The user should experience a normal, coherent company assistant. Internal context, provider selection and evolution telemetry remain server-side.

## Conversation continuity

The channel must preserve conversation identity across turns and pass the stable conversation/session identifier to the backend. The backend is responsible for assembling authoritative company context and conversation state.

For example, after:

```text
User: Necesito reparar mi teléfono.
Bitey: ¿Qué problema presenta?
User: Pantalla rota.
```

the next request must retain the established device/problem context. The channel must never reset the conversation identity or force the user to repeat information already known.

## Context and channel responsibilities

The plugin may transport authorized:

- user messages;
- conversation/session identity;
- supported language preference;
- permitted attachments;
- channel metadata required by the backend.

It must not contain provider secrets or become the source of truth for business knowledge. Company context, authorization, memory, service resolution and intelligence orchestration remain in the backend.

## Evolution markers

The channel can contribute telemetry needed for longitudinal evaluation, such as timestamp, channel, conversation/build identifier and delivery outcome. It must not block a response while waiting for an evaluator.

Post-response evaluation can record markers against the backend's evolution history, allowing periodic comparisons between Bitey versions/builds.

Useful dimensions include context grounding, conversation continuity, business/service alignment, factuality, helpfulness, safety/authorization and language quality.

## Installation

1. Obtain the validated installable ZIP produced by GitHub Actions.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload and activate the ZIP.
4. Configure the authenticated Bitey backend endpoint and company/tenant settings.
5. Verify the widget through an end-to-end conversation test.

## Security

- Never expose provider API keys in browser JavaScript.
- Treat WordPress as an untrusted channel client.
- Authenticate backend requests.
- Never allow one tenant's private context to cross into another tenant.
- Handle attachments and identity metadata only according to backend authorization rules.

## Relationship with BiteFixes Backend

`bitey-ai` supplies the communication surface. `bitefixes-backend` is the authoritative intelligence/context layer. The same intelligence architecture should eventually serve WordPress, web, SaaS, voice, WhatsApp, mobile and other authorized channels.

## Status

Active development toward **Bitey Platform v1 and an evolving Bitey IA**. Changes to the channel should be evaluated against the longitudinal evolution markers and end-to-end conversation tests before production installation.
