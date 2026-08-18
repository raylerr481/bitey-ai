document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("bitey-button");
    const windowChat = document.getElementById("bitey-window");
    const closeButton = document.getElementById("bitey-close");
    const sendButton = document.getElementById("bitey-send");
    const input = document.getElementById("bitey-input");
    const nameInput = document.getElementById("bitey-name");
    const phoneInput = document.getElementById("bitey-phone");
    const messages = document.getElementById("bitey-messages");

    if (!button || !windowChat || !sendButton || !input || !messages || typeof bitey_ajax === "undefined") {
        return;
    }

    button.addEventListener("click", function () {
        const isOpen = windowChat.style.display === "flex";
        windowChat.style.display = isOpen ? "none" : "flex";
        if (!isOpen) {
            input.focus();
        }
    });

    if (closeButton) {
        closeButton.addEventListener("click", function () {
            windowChat.style.display = "none";
        });
    }

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            sendMessage();
        }
    });

    sendButton.addEventListener("click", sendMessage);

    function addMessage(text, sender) {
        const div = document.createElement("div");
        div.className = "bitey-message " + sender;
        div.textContent = String(text || "");
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    function showTyping() {
        return addMessage("Bitey está escribiendo...", "bitey-ai");
    }

    function sendMessage() {
        const message = input.value.trim();
        if (!message || sendButton.disabled) {
            return;
        }

        addMessage(message, "bitey-user");
        input.value = "";
        sendButton.disabled = true;
        const typing = showTyping();

        const form = new FormData();
        form.append("action", "bitey_send_message");
        form.append("message", message);
        form.append("nonce", bitey_ajax.nonce);
        form.append("name", nameInput ? nameInput.value.trim() : "Customer");
        form.append("phone", phoneInput ? phoneInput.value.trim() : "");
        form.append("company_id", bitey_ajax.company_id || 1);
        form.append("channel", bitey_ajax.channel || "website");

        fetch(bitey_ajax.ajax_url, { method: "POST", body: form })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error("HTTP " + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                if (typing) {
                    typing.remove();
                }

                if (data.success) {
                    addMessage(data.data && data.data.reply ? data.data.reply : "Bitey no devolvió una respuesta.", "bitey-ai");
                } else {
                    addMessage(data.data && data.data.reply ? data.data.reply : "Bitey no pudo procesar la solicitud.", "bitey-ai");
                }
            })
            .catch(function (error) {
                if (typing) {
                    typing.remove();
                }
                console.error("[Bitey]", error);
                addMessage("No se pudo conectar con Bitey Backend.", "bitey-ai");
            })
            .finally(function () {
                sendButton.disabled = false;
                input.focus();
            });
    }
});
