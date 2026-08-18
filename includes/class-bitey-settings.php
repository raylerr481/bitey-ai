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
        add_options_page(
            'Bitey AI Settings',
            'Bitey AI',
            'manage_options',
            'bitey-ai-settings',
            array($this, 'settings_page')
        );
    }

    public function register_settings() {
        register_setting('bitey_ai_options', 'bitey_backend_url', array(
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => 'https://bitefixes-backend.onrender.com',
        ));

        register_setting('bitey_ai_options', 'bitey_company_id', array(
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 1,
        ));
    }

    public function settings_page() {
        $backend_url = get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com');
        $company_id = (int) get_option('bitey_company_id', 1);
        ?>
        <div class="wrap">
            <h1>Bitey AI Configuration</h1>
            <p>Configura la conexión entre el widget de WordPress y Bitey Core.</p>

            <form method="post" action="options.php">
                <?php settings_fields('bitey_ai_options'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="bitey_backend_url">FastAPI Backend URL</label></th>
                        <td>
                            <input id="bitey_backend_url" type="url" name="bitey_backend_url" value="<?php echo esc_attr($backend_url); ?>" class="regular-text" />
                            <p class="description">Ejemplo: https://bitefixes-backend.onrender.com</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bitey_company_id">Company ID</label></th>
                        <td>
                            <input id="bitey_company_id" type="number" min="1" name="bitey_company_id" value="<?php echo esc_attr($company_id); ?>" class="small-text" />
                            <p class="description">ID de la empresa en Supabase. BiteFixes actualmente usa 1.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save Bitey Settings'); ?>
            </form>
        </div>
        <?php
    }
}
