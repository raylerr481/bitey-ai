<?php
if (!defined('ABSPATH')) { exit; }

/** Enterprise profile/document administration. */
class Bitey_Company_Profile {
    public function __construct() { add_action('admin_menu', array($this,'menu')); }
    public function menu() {
        add_submenu_page('options-general.php','Bitey Company AI Profile','Bitey Company AI','manage_options','bitey-company-ai',array($this,'page'));
    }
    public function page() {
        $company_id = absint(get_option('bitey_company_id',1)) ?: 1;
        $website = 'https://www.bitefixes.com';
        $nonce = wp_create_nonce('bitey_nonce');
        ?>
        <div class="wrap">
            <h1>Bitey — Entorno vivo de la empresa</h1>
            <p>Bitey es la fachada/aprendiz. Las IAs externas son las rectoras intelectuales mientras Bitey demuestra madurez mediante evaluación. El perfil empresarial es el contexto común y versionado.</p>
            <table class="form-table">
                <tr><th>Company ID</th><td><code><?php echo esc_html($company_id); ?></code></td></tr>
                <tr><th>Sitio empresarial</th><td><input class="regular-text" id="bitey-profile-website" value="<?php echo esc_attr($website); ?>" /></td></tr>
                <tr><th>Empresa</th><td><input class="regular-text" id="bitey-profile-name" value="BiteFixes" /></td></tr>
                <tr><th>Contexto / directivas / objetivos</th><td><textarea class="large-text" rows="10" id="bitey-profile-description" placeholder="Describe objetivos, servicios, directivas, vocabulario, clientes, límites y forma de trabajo."></textarea></td></tr>
            </table>
            <p><button class="button button-primary" id="bitey-save-profile">Guardar y enviar al perfil empresarial</button></p>
            <hr>
            <h2>Documentos rectores</h2>
            <p>Los documentos no cambian silenciosamente la verdad empresarial. El backend los ingiere, compara y genera una propuesta/versionado según su autoridad.</p>
            <input type="file" id="bitey-admin-document" accept=".pdf,.doc,.docx,.txt,.csv,.json,.md">
            <button class="button" id="bitey-upload-profile-document">Enviar documento</button>
            <p id="bitey-profile-status" role="status"></p>
            <script>
            (function(){
                const nonce=<?php echo wp_json_encode($nonce); ?>, ajax=<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>, cid=<?php echo (int)$company_id; ?>;
                const status=document.getElementById('bitey-profile-status');
                document.getElementById('bitey-save-profile').addEventListener('click',function(){
                    const fd=new FormData(); fd.append('action','bitey_save_company_context'); fd.append('nonce',nonce); fd.append('company_id',cid); fd.append('company_name',document.getElementById('bitey-profile-name').value); fd.append('website',document.getElementById('bitey-profile-website').value); fd.append('description',document.getElementById('bitey-profile-description').value);
                    status.textContent='Guardando contexto…'; fetch(ajax,{method:'POST',body:fd,credentials:'same-origin'}).then(r=>r.json()).then(x=>{status.textContent=x.success?'Perfil enviado para procesamiento/versionado.':(x.data&&x.data.reply||'Error al guardar.');}).catch(()=>status.textContent='Error de conexión.');
                });
                document.getElementById('bitey-upload-profile-document').addEventListener('click',function(){
                    const f=document.getElementById('bitey-admin-document').files[0]; if(!f){status.textContent='Selecciona un documento.';return;}
                    const fd=new FormData(); fd.append('action','bitey_import_company'); fd.append('nonce',nonce); fd.append('company_id',cid); fd.append('company_name',document.getElementById('bitey-profile-name').value); fd.append('company_document',f);
                    status.textContent='Enviando documento…'; fetch(ajax,{method:'POST',body:fd,credentials:'same-origin'}).then(r=>r.json()).then(x=>{status.textContent=x.success?'Documento recibido por Bitey para ingestión.':(x.data&&x.data.reply||'Error al enviar.');}).catch(()=>status.textContent='Error de conexión.');
                });
            })();
            </script>
        </div>
        <?php
    }
}
