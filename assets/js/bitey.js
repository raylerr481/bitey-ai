(function () {
    'use strict';
    function initBitey() {
        if (window.__biteyInitialized) return;
        const $=id=>document.getElementById(id);
        const button=$('bitey-button'),win=$('bitey-window'),close=$('bitey-close'),send=$('bitey-send'),input=$('bitey-input'),messages=$('bitey-messages'),welcome=$('bitey-welcome');
        const languageSelect=$('bitey-language'),work=$('bitey-work'),workLabel=$('bitey-work-label'),memoryBadge=$('bitey-memory');
        if(!button||!win||!send||!input||!messages)return; window.__biteyInitialized=true;
        const cfg=window.bitey_ajax||{},KEY='bitey_session_v10';
        let state={name:'',last_name:'',phone:'',email:'',language:'auto',detected_language:'',conversation_id:'',customer_id:'',preferred_contact_channel:'web',memory_messages:0};
        try{state=Object.assign(state,JSON.parse(localStorage.getItem(KEY)||'{}'));}catch(e){}
        const ui={es:{welcome:'Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?',input:'Escribe tu mensaje...',memory:'Memoria activa.'},'pt-BR':{welcome:'Olá 👋 sou o Bitey. Como posso ajudar?',input:'Digite sua mensagem...',memory:'Memória ativa.'},en:{welcome:"Hello 👋 I'm Bitey. How can I help?",input:'Type your message...',memory:'Memory active.'}};
        const save=()=>{try{localStorage.setItem(KEY,JSON.stringify(state));}catch(e){}};
        const lang=()=>ui[state.language]&&state.language!=='auto'?state.language:(ui[state.detected_language]?state.detected_language:'es');
        function refresh(){const l=ui[lang()];input.placeholder=l.input;if(!messages.querySelector('.bitey-user')&&welcome)welcome.textContent=l.welcome;if(languageSelect)languageSelect.value=state.language||'auto';if(memoryBadge){memoryBadge.textContent=Number(state.memory_messages||0)>0?'●':'•';memoryBadge.title=Number(state.memory_messages||0)>0?l.memory:'Memoria de esta conversación';}}
        function startWork(){if(work){work.hidden=false;if(workLabel)workLabel.textContent='Bitey está entendiendo…';}}
        function stopWork(){if(work)work.hidden=true;}
        function addMessage(text,cls){const d=document.createElement('div');d.className='bitey-message '+cls;d.textContent=String(text||'');messages.appendChild(d);messages.scrollTop=messages.scrollHeight;}
        function diagnostic(code,detail,status){const labels={config_missing:'Configuración incompleta',rest_non_json:'La API REST de WordPress devolvió contenido no JSON',rest_http_error:'La API REST de WordPress devolvió un error HTTP',ajax_http_error:'WordPress AJAX devolvió un error HTTP',ajax_non_json:'WordPress AJAX devolvió HTML en lugar de JSON',backend_unreachable:'No se pudo alcanzar Bitey Backend',backend_http_error:'Bitey Backend devolvió un error HTTP',backend_non_json:'Bitey Backend devolvió una respuesta inválida',backend_empty:'Bitey no devolvió una respuesta',network_error:'Error de red'};addMessage('['+(code||'communication_error')+'] '+(labels[code]||'Error de comunicación')+(detail?' — '+detail:'')+(status?' (HTTP '+status+')':''),'bitey-ai bitey-error');if(window.console&&console.error)console.error('[Bitey]',{code,detail,status,cfg});}
        button.addEventListener('click',()=>{const opening=win.hidden;win.hidden=!opening;button.setAttribute('aria-expanded',String(opening));if(opening)input.focus();});
        if(close)close.addEventListener('click',()=>{win.hidden=true;button.setAttribute('aria-expanded','false');});
        if(languageSelect)languageSelect.addEventListener('change',()=>{state.language=languageSelect.value;save();refresh();});
        input.addEventListener('keydown',e=>{if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage();}});send.addEventListener('click',sendMessage);
        async function transport(payload){
            if(cfg.rest_url){
                const r=await fetch(cfg.rest_url,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(payload),credentials:'same-origin',cache:'no-store'});
                const raw=await r.text();let d;try{d=JSON.parse(raw);}catch(e){throw Object.assign(new Error(raw.slice(0,160)),{code:'rest_non_json',status:r.status});}
                if(!r.ok||!d.success)throw Object.assign(new Error(d?.data?.reply||d?.message||'REST request failed'),{code:d?.data?.code||'rest_http_error',status:r.status});
                return d;
            }
            if(!cfg.ajax_url||!cfg.nonce)throw Object.assign(new Error('Falta transporte de Bitey'),{code:'config_missing'});
            const form=new FormData();Object.keys(payload).forEach(k=>form.append(k,payload[k]??''));form.append('action','bitey_send_message');form.append('nonce',cfg.nonce);
            const r=await fetch(cfg.ajax_url,{method:'POST',body:form,credentials:'same-origin',cache:'no-store'});const raw=await r.text();let d;try{d=JSON.parse(raw);}catch(e){throw Object.assign(new Error(raw.slice(0,160)),{code:'ajax_non_json',status:r.status});}if(!r.ok||!d.success)throw Object.assign(new Error(d?.data?.reply||'AJAX failed'),{code:d?.data?.code||'ajax_http_error',status:r.status});return d;
        }
        async function sendMessage(){
            const message=input.value.trim();if(!message||send.disabled)return;
            if(!cfg.rest_url&&!cfg.ajax_url){diagnostic('config_missing','Falta transporte de Bitey.');return;}
            addMessage(message,'bitey-user');input.value='';send.disabled=true;startWork();
            const payload={message,name:state.name,last_name:state.last_name,phone:state.phone,email:state.email,company_id:cfg.company_id||1,channel:cfg.channel||'website',conversation_id:state.conversation_id||'',language_preference:state.language||'auto',preferred_contact_channel:state.preferred_contact_channel||'web'};
            try{const d=await transport(payload),x=d.data||{};if(x.conversation_id)state.conversation_id=x.conversation_id;if(x.customer_id)state.customer_id=x.customer_id;if(x.customer_name)state.name=x.customer_name;if(x.language)state.detected_language=x.language;if(x.preferred_contact_channel)state.preferred_contact_channel=x.preferred_contact_channel;if(x.memory)state.memory_messages=Number(x.memory.messages||0);save();refresh();if(x.reply||x.response)addMessage(x.reply||x.response,'bitey-ai');else diagnostic('backend_empty','La respuesta no contiene reply/response');}catch(err){diagnostic(err.code||'network_error',err.message||'fetch falló',err.status);}finally{stopWork();send.disabled=false;input.focus();}
        }
        refresh();
    }
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initBitey,{once:true});else initBitey();
})();
