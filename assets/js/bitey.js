(function () {
    "use strict";

    var STORAGE_KEY = "bitey_conversation_id";
    var LANGUAGE_KEY = "bitey_language_preference";
    var NAME_KEY = "bitey_customer_name";
    var PHONE_KEY = "bitey_customer_phone";

    var labels = {
        auto: { welcome: "Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?", name: "Tu nombre", phone: "Teléfono / WhatsApp", message: "Escribe tu mensaje...", send: "Enviar", typing: "Bitey está escribiendo...", error: "No se pudo conectar con Bitey Backend. Inténtalo nuevamente." },
        "pt-BR": { welcome: "Olá 👋 sou o Bitey. Como posso ajudar?", name: "Seu nome", phone: "Telefone / WhatsApp", message: "Digite sua mensagem...", send: "Enviar", typing: "Bitey está digitando...", error: "Não foi possível conectar ao Bitey Backend. Tente novamente." },
        es: { welcome: "Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?", name: "Tu nombre", phone: "Teléfono / WhatsApp", message: "Escribe tu mensaje...", send: "Enviar", typing: "Bitey está escribiendo...", error: "No se pudo conectar con Bitey Backend. Inténtalo nuevamente." },
        en: { welcome: "Hi 👋 I'm Bitey. How can I help you?", name: "Your name", phone: "Phone / WhatsApp", message: "Type your message...", send: "Send", typing: "Bitey is typing...", error: "Could not connect to Bitey Backend. Please try again." }
    };

    function read(key, fallback) {
        try { return localStorage.getItem(key) || fallback; } catch (e) { return fallback; }
    }

    function write(key, value) {
        try { localStorage.setItem(key, value || ""); } catch (e) {}
    }

    function getConversationId() {
        var current = read(STORAGE_KEY, "");
        if (current) return current;
        var generated = "web-" + Date.now() + "-" + Math.random().toString(36).slice(2, 10);
        write(STORAGE_KEY, generated);
        return generated;
    }

    function initBitey() {
        if (window.__biteyInitialized) return;
        var button = document.getElementById("bitey-button");
        var windowChat = document.getElementById("bitey-window");
        var closeButton = document.getElementById("bitey-close");
        var form = document.getElementById("bitey-form");
        var sendButton = document.getElementById("bitey-send");
        var input = document.getElementById("bitey-input");
        var nameInput = document.getElementById("bitey-name");
        var phoneInput = document.getElementById("bitey-phone");
        var languageSelect = document.getElementById("bitey-language-select");
        var messages = document.getElementById("bitey-messages");
        var status = document.getElementById("bitey-status");

        if (!button || !windowChat || !form || !sendButton || !input || !messages || !languageSelect) return;
        if (!window.bitey_ajax || !window.bitey_ajax.ajax_url || !window.bitey_ajax.nonce) {
            console.error("[Bitey] AJAX configuration is missing.");
            return;
        }

        window.__biteyInitialized = true;
        var language = read(LANGUAGE_KEY, "auto");
        if (!labels[language]) language = "auto";
        languageSelect.value = language;
        if (nameInput) nameInput.value = read(NAME_KEY, "");
        if (phoneInput) phoneInput.value = read(PHONE_KEY, "");
        applyLabels(language);

        function applyLabels(lang) {
            var copy = labels[lang] || labels.auto;
            var welcome = messages.querySelector("[data-bitey-i18n='welcome']");
            if (welcome && messages.children.length === 1) welcome.textContent = copy.welcome;
            if (nameInput) nameInput.placeholder = copy.name;
            if (phoneInput) phoneInput.placeholder = copy.phone;
            input.placeholder = copy.message;
            var buttonText = sendButton.querySelector("span");
            if (buttonText) buttonText.textContent = copy.send;
        }

        button.addEventListener("click", function () {
            var opening = windowChat.hidden;
            windowChat.hidden = !opening;
            button.setAttribute("aria-expanded", String(opening));
            if (opening) input.focus();
        });
        if (closeButton) closeButton.addEventListener("click", function () {
            windowChat.hidden = true;
            button.setAttribute("aria-expanded", "false");
            button.focus();
        });
        languageSelect.addEventListener("change", function () {
            language = languageSelect.value;
            write(LANGUAGE_KEY, language);
            applyLabels(language);
        });

        if (nameInput) nameInput.addEventListener("input", function () { write(NAME_KEY, nameInput.value.trim()); });
        if (phoneInput) phoneInput.addEventListener("input", function () { write(PHONE_KEY, phoneInput.value.trim()); });

        function addMessage(text, sender) {
            var div = document.createElement("div");
            div.className = "bitey-message " + sender;
            div.textContent = String(text || "");
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
            return div;
        }

        function sendMessage() {
            var message = input.value.trim();
            if (!message || sendButton.disabled) return;

            var name = nameInput ? nameInput.value.trim() : "";
            var phone = phoneInput ? phoneInput.value.trim() : "";
            write(NAME_KEY, name);
            write(PHONE_KEY, phone);

            addMessage(message, "bitey-user");
            input.value = "";
            sendButton.disabled = true;
            status.textContent = "";
            var copy = labels[language] || labels.auto;
            var typing = addMessage(copy.typing, "bitey-ai bitey-typing");

            var formData = new FormData();
            formData.append("action", "bitey_send_message");
            formData.append("message", message);
            formData.append("nonce", window.bitey_ajax.nonce);
            formData.append("name", name);
            formData.append("phone", phone);
            formData.append("company_id", window.bitey_ajax.company_id || 1);
            formData.append("channel", window.bitey_ajax.channel || "website");
            formData.append("conversation_id", getConversationId());
            formData.append("language_preference", language);

            fetch(window.bitey_ajax.ajax_url, { method: "POST", body: formData, credentials: "same-origin", cache: "no-store" })
                .then(function (response) {
                    return response.json().then(function (data) {
                        if (!response.ok || !data || !data.success) {
                            var detail = data && data.data ? (data.data.reply || data.data.code || "HTTP " + response.status) : "HTTP " + response.status;
                            throw new Error(detail);
                        }
                        return data;
                    });
                })
                .then(function (data) {
                    typing.remove();
                    var result = data.data || {};
                    if (result.conversation_id) write(STORAGE_KEY, result.conversation_id);
                    if (result.customer_name && nameInput) { nameInput.value = result.customer_name; write(NAME_KEY, result.customer_name); }
                    addMessage(result.reply || "Bitey no pudo generar una respuesta.", "bitey-ai");
                })
                .catch(function (error) {
                    typing.remove();
                    console.error("[Bitey] AJAX error:", error);
                    status.textContent = error.message || copy.error;
                    addMessage(copy.error, "bitey-ai bitey-error");
                })
                .finally(function () {
                    sendButton.disabled = false;
                    input.focus();
                });
        }

        form.addEventListener("submit", function (event) {
            event.preventDefault();
            sendMessage();
        });
    }

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", initBitey, { once: true });
    else initBitey();
})();
