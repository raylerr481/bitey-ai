(function () {
    'use strict';

    function initBitey() {
        if (window.__biteyInitialized) return;
        const $ = id => document.getElementById(id);
        const button = $('bitey-button'), win = $('bitey-window'), close = $('bitey-close');
        const send = $('bitey-send'), input = $('bitey-input'), messages = $('bitey-messages'), welcome = $('bitey-welcome');
        const languageSelect = $('bitey-language'), work = $('bitey-work'), workLabel = $('bitey-work-label'), memoryBadge = $('bitey-memory');
        if (!button || !win || !send || !input || !messages) return;
        window.__biteyInitialized = true;

        const cfg = window.bitey_ajax || {};
        const KEY = 'bitey_session_v9';
        let state = {name:'', phone:'', language:'auto', detected_language:'', conversation_id:'', customer_id:'', memory_messages:0};
        try { state = Object.assign(state, JSON.parse(localStorage.getItem(KEY) || '{}')); } catch (e) {}

        const ui = {
            es: {welcome:'Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?', input:'Escribe tu mensaje...', memory:'Memoria activa.'},
            'pt-BR': {welcome:'Olá 👋 sou o Bitey. Como posso ajudar?', input:'Digite sua mensagem...', memory:'Memória ativa.'},
            en: {welcome:"Hello 👋 I'm Bitey. How can I help?", input:'Type your message...', memory:'Memory active.'}
        };
        let stepTimer = null;
        const save=()=>{try{localStorage.setItem(KEY,JSON.stringify(state));}catch(e){}};
        const lang=()=>ui[state.language]&&state.language!=='auto'?state.language:(ui[state.detected_language]?state.detected_language:'es');
        function refresh(){const l=ui[lang()];input.placeholder=l.input;if(!messages.querySelector('.bitey-user')&&welcome)welcome.textContent=l.welcome;if(languageSelect)languageSelect.value=state.language||'auto';if(memoryBadge){memoryBadge.textContent=Number(state.memory_messages||0)>0?'●':'•';memoryBadge.title=Number(state.memory_messages||0)>0?l.memory:'Memoria de esta conversación';}}
        function startWork(){if(!work||!workLabel)return;work.hidden=false;workLabel.textContent='Bitey está entendiendo…';stepTimer=setInterval(()=>{workLabel.textContent='Bitey está preparando la respuesta…';},1200);}
        function stopWork(){if(stepTimer){clearInterval(stepTimer);stepTimer=null;}if(work)work.hidden=true;}
        function addMessage(text,cls){const d=document.createElement('div');d.className='bitey-message '+cls;d.textContent=String(text||'');messages.appendChild(d);messages.scrollTop=messages.scrollHeight;return d;}
        function showDiagnostic(code,detail,httpStatus){const labels={config_missing:'Configuração do plugin incompleta',invalid_nonce:'Sessão de segurança do WordPress inválida',ajax_http_error:'WordPress AJAX retornou erro HTTP',ajax_non_json:'WordPress AJAX não retornou JSON',backend_unreachable:'WordPress não conseguiu alcançar o backend',backend_http_error:'Bitey Backend retornou erro HTTP',backend_non_json:'Bitey Backend retornou resposta inválida',backend_empty:'Bitey Backend não retornou uma resposta',network_error:'Falha de rede ou bloqueio do navegador'};addMessage('['+(code||'communication_error')+'] '+(labels[code]||'Falha de comunicação com Bitey')+(detail?' — '+detail:'')+(httpStatus?' (HTTP '+httpStatus+')':''),'bitey-ai bitey-error');if(window.console&&console.error)console.error('[Bitey diagnostic]',{code,detail,httpStatus,ajax_url:cfg.ajax_url||null,backend_url:cfg.backend_url||null});}
        button.addEventListener('click',()=>{const opening=win.hidden;win.hidden=!opening;button.setAttribute('aria-expanded',String(opening));if(opening)input.focus();});
        if(close)close.addEventListener('click',()=>{win.hidden=true;button.setAttribute('aria-expanded','false');});
        if(languageSelect)languageSelect.addEventListener('change',()=>{state.language=languageSelect.value;save();refresh();});
        input.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage();}});send.addEventListener('click',sendMessage);

        async function sendMessage(){
            const message=input.value.trim();if(!message||send.disabled)return;
            if(!cfg.ajax_url||!cfg.nonce){showDiagnostic('config_missing','Falta ajax_url o nonce. Actualiza/reinstala el plugin y limpia la caché.');return;}
            addMessage(message,'bitey-user');input.value='';send.disabled=true;startWork();
            const form=new FormData();form.append('action','bitey_send_message');form.append('message',message);form.append('nonce',cfg.nonce);form.append('name',state.name);form.append('phone',state.phone);form.append('company_id',cfg.company_id||1);form.append('channel',cfg.channel||'website');form.append('conversation_id',state.conversation_id||'');form.append('language_preference',state.language||'auto');
            try{const r=await fetch(cfg.ajax_url,{method:'POST',body:form,credentials:'same-origin',cache:'no-store'});const raw=await r.text();let d;try{d=JSON.parse(raw);}catch(e){showDiagnostic('ajax_non_json',raw.slice(0,160),r.status);return;}if(!r.ok){showDiagnostic('ajax_http_error',d?.data?.code||d?.data?.reply||d?.data?.message||'Respuesta HTTP no válida',r.status);return;}if(!d?.success){showDiagnostic(d?.data?.code||'ajax_http_error',d?.data?.reply||d?.data?.message||'WordPress rechazó la solicitud',r.status);return;}const x=d.data||{};if(x.conversation_id)state.conversation_id=x.conversation_id;if(x.customer_id)state.customer_id=x.customer_id;if(x.customer_name)state.name=x.customer_name;if(x.language)state.detected_language=x.language;if(x.memory)state.memory_messages=Number(x.memory.messages||0);save();refresh();if(x.reply||x.response)addMessage(x.reply||x.response,'bitey-ai');else showDiagnostic('backend_empty','La respuesta no contiene reply/response',r.status);}catch(err){showDiagnostic('network_error',err?.message||'fetch falló');}finally{stopWork();send.disabled=false;input.focus();}
        }
        refresh();
    }
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initBitey,{once:true});else initBitey();
})();
