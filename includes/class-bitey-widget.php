<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_Widget {
    public function __construct() {
        add_action('wp_footer', array($this, 'render'));
    }

    public function render() {
        ?>
        <div id="bitey-container" class="bitey-container" aria-label="Bitey AI Assistant">
            <button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI">
                <span class="bitey-button-icon" aria-hidden="true">✦</span>
                <span class="bitey-button-pulse" aria-hidden="true"></span>
            </button>

            <section id="bitey-window" class="bitey-window" hidden role="dialog" aria-modal="false" aria-labelledby="bitey-title">
                <header class="bitey-header">
                    <div class="bitey-brand">
                        <div class="bitey-avatar" aria-hidden="true">✦</div>
                        <div>
                            <strong id="bitey-title">Bitey</strong>
                            <small>Asistente IA empresarial</small>
                        </div>
                    </div>
                    <div class="bitey-online"><span></span> Online</div>
                    <button id="bitey-close" type="button" aria-label="Cerrar Bitey">×</button>
                </header>

                <div class="bitey-language" aria-label="Idioma de Bitey">
                    <label for="bitey-language-select">Idioma</label>
                    <select id="bitey-language-select">
                        <option value="auto">Automático</option>
                        <option value="pt-BR">Português</option>
                        <option value="es">Español</option>
                        <option value="en">English</option>
                    </select>
                </div>

                <div id="bitey-messages" class="bitey-messages" aria-live="polite" aria-atomic="false">
                    <div class="bitey-message bitey-ai" data-bitey-i18n="welcome">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div>
                </div>

                <div class="bitey-user-data">
                    <div class="bitey-field">
                        <label for="bitey-name">Nombre</label>
                        <input id="bitey-name" type="text" placeholder="Tu nombre" autocomplete="name" maxlength="120" />
                    </div>
                    <div class="bitey-field">
                        <label for="bitey-phone">Teléfono / WhatsApp</label>
                        <input id="bitey-phone" type="tel" placeholder="+55 ..." autocomplete="tel" maxlength="40" />
                    </div>
                </div>
                <div class="bitey-privacy">Tus datos se utilizan para identificar tu solicitud y continuar la conversación.</div>

                <form id="bitey-form" class="bitey-input-area">
                    <input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off" maxlength="2000" required />
                    <button id="bitey-send" type="submit"><span>Enviar</span> <span aria-hidden="true">➤</span></button>
                </form>

                <div id="bitey-status" class="bitey-status" role="status" aria-live="polite"></div>
            </section>
        </div>
        <?php
    }
}
