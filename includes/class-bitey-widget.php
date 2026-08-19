<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_Widget {
    public function __construct() { add_action('wp_footer', array($this, 'render')); }
    public function render() { ?>
<div id="bitey-container" aria-label="Bitey AI Assistant">
<button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI"><span>🤖</span><b>Bitey AI</b></button>
<div id="bitey-window" class="bitey-window" style="display:none" role="dialog" aria-label="Bitey AI">
<div class="bitey-header"><div class="bitey-brand"><span class="bitey-avatar">🤖</span><div><strong>Bitey AI</strong><small>BiteFixes · Asistente inteligente</small></div></div><div class="bitey-header-actions"><select id="bitey-language" aria-label="Idioma"><option value="auto">🌐 Automático</option><option value="es">🇪🇸 Español</option><option value="pt-BR">🇧🇷 Português</option><option value="en">🇺🇸 English</option></select><button id="bitey-close" type="button" aria-label="Cerrar">×</button></div></div>
<div id="bitey-messages" class="bitey-messages" aria-live="polite"><div id="bitey-welcome" class="bitey-message bitey-ai">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
<div id="bitey-user-data" class="bitey-user-data"><input id="bitey-name" type="text" placeholder="Tu nombre" autocomplete="name"><input id="bitey-phone" type="tel" placeholder="Teléfono / WhatsApp" autocomplete="tel"><button id="bitey-identify" type="button">Continuar</button></div>
<div class="bitey-quick-actions"><button type="button" data-message="Necesito reparar un celular">📱 Celular</button><button type="button" data-message="Necesito reparar una computadora">💻 Computador</button><button type="button" data-message="Tengo un problema con mi red o Wi-Fi">🌐 Redes / Wi-Fi</button><button type="button" data-message="Necesito instalar cámaras CCTV">📹 Cámaras</button><button type="button" data-message="Quiero IA para mi empresa">🤖 IA empresarial</button><button type="button" data-message="Quiero solicitar un presupuesto">💰 Presupuesto</button></div>
<div class="bitey-input-area"><input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off"><button id="bitey-send" type="button" aria-label="Enviar">➤</button></div>
</div></div>
<?php }
}