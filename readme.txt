=== Bitey AI Assistant ===
Contributors: bitefixes
Requires at least: 6.4
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Enterprise AI facade for WordPress. Bitey works inside a company context while external AI models remain the intellectual directors until Bitey demonstrates maturity through evaluation.

== Architecture ==

WordPress is the presentation and secure gateway layer. FastAPI and Supabase hold the enterprise profile, knowledge, conversations and learning evidence. External AI models receive a task-specific enterprise context contract containing authorized company objectives, directives, vocabulary, services, knowledge and conversation context.

Bitey does not replace the external models. It coordinates the context, memory, user interaction and evaluation. Autonomy is earned through measurable evidence and can be reduced after regressions.

== Enterprise profile ==

Administrators can provide company description, website and documents. Documents are sent to the backend for ingestion and versioned profile processing. The default BiteFixes environment uses company ID 1 and https://www.bitefixes.com.

== Installation ==

1. Download the repository ZIP from GitHub or the packaged GitHub Actions artifact.
2. Make sure the ZIP contains exactly one top-level plugin folder named bitey-ai and that bitey-ai.php is directly inside that folder.
3. Do not create a ZIP inside another ZIP.
4. In WordPress, open Plugins > Add New Plugin > Upload Plugin and upload the ZIP.
5. Activate Bitey AI.
6. Open Settings > Bitey AI and verify the FastAPI backend URL and Company ID.
7. Open Settings > Bitey Company AI to manage the living enterprise profile and documents.

== Important ==

The plugin does not store AI provider secrets in the browser. Provider credentials belong in the backend environment. The plugin sends only the company identifier and task/conversation data required by the backend.

== Changelog ==

= 3.0.0 =
* Rebuilt the plugin around the living enterprise profile architecture.
* External AI models remain the intellectual directors/supervisors.
* Added company context and document administration.
* Added version-safe backend gateway flow.
* Added WordPress compatibility structure and packaging validation.
