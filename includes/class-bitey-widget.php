<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_Widget {
    public function __construct() { add_action('wp_footer', array($this, 'render')); }
    public function render() { ?>
<div id="bitey-container" aria-label="Bitey AI Assistant">
<button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI"><span>🤖</span><b>Bitey AI</b></button>
<div id="bitey-window" class="bitey-window" style="display:none" role="dialog" aria-label="Bitey AI">
<div class="bitey-header"><div class="bitey-brand"><span class="bitey-avatar">🤖</span><div><strong>Bitey AI</strong><small>Asistente inteligente</small></div></div><button id="bitey-close" type="button" aria-label="Cerrar">×</button></div>
<div id="bitey-messages" class="bitey-messages" aria-live="polite"><div id="bitey-welcome" class="bitey-message bitey-ai">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
<div class="bitey-input-area"><input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off"><button id="bitey-send" type="button" aria-label="Enviar">➤</button></div>
</div></div>
<?php }
}
