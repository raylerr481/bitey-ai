# Bitey AI 2.3.0 — production optimization

## Design
- Minimal WordPress UI: conversation only.
- No service checkboxes, menus, or language selector.
- Language is inferred by Bitey Backend.
- Customer data is collected progressively; the plugin does not force a form.
- The plugin is a thin transport/presentation layer. Business intelligence stays in Bitey Backend.
- Backend remains responsible for intent, missing-information questions, customer/lead/ticket decisions, AI provider selection, and Supabase persistence.

## Production requirements
- Backend URL is configurable in WordPress admin and must use HTTPS in production.
- Requests are authenticated server-to-server; API credentials must never be exposed to browser JavaScript.
- AJAX requests require WordPress nonce validation.
- External AI providers remain optional; Bitey must operate with its deterministic core when providers are unavailable.
- Do not store provider API keys in this repository.
- Conversation state is represented by the backend `conversation_id`.

## Release validation
1. Activate plugin.
2. Verify only one widget is rendered.
3. Test Spanish, Portuguese and English without a language selector.
4. Verify name/phone are requested only when needed.
5. Verify backend failures produce a safe user-facing message.
6. Verify no secrets are present in frontend assets.
7. Run PHP syntax/lint and WordPress compatibility checks before release.
