<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_Widget
{
    public function __construct()
    {
        add_action('wp_footer', array($this, 'render'));
    }

    public function render()
    {
        ?>
        <div id="bitey-container" aria-label="Bitey AI Assistant">
            <button
                id="bitey-button"
                class="bitey-button"
                type="button"
                aria-controls="bitey-window"
                aria-expanded="false"
                aria-label="Abrir Bitey AI">
                🤖
            </button>

            <div
                id="bitey-window"
                class="bitey-window"
                style="display:none;"
                role="dialog"
                aria-label="Bitey AI">
                <div class="bitey-header">
                    <span>Bitey AI</span>
                    <button id="bitey-close" type="button" aria-label="Cerrar Bitey">×</button>
                </div>

                <div id="bitey-messages" class="bitey-messages" aria-live="polite">
                    <div class="bitey-message bitey-ai">
                        Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?
                    </div>
                </div>

                <div class="bitey-user-data">
                    <input id="bitey-name" type="text" placeholder="Tu nombre" autocomplete="name" />
                    <input id="bitey-phone" type="tel" placeholder="WhatsApp" autocomplete="tel" />
                </div>

                <div class="bitey-input-area">
                    <input id="bitey-input" type="text" placeholder="Escribe tu mensaje..." autocomplete="off" />
                    <button id="bitey-send" type="button">Enviar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
