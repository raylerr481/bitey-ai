# Bitey Plugin Web

**Bitey Plugin Web** is the WordPress plugin/channel for the Bitey ecosystem. It is installed in WordPress sites such as **BiteFixes.com** and provides the web/widget entry point for the site's authorized BiteFixes AI experience.

It is **not the Bitey IA supracerebro** and it must not become a second intelligence core.

## Architecture

```text
WordPress site
      ↓
Bitey Plugin Web
      ↓
BiteFixes Backend
      ↓
BiteFixes AI / Company AI Profile / authorized context
      ↓
response
```

The plugin remains a channel and presentation/integration layer. Business intelligence, customer context, memory, services, tickets, company knowledge, tenant rules and operational decisions remain under the BiteFixes backend architecture.

## Product boundaries

- **Bitey IA** — independent web-based supracerebro in `bitey-web`, designed as a complete AI experience comparable in interaction style to ChatGPT or Claude.
- **Bitey Plugin Web** — this WordPress channel, installed on sites such as BiteFixes.com.
- **BiteFixes Backend** — specialized brain/infrastructure of BiteFixes.com and its authorized channels. It remains independent and is not merged into Bitey IA.

## Responsibilities

The plugin transports authorized channel data such as user messages, conversation/session identity, language preference, permitted attachments and channel metadata. It must not expose provider API keys or become the source of truth for private company/customer data.

## Installation

1. Obtain the validated installable ZIP produced by the repository release workflow.
2. In WordPress, open **Plugins → Add New → Upload Plugin**.
3. Upload and activate the ZIP.
4. Configure the authorized BiteFixes backend endpoint and site/company settings.
5. Run an end-to-end conversation test.

## Naming transition

The repository is currently named `bitey-ai`. Its intended product name is **Bitey Plugin Web**. A repository-level GitHub rename is still required to change the slug without creating a duplicate repository.
