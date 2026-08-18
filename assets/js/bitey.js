(function () {
    "use strict";

    function initBitey() {
        if (window.__biteyInitialized) {
            return;
        }

        const button = document.getElementById("bitey-button");
        const windowChat = document.getElementById("bitey-window");
        const closeButton = document.getElementById("bitey-close");
        const sendButton = document.getElementById("bitey-send");
        const input = document.getElementById("bitey-input");
        const nameInput = document.getElementById("bitey-name");
        const phoneInput = document.getElementById("bitey-phone");
        const messages = document.getElementById("bitey-messages");

        if (!button || !windowChat || !sendButton || !input || !messages) {
            console.warn("[Bitey] Widget DOM not ready.");
            return;
        }

        if (typeof window.bitey_ajax === "undefined" || !window.bitey_ajax.ajax_url || !window.bitey_ajax.nonce) {
            console.error("[Bitey] AJAX configuration is missing.");
            return;
        }

        window.__biteyInitialized = true;

        button.addEventListener("click", function () {
            const isOpen = windowChat.style.display === "flex";
            windowChat.style.display = isOpen ? "none" : "flex";
            if (!isOpen) input.focus();
        });

        if (closeButton) {
            closeButton.addEventListener("click", function () {
                windowChat.style.display = "none";
            });
        }

        input.addEventListener("keydown", function (event) {
            if (event.key === "Enter" && !event.shiftKey) {
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

        function sendMessage() {
            const message = input.value.trim();
            if (!message || sendButton.disabled) return;

            addMessage(message, "bitey-user");
            input.value = "";
            sendButton.disabled = true;
            const typing = addMessage("Bitey está escribiendo...", "bitey-ai");

            const form = new FormData();
            form.append("action", "bitey_send_message");
            form.append("message", message);
            form.append("nonce", window.bitey_ajax.nonce);
            form.append("name", nameInput ? nameInput.value.trim() : "Customer");
            form.append("phone", phoneInput ? phoneInput.value.trim() : "");
            form.append("company_id", window.bitey_ajax.company_id || 1);
            form.append("channel", window.bitey_ajax.channel || "website");

            fetch(window.bitey_ajax.ajax_url, {
                method: "POST",
                body: form,
                credentials: "same-origin"
            })
                .then(function (response) {
                    if (!response.ok) throw new Error("HTTP " + response.status);
                    return response.json();
                })
                .then(function (data) {
                    typing.remove();
                    const reply = data && data.data && data.data.reply
                        ? data.data.reply
                        : "Bitey no pudo generar una respuesta.";
                    addMessage(reply, "bitey-ai");
                })
                .catch(function (error) {
                    typing.remove();
                    console.error("[Bitey] AJAX error:", error);
                    addMessage("No se pudo conectar con Bitey Backend. Inténtalo nuevamente.", "bitey-ai");
                })
                .finally(function () {
                    sendButton.disabled = false;
                    input.focus();
                });
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initBitey, { once: true });
    } else {
        initBitey();
    }
})();
