/**
 * ==========================================================
 * Bitey AI Assistant
 * Frontend Widget
 * BiteFixes
 * ==========================================================
 */

document.addEventListener("DOMContentLoaded", () => {

    const button = document.getElementById("bitey-button");
    const windowChat = document.getElementById("bitey-window");
    const closeButton = document.getElementById("bitey-close");
    const sendButton = document.getElementById("bitey-send");
    const input = document.getElementById("bitey-input");
    const messages = document.getElementById("bitey-messages");
    const typing = document.getElementById("bitey-typing");

    if (!button || !windowChat) {
        return;
    }

    /**
     * Abrir chat
     */
    button.addEventListener("click", () => {
        windowChat.classList.remove("bitey-hidden");
        input.focus();
    });

    /**
     * Cerrar chat
     */
    if (closeButton) {
        closeButton.addEventListener("click", () => {
            windowChat.classList.add("bitey-hidden");
        });
    }

    /**
     * Enter para enviar
     */
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            sendMessage();
        }
    });

    sendButton.addEventListener("click", sendMessage);

    /**
     * Agregar mensaje
     */
    function addMessage(author, text) {

        const div = document.createElement("div");

        div.className =
            author === "user"
                ? "bitey-message bitey-user"
                : "bitey-message bitey-bot";

        div.innerHTML = `<strong>${author === "user" ? "Tú" : "Bitey"}:</strong><br>${escapeHtml(text)}`;

        messages.appendChild(div);

        messages.scrollTop = messages.scrollHeight;
    }

    /**
     * Escapar HTML
     */
    function escapeHtml(text) {

        const div = document.createElement("div");
        div.innerText = text;
        return div.innerHTML;
    }

    /**
     * Mostrar indicador escribiendo
     */
    function showTyping(show) {

        if (!typing) return;

        typing.classList.toggle("bitey-hidden", !show);
    }

    /**
     * Enviar mensaje
     */
    async function sendMessage() {

        const text = input.value.trim();

        if (text === "") return;

        addMessage("user", text);

        input.value = "";

        showTyping(true);

        try {

            const response = await fetch(
                BiteyConfig.api_url,
                {
                    method: "POST",

                    headers: {
                        "Content-Type": "application/json"
                    },

                    body: JSON.stringify({

                        message: text,

                        session_id: BiteyConfig.session_id,

                        language: BiteyConfig.language

                    })

                }
            );

            const data = await response.json();

            showTyping(false);

            if (data.response) {

                addMessage("bot", data.response);

            } else {

                addMessage(
                    "bot",
                    "No pude procesar tu solicitud."
                );

            }

        } catch (error) {

            console.error(error);

            showTyping(false);

            addMessage(
                "bot",
                "No fue posible conectar con Bitey."
            );

        }

    }

});