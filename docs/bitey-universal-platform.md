# Bitey Universal Platform

Bitey is a multi-tenant AI platform, not only a WordPress plugin.

## Product surfaces

- Hosted SaaS web app with a ChatGPT-like conversation experience.
- Universal JavaScript widget for any website.
- WordPress plugin using the same cloud API.
- REST API/SDK for custom applications.
- Future channel adapters such as WhatsApp.
- Optional custom domains and white-label assistant identity.

## Tenant model

Each company is an isolated tenant. A tenant owns its assistant identity, branding, knowledge, users, conversations, memory, tools, workflows and channel configuration.

A company can configure an assistant name such as `Bitey`, `Nexa`, `Luna`, or any other approved name. The platform identity remains Bitey Cloud while the end-user assistant identity is tenant-configurable.

## Universal widget contract

The widget must be thin. It renders UI and sends authenticated requests to Bitey Cloud. Business intelligence, provider routing, RAG, memory and permissions remain server-side.

Example integration:

```html
<script src="https://cdn.bitey.ai/widget.js"></script>
<script>
  Bitey.init({
    tenant: "empresa_xyz",
    assistant: "nexa"
  });
</script>
```

The production host and authentication contract will be supplied by Bitey Cloud; examples above are architectural placeholders until the public SaaS domain is deployed.

## WordPress

The existing plugin remains a first-class channel. It must not duplicate the AI engine. It connects to the same tenant-aware cloud gateway used by the hosted app and universal widget.

## Security requirements

- Every request is tenant-scoped server-side.
- Tenant identity must never be trusted solely from browser input.
- Vector retrieval must filter by tenant ID.
- Conversation and memory access must be authorization checked.
- Provider API keys remain server-side.
- Custom assistant branding cannot change security policy or tool permissions.

## Architecture

```text
Bitey Web / Universal Widget / WordPress / API / Channels
                         |
                 Bitey Cloud Gateway
                         |
                    Tenant Context
                         |
                 Bitey Core + Memory
                  /       |        \
                RAG     Tools     Workflows
                 |                    |
          FAISS/Qdrant/Chroma     Business APIs
                         |
                    AI Orchestrator
                         |
             Groq / Gemini / HF / Ollama
                         |
                    Verified response
```

## BiteFixes

BiteFixes is the reference tenant for production validation. Bitey Cloud must remain product-agnostic so other companies can onboard without changing the core engine.
