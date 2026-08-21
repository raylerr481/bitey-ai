(function(){'use strict';
function init(){
 const file=document.getElementById('bitey-file'), send=document.getElementById('bitey-send'), input=document.getElementById('bitey-input'), messages=document.getElementById('bitey-messages'), status=document.getElementById('bitey-attachment-status');
 const cfg=window.bitey_ajax||{}; if(!file||!send||!input||!messages||!cfg.ajax_url)return;
 let selected=null;
 const add=(text,cls)=>{const d=document.createElement('div');d.className='bitey-message '+cls;d.textContent=text;messages.appendChild(d);messages.scrollTop=messages.scrollHeight;};
 file.addEventListener('change',function(){selected=file.files&&file.files[0]?file.files[0]:null;if(!selected){status.hidden=true;return;}status.textContent='📎 '+selected.name+' listo para analizar';status.hidden=false;});
 send.addEventListener('click',async function(e){if(!selected)return;e.preventDefault();e.stopImmediatePropagation();const text=input.value.trim()||'Analiza este documento y crea/actualiza el perfil de la empresa y sus servicios.';if(selected.size>10*1024*1024){add('El archivo supera el límite de 10 MB.','bitey-error');return;}send.disabled=true;add(text,'bitey-user');add('📄 Analizando documento empresarial y construyendo el contexto…','bitey-process');
 const fd=new FormData();fd.append('action','bitey_send_message');fd.append('nonce',cfg.nonce||'');fd.append('message',text);fd.append('company_id',cfg.company_id||1);fd.append('channel',cfg.channel||'website');fd.append('conversation_id',localStorage.getItem('bitey_session_v14')||'');fd.append('language_preference','auto');fd.append('preferred_contact_channel','web');fd.append('file',selected,selected.name);
 try{const r=await fetch(cfg.ajax_url,{method:'POST',body:fd,credentials:'same-origin',cache:'no-store'});const d=await r.json();if(!r.ok||!d.success)throw new Error(d?.data?.reply||'No se pudo procesar el documento.');const x=d.data||{};add(x.reply||'Documento procesado y contexto empresarial actualizado.','bitey-ai');if(x.company_profile)add('🏢 Perfil empresarial: registrado/actualizado.','bitey-process');if(x.learning_candidate)add('🧠 Conocimiento candidato: registrado para entrenamiento de Bitey.','bitey-process');}catch(err){add('[attachment_error] '+err.message,'bitey-error');}finally{send.disabled=false;selected=null;file.value='';status.hidden=true;input.focus();}},true);
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});else init();
})();
