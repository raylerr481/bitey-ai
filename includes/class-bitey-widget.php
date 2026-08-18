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
        <div id="bitey-container" class="bitey-container">
            <button id="bitey-button" class="bitey-button" type="button" aria-controls="bitey-window" aria-expanded="false" aria-label="Abrir Bitey AI">
                🤖
            </button>

            <section id="bitey-window" class="bitey-window" aria-label="Bitey AI Assistant" hidden>
                <div id="bitey-header" class="bitey-header">
                    <span>Bitey AI</span>
                    <button id="bitey-close" type="button" aria-label="Cerrar Bitey">×</button>
                </div>

                <div id="bitey-messages" class="bitey-messages" aria-live="polite">
                    <div class="bitey-ai">
                        Hola 👋 soy Bitey.<br>
                        ¿Cómo puedo ayudarte?
                    </div>
                </div>

                <div class="bitey-user-data">
                    <label class="screen-reader-text" for="bitey-name">Tu nombre</label>
                    <input id="bitey-name" type="text" autocomplete="name" placeholder="Tu nombre">

                    <label class="screen-reader-text" for="bitey-phone">WhatsApp</label>
                    <input id="bitey-phone" type="tel" autocomplete="tel" placeholder="WhatsApp (opcional)">
                </div>

                <div class="bitey-input-area">
                    <label class="screen-reader-text" for="bitey-input">Mensaje</label>
                    <input id="bitey-input" type="text" autocomplete="off" maxlength="2000" placeholder="Describe tu problema...">
                    <button id="bitey-send" type="button">Enviar</button>
                </div>
            </section>
        </div>
        <?php
    }
}
