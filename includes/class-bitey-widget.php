<?php
if (!defined('ABSPATH')) { exit; }

class Bitey_Widget {
    public function __construct() { add_action('wp_footer', array($this, 'render')); }

    public function render() { ?>
<div id="bitey-container" class="bitey" aria-label="Bitey">
    <button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey"><span aria-hidden="true">🤖</span><b>Bitey</b></button>
    <div id="bitey-window" class="bitey-window" hidden role="dialog" aria-label="Bitey">
        <header class="bitey-header">
            <div class="bitey-brand"><span class="bitey-avatar" aria-hidden="true">🤖</span><div><strong>Bitey</strong></div></div>
            <div class="bitey-header-actions"><span id="bitey-memory" class="bitey-memory" title="Memoria de esta conversación" aria-label="Memoria de esta conversación">•</span><button id="bitey-close" type="button" aria-label="Cerrar">×</button></div>
        </header>
        <div class="bitey-language-row"><label for="bitey-language">Idioma</label><select id="bitey-language" aria-label="Idioma"><option value="auto">Automático</option><option value="es">Español</option><option value="pt-BR">Português</option><option value="en">English</option></select></div>
        <div id="bitey-work" class="bitey-work" hidden role="status" aria-live="polite"><span id="bitey-work-icon" class="bitey-work-icon" aria-hidden="true">•</span><span id="bitey-work-label">Preparando respuesta…</span><span id="bitey-work-time" class="bitey-work-time" aria-hidden="true"></span></div>
        <div id="bitey-messages" class="bitey-messages" aria-live="polite"><div id="bitey-welcome" class="bitey-message bitey-ai">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
        <div class="bitey-document-area">
            <input id="bitey-file-input" type="file" accept=".pdf,.docx,.txt,.csv,.json,.md" hidden>
            <button id="bitey-attach" type="button" class="bitey-attach" aria-label="Adjuntar documento">📎 Adjuntar documento</button>
            <small id="bitey-file-status" class="bitey-file-status" role="status" aria-live="polite">Comparte documentos sobre tu empresa, procesos o proyecto para incorporarlos al contexto de trabajo.</small>
        </div>
        <div class="bitey-input-area"><input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off" maxlength="1000"><button id="bitey-send" type="button" aria-label="Enviar">➤</button></div>
    </div>
</div>
<?php }
}
