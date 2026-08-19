(function () {
    "use strict";
    function initBitey() {
        if (window.__biteyInitialized) return;
        const $ = id => document.getElementById(id);
        const button=$('bitey-button'), win=$('bitey-window'), close=$('bitey-close'), send=$('bitey-send'), input=$('bitey-input'), name=$('bitey-name'), phone=$('bitey-phone'), dataBox=$('bitey-user-data'), identify=$('bitey-identify'), messages=$('bitey-messages'), lang=$('bitey-language'), welcome=$('bitey-welcome');
        if (!button || !win || !send || !input || !messages) return;
        if (!window.bitey_ajax || !window.bitey_ajax.ajax_url || !window.bitey_ajax.nonce) return;
        window.__biteyInitialized=true;
        const KEY='bitey_session_v2';
        let state=JSON.parse(localStorage.getItem(KEY)||'null')||{identified:false,name:'',phone:'',language:'auto',conversation_id:''};
        if(state.identified){ dataBox.style.display='none'; }
        lang.value=state.language||'auto';
        const labels={
            es:{welcome:n=>n?`Hola ${n} 👋 Me alegra verte nuevamente. ¿Qué necesitas resolver hoy?`:'Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?',name:'Tu nombre',phone:'Teléfono / WhatsApp',continue:'Continuar',input:'Escribe tu mensaje...',typing:'Bitey está escribiendo...',error:'No se pudo conectar con Bitey Backend. Inténtalo nuevamente.'},
            'pt-BR':{welcome:n=>n?`Olá ${n} 👋 Que bom ver você novamente. Como posso ajudar hoje?`:'Olá 👋 sou Bitey. Como posso ajudar?',name:'Seu nome',phone:'Telefone / WhatsApp',continue:'Continuar',input:'Digite sua mensagem...',typing:'Bitey está digitando...',error:'Não foi possível conectar ao Bitey Backend. Tente novamente.'},
            en:{welcome:n=>n?`Hello ${n} 👋 Nice to see you again. How can I help today?`:'Hello 👋 I\'m Bitey. How can I help?',name:'Your name',phone:'Phone / WhatsApp',continue:'Continue',input:'Type your message...',typing:'Bitey is typing...',error:'Could not connect to Bitey Backend. Please try again.'}
        };
        function uiLanguage(){ return state.language==='auto'?'es':(state.language||'es'); }
        function refreshUI(){ const l=labels[uiLanguage()]; name.placeholder=l.name; phone.placeholder=l.phone; identify.textContent=l.continue; input.placeholder=l.input; if(!messages.querySelector('.bitey-user')) welcome.textContent=l.welcome(state.name); }
        function save(){localStorage.setItem(KEY,JSON.stringify(state));}
        refreshUI();
        lang.addEventListener('change',()=>{state.language=lang.value;save();refreshUI();});
        button.addEventListener('click',()=>{const open=win.style.display==='flex';win.style.display=open?'none':'flex';button.setAttribute('aria-expanded',String(!open));if(!open)input.focus();});
        if(close) close.addEventListener('click',()=>{win.style.display='none';button.setAttribute('aria-expanded','false');});
        identify.addEventListener('click',()=>{const n=name.value.trim(), p=phone.value.trim(); if(!n||!p){name.focus();return;} state.identified=true;state.name=n;state.phone=p;save();dataBox.style.display='none';welcome.textContent=labels[uiLanguage()].welcome(n);input.focus();});
        document.querySelectorAll('.bitey-quick-actions button').forEach(b=>b.addEventListener('click',()=>{input.value=b.dataset.message||'';sendMessage();}));
        input.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage();}}); send.addEventListener('click',sendMessage);
        function addMessage(text,cls){const d=document.createElement('div');d.className='bitey-message '+cls;d.textContent=String(text||'');messages.appendChild(d);messages.scrollTop=messages.scrollHeight;return d;}
        function sendMessage(){const message=input.value.trim();if(!message||send.disabled)return;if(!state.identified){dataBox.style.display='flex';name.focus();return;}addMessage(message,'bitey-user');input.value='';send.disabled=true;const typing=addMessage(labels[uiLanguage()].typing,'bitey-ai');const form=new FormData();form.append('action','bitey_send_message');form.append('message',message);form.append('nonce',window.bitey_ajax.nonce);form.append('name',state.name);form.append('phone',state.phone);form.append('company_id',window.bitey_ajax.company_id||1);form.append('channel',window.bitey_ajax.channel||'website');form.append('conversation_id',state.conversation_id||'');form.append('language_preference',state.language||'auto');fetch(window.bitey_ajax.ajax_url,{method:'POST',body:form,credentials:'same-origin'}).then(r=>{if(!r.ok)throw Error('HTTP '+r.status);return r.json();}).then(d=>{typing.remove();const x=d&&d.data?d.data:{};if(x.conversation_id)state.conversation_id=x.conversation_id;if(x.customer_id)state.customer_id=x.customer_id;if(x.customer_name)state.name=x.customer_name;save();addMessage(x.reply||labels[uiLanguage()].error,'bitey-ai');}).catch(()=>{typing.remove();addMessage(labels[uiLanguage()].error,'bitey-ai');}).finally(()=>{send.disabled=false;input.focus();});}
    }
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',initBitey,{once:true}); else initBitey();
})();