document.addEventListener("DOMContentLoaded", function () {

    const button = document.getElementById("bitey-button");
    const windowChat = document.getElementById("bitey-window");
    const sendButton = document.getElementById("bitey-send");
    const input = document.getElementById("bitey-input");
    const messages = document.getElementById("bitey-messages");

    if (!button || !windowChat) {
        return;
    }

    // Mostrar / ocultar ventana
    button.addEventListener("click", function () {

        if (windowChat.style.display === "flex") {
            windowChat.style.display = "none";
        } else {
            windowChat.style.display = "flex";
            input.focus();
        }

    });

    // Enter para enviar
    input.addEventListener("keypress", function (e) {

        if (e.key === "Enter") {
            sendMessage();
        }

    });

    sendButton.addEventListener("click", sendMessage);

    function addMessage(text, sender) {

        const div = document.createElement("div");

        div.className = sender;

        div.innerHTML = text;

        messages.appendChild(div);

        messages.scrollTop = messages.scrollHeight;

    }

    function showTyping() {

        const div = document.createElement("div");

        div.id = "bitey-typing";

        div.className = "bitey-ai";

        div.innerHTML = "Bitey está escribiendo...";

        messages.appendChild(div);

        messages.scrollTop = messages.scrollHeight;

    }

    function hideTyping() {

        const typing = document.getElementById("bitey-typing");

        if (typing) {
            typing.remove();
        }

    }

    function sendMessage() {

        const message = input.value.trim();

        if (message === "") {
            return;
        }

        addMessage(message, "bitey-user");

        input.value = "";

        showTyping();

        const form = new FormData();

        form.append("action", "bitey_chat");
        form.append("message", message);
        form.append("nonce", bitey_ajax.nonce);

        fetch(bitey_ajax.ajax_url, {

            method: "POST",

            body: form

        })

        .then(response => response.json())

        .then(data => {

            hideTyping();

            if (data.success) {

                addMessage(data.reply, "bitey-ai");

            } else {

                addMessage(
                    "Error: " + data.reply,
                    "bitey-ai"
                );

            }

        })

        .catch(error => {

            hideTyping();

            addMessage(
                "No se pudo conectar con Bitey.",
                "bitey-ai"
            );

            console.error(error);

        });

    }

});