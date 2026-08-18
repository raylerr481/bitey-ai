(function () {
    "use strict";

    var STORAGE_KEY = "bitey_conversation_id";
    var LANGUAGE_KEY = "bitey_language_preference";

    var labels = {
        "auto": {
            welcome: "Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?",
            name: "Tu nombre",
            phone: "WhatsApp",
            message: "Escribe tu mensaje...",
            send: "Enviar",
            language: "Idioma",
            typing: "Bitey está escribiendo...",
            error: "No se pudo conectar con Bitey Backend. Inténtalo nuevamente."
        },
        "pt-BR": {
            welcome: "Olá 👋 sou o Bitey. Como posso ajudar?",
            name: "Seu nome",
            phone: "WhatsApp",
            message: "Digite sua mensagem...",
            send: "Enviar",
            language: "Idioma",
            typing: "Bitey está digitando...",
            error: "Não foi possível conectar ao Bitey Backend. Tente novamente."
        },
        "es": {
            welcome: "Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?",
            name: "Tu nombre",
            phone: "WhatsApp",
            message: "Escribe tu mensaje...",
            send: "Enviar",
            language: "Idioma",
            typing: "Bitey está escribiendo...",
            error: "No se pudo conectar con Bitey Backend. Inténtalo nuevamente."
        },
        "en": {
            welcome: "Hi 👋 I'm Bitey. How can I help you?",
            name: "Your name",
            phone: "WhatsApp",
            message: "Type your message...",
            send: "Send",
            language: "Language",
            typing: "Bitey is typing...",
            error: "Could not connect to Bitey Backend. Please try again."
        }
    };

    function getConversationId() {
        try {
            var current = localStorage.getItem(STORAGE_KEY);
            if (current) return current;
            var generated = "web-" + Date.now() + "-" + Math.random().toString(36).slice(2, 10);
            localStorage.setItem(STORAGE_KEY, generated);
            return generated;
        } catch (e) {
            return "web-" + Date.now();
        }
    }

    function getLanguage() {
        try {
            return localStorage.getItem(LANGUAGE_KEY) || "auto";
        } catch (e) {
            return "auto";
        }
    }

    function setLanguage(language) {
        try { localStorage.setItem(LANGUAGE_KEY, language); } catch (e) {}
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

        if (!button || !windowChat || !form || !sendButton || !input || !messages || !languageSelect) {
            console.warn("[Bitey] Widget DOM not ready.");
            return;
        }

        if (!window.bitey_ajax || !window.bitey_ajax.ajax_url || !window.bitey_ajax.nonce) {
            console.error("[Bitey] AJAX configuration is missing.");
            return;
        }

        window.__biteyInitialized = true;
        var language = getLanguage();
        if (!labels[language]) language = "auto";
        languageSelect.value = language;
        applyLabels(language);

        function applyLabels(lang) {
            var copy = labels[lang] || labels.auto;
            var welcome = messages.querySelector("[data-bitey-i18n='welcome']");
            if (welcome && messages.children.length === 1) welcome.textContent = copy.welcome;
            if (nameInput) nameInput.placeholder = copy.name;
            if (phoneInput) phoneInput.placeholder = copy.phone;
            input.placeholder = copy.message;
            sendButton.textContent = copy.send;
        }

        button.addEventListener("click", function () {
            var opening = windowChat.hidden;
            windowChat.hidden = !opening;
            button.setAttribute("aria-expanded", String(opening));
            if (opening) input.focus();
        });

        if (closeButton) {
            closeButton.addEventListener("click", function () {
                windowChat.hidden = true;
                button.setAttribute("aria-expanded", "false");
                button.focus();
            });
        }

        languageSelect.addEventListener("change", function () {
            language = languageSelect.value;
            setLanguage(language);
            applyLabels(language);
        });

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
            formData.append("name", nameInput ? nameInput.value.trim() : "Customer");
            formData.append("phone", phoneInput ? phoneInput.value.trim() : "");
            formData.append("company_id", window.bitey_ajax.company_id || 1);
            formData.append("channel", window.bitey_ajax.channel || "website");
            formData.append("conversation_id", getConversationId());
            formData.append("language_preference", language);

            fetch(window.bitey_ajax.ajax_url, {
                method: "POST",
                body: formData,
                credentials: "same-origin"
            })
                .then(function (response) {
                    return response.json().then(function (data) {
                        if (!response.ok || !data || !data.success) {
                            var messageError = data && data.data && data.data.reply ? data.data.reply : "HTTP " + response.status;
                            throw new Error(messageError);
                        }
                        return data;
                    });
                })
                .then(function (data) {
                    typing.remove();
                    var result = data.data || {};
                    if (result.conversation_id) {
                        try { localStorage.setItem(STORAGE_KEY, result.conversation_id); } catch (e) {}
                    }
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

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initBitey, { once: true });
    } else {
        initBitey();
    }
})();
