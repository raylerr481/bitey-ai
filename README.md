# Bitey AI

**Bitey AI** is the WordPress channel of the Bitey AI platform: a lightweight, configurable assistant that connects a website to Bitey Cloud.

## Vision

Bitey is being developed as a universal AI platform with three complementary delivery modes:

- **Bitey Cloud / SaaS** — an independent ChatGPT-like web experience.
- **Bitey Universal Widget / API** — embeddable in almost any website or application.
- **Bitey WordPress Plugin** — installable on WordPress sites, including BiteFixes.

The WordPress plugin is intentionally a channel, not the intelligence core. AI orchestration, memory, RAG, web intelligence, tenant isolation and business workflows remain in Bitey Cloud / BiteFixes Backend.

## Main capabilities

- Conversational AI widget for WordPress.
- Configurable assistant identity: name, display name, language, personality, avatar and branding.
- Session and conversation context.
- Connection to the Bitey backend through a configurable API endpoint.
- REST/AJAX integration for WordPress.
- Shortcode/widget integration.
- Responsive chat UI.
- Automatic build of an installable plugin ZIP through GitHub Actions.

## Universal platform architecture

```text
WordPress / Any Website / SaaS App / WhatsApp
                    |
              Bitey Cloud API
                    |
               Bitey Core
          /        |         \
       Memory     RAG      AI Router
                    |          |
             FAISS/Qdrant   Groq/Gemini/HF/Ollama
             /Chroma
```

## Multi-tenant model

Each company can deploy its own branded assistant while using the shared Bitey platform. A tenant can configure:

- assistant name;
- welcome message;
- language;
- personality/tone;
- logo and avatar;
- colors and branding;
- knowledge base;
- enabled tools and workflows;
- supported channels.

Knowledge and conversations must remain isolated by tenant.

## Installation

1. Download the generated plugin ZIP from the GitHub Actions artifact/release workflow.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP and activate it.
4. Configure the Bitey Cloud/backend endpoint and tenant settings.
5. Add the Bitey widget/shortcode where required.

## Development

The plugin is PHP-based and contains the WordPress bootstrap, API integration, widget, settings, templates, JavaScript and CSS assets.

The repository includes a GitHub Actions workflow for building the installable ZIP.

## Security principles

- Never put provider API keys in browser JavaScript or the plugin frontend.
- Keep provider credentials in Bitey Cloud/Render secrets.
- Authenticate and authorize tenant requests at the backend.
- Treat the WordPress plugin as an untrusted client.
- Do not expose private company knowledge to another tenant.

## Relationship with BiteFixes Backend

`bitey-ai` is the frontend/channel repository. The backend and intelligence services live separately so that the same Bitey intelligence can serve WordPress, the independent SaaS application, APIs and future channels.

## Status

Active development toward **Bitey Cloud Platform v1**. Production readiness requires end-to-end validation of authentication, tenant isolation, backend connectivity, RAG, AI providers, observability and channel integrations.

## License

See the repository license and project terms before redistributing or deploying the plugin.