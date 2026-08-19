<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_Widget {
    public function __construct() { add_action('wp_footer', array($this, 'render')); }
    public function render() { ?>
<div id="bitey-container" class="bitey" aria-label="Bitey AI">
    <button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI"><span aria-hidden="true">🤖</span><b>Bitey AI</b></button>
    <div id="bitey-window" class="bitey-window" hidden role="dialog" aria-label="Bitey AI">
        <header class="bitey-header"><div class="bitey-brand"><span class="bitey-avatar" aria-hidden="true">🤖</span><div><strong>Bitey AI</strong><small>Asistente empresarial</small></div></div><div class="bitey-header-actions"><span id="bitey-memory" class="bitey-memory" title="Memoria de esta conversación" aria-label="Memoria de esta conversación">•</span><button id="bitey-close" type="button" aria-label="Cerrar">×</button></div></header>
        <div class="bitey-language-row"><label for="bitey-language">🌐</label><select id="bitey-language" aria-label="Idioma"><option value="auto">Automático</option><option value="es">Español</option><option value="pt-BR">Português</option><option value="en">English</option></select></div>
        <div id="bitey-work" class="bitey-work" hidden role="status" aria-live="polite"><span id="bitey-work-icon" class="bitey-work-icon" aria-hidden="true">🧠</span><span id="bitey-work-label">Entendiendo tu consulta…</span></div>
        <div id="bitey-messages" class="bitey-messages" aria-live="polite"><div id="bitey-welcome" class="bitey-message bitey-ai">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
        <div class="bitey-input-area"><input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off"><button id="bitey-send" type="button" aria-label="Enviar">➤</button></div>
    </div>
</div>
<?php }
}