<?php
if (!defined('ABSPATH')) { exit; }
?>
<button id="bitey-button" type="button" aria-expanded="false">💬 Bitey AI</button>
<div id="bitey-window" hidden>
  <div id="bitey-header">
    <span>🤖 Bitey AI Assistant</span>
    <button id="bitey-close" type="button" aria-label="Cerrar">✕</button>
  </div>
  <div id="bitey-messages">
    <div id="bitey-welcome" class="bitey-message bot">Hola 👋 soy Bitey. ¿Cómo puedo ayudarte?</div>
  </div>
  <div id="bitey-attachment-status" class="bitey-attachment-status" hidden></div>
  <div id="bitey-input-area">
    <input type="text" id="bitey-input" placeholder="Describe tu problema técnico..." autocomplete="off">
    <label id="bitey-attach" class="bitey-attach" for="bitey-file" title="Adjuntar documento o PDF">📎</label>
    <input type="file" id="bitey-file" accept=".pdf,.doc,.docx,.txt,.csv,.json,.md,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain,text/csv,application/json,text/markdown" hidden>
    <button id="bitey-send" type="button">Enviar</button>
  </div>
  <div id="bitey-work" hidden><span id="bitey-work-icon">🧠</span> <span id="bitey-work-label">Analizando…</span></div>
</div>
