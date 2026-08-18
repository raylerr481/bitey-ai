<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_AI_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_menu() {
        add_options_page('Bitey AI Settings', 'Bitey AI', 'manage_options', 'bitey-ai-settings', array($this, 'settings_page'));
    }

    public function register_settings() {
        register_setting('bitey_ai_options', 'bitey_backend_url', array('sanitize_callback' => 'esc_url_raw', 'default' => BITEY_DEFAULT_BACKEND));
        register_setting('bitey_ai_options', 'bitey_company_id', array('sanitize_callback' => 'absint', 'default' => 1));
    }

    public function settings_page() {
        $backend_url = get_option('bitey_backend_url', BITEY_DEFAULT_BACKEND);
        $company_id = absint(get_option('bitey_company_id', 1)) ?: 1;
        ?>
        <div class="wrap">
            <h1>Bitey AI 2.0</h1>
            <p>Configuración del asistente empresarial conectado al Bitey Core.</p>
            <form method="post" action="options.php">
                <?php settings_fields('bitey_ai_options'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="bitey_backend_url">FastAPI Backend URL</label></th>
                        <td>
                            <input id="bitey_backend_url" type="url" name="bitey_backend_url" value="<?php echo esc_attr($backend_url); ?>" class="regular-text" />
                            <p class="description">Backend principal de Bitey. Ejemplo: https://bitefixes-backend.onrender.com</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bitey_company_id">Company / Tenant ID</label></th>
                        <td>
                            <input id="bitey_company_id" type="number" min="1" name="bitey_company_id" value="<?php echo esc_attr($company_id); ?>" />
                            <p class="description">Identificador de la empresa/tenant que recibe el contexto operativo.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Guardar configuración'); ?>
            </form>
        </div>
        <?php
    }
}
