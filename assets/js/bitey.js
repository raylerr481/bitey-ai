(function () {
    'use strict';
    function initBitey() {
        if (window.__biteyInitialized) return;
        const $ = id => document.getElementById(id);
        const button = $('bitey-button'), win = $('bitey-window'), close = $('bitey-close'), send = $('bitey-send'), input = $('bitey-input'), messages = $('bitey-messages'), welcome = $('bitey-welcome');
        const work = $('bitey-work'), workLabel = $('bitey-work-label'), memoryBadge = $('bitey-memory');
        if (!button || !win || !send || !input || !messages) return;
        if (!window.bitey_ajax || !window.bitey_ajax.ajax_url || !window.bitey_ajax.nonce) return;
        window.__biteyInitialized = true;

        const KEY = 'bitey_session_v5';
        let state = { name: '', phone: '', language: 'auto', detected_language: '', conversation_id: '', customer_id: '', memory_messages: 0 };
        try { state = Object.assign(state, JSON.parse(localStorage.getItem(KEY) || '{}')); } catch (e) {}

        const ui = {
            es: { welcome: 'Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?', input: 'Escribe tu mensaje...', steps: ['Entendiendo tu solicitud…', 'Revisando tu contexto…', 'Buscando una respuesta…', 'Preparando la mejor respuesta…'], error: 'No pude conectar con Bitey. Inténtalo nuevamente.', memory: 'Bitey recuerda el contexto de esta conversación.' },
            'pt-BR': { welcome: 'Olá 👋 sou o Bitey. Como posso ajudar?', input: 'Digite sua mensagem...', steps: ['Entendendo sua solicitação…', 'Revisando seu contexto…', 'Buscando uma resposta…', 'Preparando a melhor resposta…'], error: 'Não consegui conectar ao Bitey. Tente novamente.', memory: 'Bitey lembra o contexto desta conversa.' },
            en: { welcome: "Hello 👋 I'm Bitey. How can I help?", input: 'Type your message...', steps: ['Understanding your request…', 'Reviewing your context…', 'Finding an answer…', 'Preparing the best response…'], error: 'I could not connect to Bitey. Please try again.', memory: 'Bitey remembers the context of this conversation.' }
        };

        let stepTimer = null;
        function save() { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {} }
        function language() { return ui[state.detected_language] ? state.detected_language : 'es'; }
        function refresh() {
            const l = ui[language()];
            input.placeholder = l.input;
            if (!messages.querySelector('.bitey-user')) welcome.textContent = l.welcome;
            if (memoryBadge) {
                const hasMemory = Number(state.memory_messages || 0) > 0;
                memoryBadge.classList.toggle('is-active', hasMemory);
                memoryBadge.textContent = hasMemory ? '●' : '•';
                memoryBadge.title = hasMemory ? l.memory : 'Memoria activa cuando existe historial del usuario.';
                memoryBadge.setAttribute('aria-label', memoryBadge.title);
            }
        }
        function startWork() {
            if (!work || !workLabel) return;
            const steps = ui[language()].steps;
            let index = 0;
            work.hidden = false;
            workLabel.textContent = steps[index];
            stepTimer = window.setInterval(() => {
                index = Math.min(index + 1, steps.length - 1);
                workLabel.textContent = steps[index];
            }, 900);
        }
        function stopWork() {
            if (stepTimer) { window.clearInterval(stepTimer); stepTimer = null; }
            if (work) work.hidden = true;
        }
        refresh();

        button.addEventListener('click', () => {
            const open = win.style.display === 'flex';
            win.style.display = open ? 'none' : 'flex';
            button.setAttribute('aria-expanded', String(!open));
            if (!open) input.focus();
        });
        if (close) close.addEventListener('click', () => { win.style.display = 'none'; button.setAttribute('aria-expanded', 'false'); });
        input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });
        send.addEventListener('click', sendMessage);

        function addMessage(text, cls) {
            const d = document.createElement('div');
            d.className = 'bitey-message ' + cls;
            d.textContent = String(text || '');
            messages.appendChild(d);
            messages.scrollTop = messages.scrollHeight;
            return d;
        }

        function sendMessage() {
            const message = input.value.trim();
            if (!message || send.disabled) return;
            addMessage(message, 'bitey-user');
            input.value = '';
            send.disabled = true;
            startWork();
            const form = new FormData();
            form.append('action', 'bitey_send_message');
            form.append('message', message);
            form.append('nonce', window.bitey_ajax.nonce);
            form.append('name', state.name || '');
            form.append('phone', state.phone || '');
            form.append('company_id', window.bitey_ajax.company_id || 1);
            form.append('channel', window.bitey_ajax.channel || 'website');
            form.append('conversation_id', state.conversation_id || '');
            form.append('language_preference', 'auto');

            fetch(window.bitey_ajax.ajax_url, { method: 'POST', body: form, credentials: 'same-origin' })
                .then(r => { if (!r.ok) throw Error('HTTP ' + r.status); return r.json(); })
                .then(d => {
                    stopWork();
                    const x = d && d.data ? d.data : {};
                    if (x.conversation_id) state.conversation_id = x.conversation_id;
                    if (x.customer_id) state.customer_id = x.customer_id;
                    if (x.customer_name) state.name = x.customer_name;
                    if (x.phone) state.phone = x.phone;
                    if (x.language) state.detected_language = x.language;
                    if (x.memory) state.memory_messages = Number(x.memory.messages || 0);
                    save();
                    refresh();
                    addMessage(x.reply || x.response || ui[language()].error, 'bitey-ai');
                })
                .catch(() => { stopWork(); addMessage(ui[language()].error, 'bitey-ai'); })
                .finally(() => { send.disabled = false; input.focus(); });
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initBitey, { once: true }); else initBitey();
})();
