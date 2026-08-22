<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_AI_Settings {
    public function __construct() { add_action('admin_menu', array($this, 'add_menu')); add_action('admin_init', array($this, 'register_settings')); }
    public function add_menu() { add_options_page('Bitey AI Settings', 'Bitey AI', 'manage_options', 'bitey-ai-settings', array($this, 'settings_page')); }
    public function register_settings() {
        register_setting('bitey_ai_options', 'bitey_backend_url', array('sanitize_callback' => 'esc_url_raw'));
        register_setting('bitey_ai_options', 'bitey_company_id', array('sanitize_callback' => 'absint', 'default' => 1));
        register_setting('bitey_ai_options', 'bitey_channel_api_key', array('sanitize_callback' => 'sanitize_text_field'));
    }
    public function settings_page() {
        $backend_url = get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com');
        $company_id = absint(get_option('bitey_company_id', 1)) ?: 1;
        $channel_key = get_option('bitey_channel_api_key', '');
        ?>
        <div class="wrap">
            <h1>Bitey AI Configuration</h1>
            <p>Configure the Bitey API connection and the authenticated channel used for company-document ingestion.</p>
            <form method="post" action="options.php">
                <?php settings_fields('bitey_ai_options'); ?>
                <table class="form-table">
                    <tr><th scope="row"><label for="bitey_backend_url">FastAPI Backend URL</label></th><td><input id="bitey_backend_url" type="url" name="bitey_backend_url" value="<?php echo esc_attr($backend_url); ?>" size="60" /><p class="description">Example: https://bitefixes-backend.onrender.com</p></td></tr>
                    <tr><th scope="row"><label for="bitey_company_id">Company ID</label></th><td><input id="bitey_company_id" type="number" min="1" name="bitey_company_id" value="<?php echo esc_attr($company_id); ?>" /><p class="description">Tenant/company identifier sent to Bitey Backend.</p></td></tr>
                    <tr><th scope="row"><label for="bitey_channel_api_key">Channel API Key</label></th><td><input id="bitey_channel_api_key" type="password" name="bitey_channel_api_key" value="<?php echo esc_attr($channel_key); ?>" size="60" autocomplete="new-password" /><p class="description">Must match <code>BITEY_CHANNEL_API_KEY</code> in the backend. Never expose this key in browser JavaScript.</p></td></tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
