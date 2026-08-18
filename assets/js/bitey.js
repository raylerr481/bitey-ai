document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("bitey-button");
    const windowChat = document.getElementById("bitey-window");
    const closeButton = document.getElementById("bitey-close");
    const sendButton = document.getElementById("bitey-send");
    const input = document.getElementById("bitey-input");
    const nameInput = document.getElementById("bitey-name");
    const phoneInput = document.getElementById("bitey-phone");
    const messages = document.getElementById("bitey-messages");

    if (!button || !windowChat || !sendButton || !input || !messages) {
        console.error("[Bitey] Widget markup is incomplete.");
        return;
    }

    function setOpen(open) {
        windowChat.hidden = !open;
        windowChat.style.display = open ? "flex" : "none";
        button.setAttribute("aria-expanded", open ? "true" : "false");
        if (open) {
            input.focus();
        }
    }

    button.addEventListener("click", function () {
        setOpen(windowChat.hidden);
    });

    if (closeButton) {
        closeButton.addEventListener("click", function () {
            setOpen(false);
            button.focus();
        });
    }

    function addMessage(text, sender) {
        const div = document.createElement("div");
        div.className = sender;
        div.textContent = String(text || "");
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    function showTyping() {
        const existing = document.getElementById("bitey-typing");
        if (existing) {
            return existing;
        }

        const div = document.createElement("div");
        div.id = "bitey-typing";
        div.className = "bitey-ai";
        div.textContent = "Bitey está pensando...";
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
        return div;
    }

    function hideTyping() {
        const typing = document.getElementById("bitey-typing");
        if (typing) {
            typing.remove();
        }
    }

    function visitorId() {
        const key = "bitey_visitor_id";
        let id = window.localStorage ? localStorage.getItem(key) : null;
        if (!id && window.crypto && typeof window.crypto.randomUUID === "function") {
            id = "web-" + window.crypto.randomUUID();
        }
        if (!id) {
            id = "web-" + Math.random().toString(36).slice(2) + Date.now().toString(36);
        }
        try {
            localStorage.setItem(key, id);
        } catch (e) {
            // Private browsing may block localStorage; the generated ID is still usable.
        }
        return id;
    }

    function sendMessage() {
        const message = input.value.trim();
        if (!message || !window.bitey_ajax || !bitey_ajax.ajax_url) {
            return;
        }

        addMessage(message, "bitey-user");
        input.value = "";
        input.disabled = true;
        sendButton.disabled = true;
        showTyping();

        const form = new FormData();
        form.append("action", "bitey_send_message");
        form.append("nonce", bitey_ajax.nonce || "");
        form.append("message", message);
        form.append("name", nameInput ? nameInput.value.trim() : "Visitor");
        form.append("phone", phoneInput && phoneInput.value.trim() ? phoneInput.value.trim() : visitorId());
        form.append("company_id", bitey_ajax.company_id || "1");
        form.append("channel", bitey_ajax.channel || "website");

        fetch(bitey_ajax.ajax_url, {
            method: "POST",
            body: form,
            credentials: "same-origin"
        })
        .then(function (response) {
            return response.json().catch(function () {
                return { success: false, data: { reply: "Respuesta inválida del servidor." } };
            });
        })
        .then(function (data) {
            hideTyping();
            if (data && data.success) {
                addMessage(data.data && data.data.reply ? data.data.reply : "Bitey no devolvió una respuesta.", "bitey-ai");
            } else {
                const reply = data && data.data && data.data.reply ? data.data.reply : "Bitey no pudo procesar la solicitud.";
                addMessage(reply, "bitey-ai");
            }
        })
        .catch(function (error) {
            hideTyping();
            addMessage("No se pudo conectar con Bitey. Inténtalo de nuevo en unos segundos.", "bitey-ai");
            console.error("[Bitey]", error);
        })
        .finally(function () {
            input.disabled = false;
            sendButton.disabled = false;
            input.focus();
        });
    }

    sendButton.addEventListener("click", sendMessage);

    input.addEventListener("keydown", function (event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    });
});
