# Bitey AI Assistant

Bitey AI is the WordPress conversational interface for the BiteFixes AI platform. It provides a lightweight customer-facing chat experience and delegates intelligence, business context, memory, workflows and integrations to Bitey Backend.

## Architecture

```text
WordPress / Bitey AI
        |
        v
BiteFixes Backend (FastAPI)
        |
        +-- Bitey Core: intent, customer context, services, tickets, workflows
        +-- Web Intelligence: search, sources, verification, cache and learning memory
        +-- AI Orchestrator: provider selection and failover
        |      +-- Ollama / open models (local-first)
        |      +-- Google Gemini API (optional free tier, quota-limited)
        |      +-- Groq Cloud (optional free tier, quota-limited)
        |      +-- Hugging Face Inference Providers / Meta Llama (optional)
        |
        +-- RAG / vector layer (optional)
               +-- Supabase / PostgreSQL source of truth
               +-- Qdrant / Chroma / FAISS adapters
               +-- LangChain / LlamaIndex integrations
               +-- Flowise / Langflow as external visual orchestration options
```

## Design principles

- **Local-first and open-source:** Ollama and open models are preferred whenever practical.
- **No mandatory paid AI:** cloud providers are optional and disabled until credentials are configured.
- **Provider failover:** a provider outage must not break the Bitey Core conversation path.
- **Business authority stays in Bitey Core:** external models provide reasoning assistance; they do not directly execute business actions.
- **Progressive identity:** Bitey asks for customer information only when it becomes useful to solve the request.
- **RAG-ready:** enterprise knowledge can be indexed and retrieved without replacing Supabase as the system of record.

## AI ecosystem

Bitey is designed to consume open models and community tooling rather than depend on a single vendor. LangChain and LlamaIndex are integration layers, while Flowise and Langflow can be used for visual workflows. Ollama provides a simple local runtime. Hugging Face provides access to models and inference providers, including Meta Llama families. Qdrant, Chroma and FAISS can provide vector retrieval depending on deployment requirements.

## Cost model

The repository itself is open-source. **Free cloud tiers are quota-limited and can change.** Gemini, Groq and Hugging Face should therefore be treated as optional acceleration/fallback services, not as guaranteed unlimited free infrastructure. Ollama is the preferred zero-API-key development path when local compute is available.

## Backend

The companion backend is [`raylerr481/bitefixes-backend`](https://github.com/raylerr481/bitefixes-backend).

## License

GPL-2.0.
