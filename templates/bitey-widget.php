<?php

if (!defined('ABSPATH')) {
    exit;
}

?>

<!-- Botón flotante Bitey -->

<button id="bitey-button">
    💬 Bitey AI
</button>



<!-- Ventana del chat -->

<div id="bitey-window">


    <div id="bitey-header">

        <span>
            🤖 Bitey AI Assistant
        </span>

        <button id="bitey-close">
            ✕
        </button>

    </div>



    <div id="bitey-messages">

        <div class="bitey-message bot">

            Hola 👋 soy Bitey.
            ¿En qué puedo ayudarte con tu equipo?

        </div>

    </div>




    <div id="bitey-input-area">


        <input 
            type="text"
            id="bitey-input"
            placeholder="Describe tu problema técnico..."
        >



        <button id="bitey-send">

            Enviar

        </button>


    </div>


</div>