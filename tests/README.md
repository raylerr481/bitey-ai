# Bitey AI plugin validation

The production checklist is intentionally separate from the backend test suite.

Before installing a release, verify:

- PHP 8.0+ syntax compatibility.
- WordPress 6.x compatibility.
- No PHP warnings/notices on activation.
- Exactly one floating widget is rendered.
- AJAX requests are protected by a nonce.
- Backend URL uses HTTPS.
- No API key is embedded in JavaScript/CSS.
- `language_preference=auto` is sent to the backend.
- Conversation ID is retained between messages.
- Backend error responses fail safely.
