<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_Widget {
    public function __construct() { add_action('wp_footer', array($this, 'render')); }
    public function render() { ?>
<div id="bitey-container" class="bitey" aria-label="Bitey AI">
    <button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI"><span aria-hidden="true">🤖</span><b>Bitey AI</b></button>
    <div id="bitey-window" class="bitey-window" hidden role="dialog" aria-label="Bitey AI">
        <header class="bitey-header"><div class="bitey-brand"><span class="bitey-avatar" aria-hidden="true">🤖</span><div><strong>Bitey AI</strong><small>Aprendiz empresarial</small></div></div><div class="bitey-header-actions"><span id="bitey-memory" class="bitey-memory" title="Memoria de esta conversación" aria-label="Memoria de esta conversación">•</span><button id="bitey-close" type="button" aria-label="Cerrar">×</button></div></header>
        <div class="bitey-language-row"><label for="bitey-language">Idioma</label><select id="bitey-language" aria-label="Idioma"><option value="auto">Automático</option><option value="es">Español</option><option value="pt-BR">Português</option><option value="en">English</option></select></div>
        <section class="bitey-business-intro" aria-label="Contexto empresarial"><strong>🏢 ¿Quieres crear una IA para tu empresa?</strong><p>Cuéntale a Bitey cómo funciona tu negocio o comparte documentos. Las IAs externas podrán investigar y analizar ese contexto para ayudarte a diseñar soluciones y automatizaciones personalizadas.</p></section>
        <div id="bitey-work" class="bitey-work" hidden role="status" aria-live="polite"><span id="bitey-work-icon" class="bitey-work-icon" aria-hidden="true">🧠</span><span id="bitey-work-label">Preparando contexto…</span><span id="bitey-work-time" class="bitey-work-time" aria-hidden="true"></span></div>
        <div id="bitey-messages" class="bitey-messages" aria-live="polite"><div id="bitey-welcome" class="bitey-message bitey-ai">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
        <div class="bitey-attachment-row"><input id="bitey-company-document" type="file" accept=".pdf,.doc,.docx,.txt,.csv,.json,.md,application/pdf,text/plain,application/json,text/csv" hidden><button id="bitey-attach" type="button" aria-label="Adjuntar documento">📎</button><span id="bitey-file-name" aria-live="polite">Adjuntar documento de empresa</span></div>
        <div class="bitey-input-area"><input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off"><button id="bitey-send" type="button" aria-label="Enviar">➤</button></div>
    </div>
</div>
<?php }
}
