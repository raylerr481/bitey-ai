# Bitey Channel Evolution

The WordPress plugin is a communication channel, not Bitey's intelligence core.

## Channel evolution markers

Record changes that can affect conversation delivery or context transport, including:

- channel version/build;
- backend endpoint/configuration changes;
- conversation identity continuity;
- language handling;
- attachment transport;
- authentication/authorization changes;
- UI changes that affect the user conversation;
- response delivery failures;
- end-to-end validation results.

## Evaluation relationship

The channel must deliver the conversation without waiting for quality evaluators. Evaluation telemetry is recorded asynchronously through the backend so that channel performance and Bitey IA evolution can be compared over time.

## Periodic checks

- Continuous: record significant channel events and failures.
- Weekly: review channel regressions and context/identity continuity failures.
- Monthly: validate representative conversations against the current baseline.
- Release: record build/version and end-to-end validation results before installation in a production company site.

## Public UX rule

Internal learning, evaluator, provider and architecture information must never leak into the public Bitey chat UI. The user should see a coherent company assistant, not the internal platform development process.
