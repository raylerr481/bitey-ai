Bitey AI Assistant

WordPress frontend for Bitey Core.

Integration contract
- WordPress -> POST /chat via the BiteFixes FastAPI backend.
- Tenant/company identity is resolved server-side from the plugin setting.
- Browser requests never choose a company_id.
- Supabase remains the backend system of record; the plugin does not connect directly to Supabase.
- Website conversations use a persistent anonymous visitor identifier until the visitor provides a phone number.
