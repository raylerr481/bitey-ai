<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="bitey-widget">

    <button id="bitey-button" type="button" aria-label="Abrir Bitey AI">
        💬
    </button>

    <div id="bitey-window">

        <div id="bitey-header">
            Bitey AI Assistant
        </div>

        <div id="bitey-messages">

            <div class="bitey-ai">
                👋 Hola, soy <strong>Bitey</strong>.

                <br><br>

                Soy el asistente técnico de BiteFixes.

                <br><br>

                ¿En qué puedo ayudarte hoy?
            </div>

        </div>

        <input
            id="bitey-input"
            type="text"
            maxlength="500"
            placeholder="Escribe tu consulta técnica..."
            autocomplete="off">

        <button id="bitey-send" type="button">
            Enviar
        </button>

    </div>

</div>