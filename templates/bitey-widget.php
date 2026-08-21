<?php
if (!defined('ABSPATH')) { exit; }
?>
<button id="bitey-button" type="button" aria-expanded="false">💬 Bitey AI</button>
<div id="bitey-window" hidden>
  <div id="bitey-header"><span>🤖 Bitey AI Assistant</span><button id="bitey-close" type="button" aria-label="Cerrar">✕</button></div>
  <div id="bitey-messages"><div id="bitey-welcome" class="bitey-message bot">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div></div>
  <div id="bitey-attachment-status" class="bitey-attachment-status" hidden></div>
  <div id="bitey-input-area">
    <input type="text" id="bitey-input" placeholder="Describe tu problema técnico..." autocomplete="off">
    <label id="bitey-attach" class="bitey-attach" for="bitey-file" title="Adjuntar documento o PDF">📎</label>
    <input type="file" id="bitey-file" accept=".pdf,.doc,.docx,.txt,.csv,.json,.md,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain,text/csv,application/json,text/markdown" hidden>
    <button id="bitey-send" type="button">Enviar</button>
  </div>
  <div id="bitey-work" hidden><span id="bitey-work-icon">🧠</span> <span id="bitey-work-label">Analizando…</span></div>
</div>
<script>
(function(){
'use strict';
function biteyAttachment(){
 const f=document.getElementById('bitey-file'),b=document.getElementById('bitey-send'),i=document.getElementById('bitey-input'),m=document.getElementById('bitey-messages'),s=document.getElementById('bitey-attachment-status'),c=window.bitey_ajax||{}; if(!f||!b||!i||!m||!c.ajax_url)return;
 let selected=null; const add=(t,k)=>{const d=document.createElement('div');d.className='bitey-message '+k;d.textContent=t;m.appendChild(d);m.scrollTop=m.scrollHeight;};
 f.addEventListener('change',()=>{selected=f.files&&f.files[0]?f.files[0]:null;s.textContent=selected?'📎 '+selected.name+' listo para analizar':'';s.hidden=!selected;});
 b.addEventListener('click',async e=>{if(!selected)return;e.preventDefault();e.stopImmediatePropagation();if(selected.size>10485760){add('El archivo supera el límite de 10 MB.','bitey-error');return;}const text=i.value.trim()||'Analiza este documento y crea o actualiza el perfil de la empresa y sus servicios.';b.disabled=true;add(text,'bitey-user');add('📄 Analizando documento, empresa, servicios y contexto…','bitey-process');const fd=new FormData();fd.append('action','bitey_send_message');fd.append('nonce',c.nonce||'');fd.append('message',text);fd.append('company_id',c.company_id||1);fd.append('channel',c.channel||'website');fd.append('conversation_id',localStorage.getItem('bitey_session_v14')||'');fd.append('language_preference','auto');fd.append('preferred_contact_channel','web');fd.append('file',selected,selected.name);try{const r=await fetch(c.ajax_url,{method:'POST',body:fd,credentials:'same-origin',cache:'no-store'});const d=await r.json();if(!r.ok||!d.success)throw new Error(d?.data?.reply||'No se pudo procesar el documento.');const x=d.data||{};add(x.reply||'Documento procesado y contexto empresarial actualizado.','bitey-ai');if(x.company_profile)add('🏢 Perfil empresarial registrado/actualizado.','bitey-process');if(x.learning_candidate)add('🧠 Conocimiento candidato registrado para el desarrollo de Bitey.','bitey-process');}catch(err){add('[attachment_error] '+err.message,'bitey-error');}finally{b.disabled=false;selected=null;f.value='';s.hidden=true;i.focus();}},true);
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',biteyAttachment,{once:true});else biteyAttachment();
})();
</script>
