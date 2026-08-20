<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_Assets {
    public function __construct() { add_action('wp_enqueue_scripts', array($this, 'load_assets')); }
    public function load_assets() {
        $version = defined('BITEY_VERSION') ? BITEY_VERSION : '2.4.2';
        $style = BITEY_URL . 'assets/css/bitey-style.css';
        $script = BITEY_URL . 'assets/js/bitey.js';
        $style_file = defined('BITEY_PATH') ? BITEY_PATH . 'assets/css/bitey-style.css' : '';
        $script_file = defined('BITEY_PATH') ? BITEY_PATH . 'assets/js/bitey.js' : '';
        if ($style_file && file_exists($style_file)) $version .= '.' . filemtime($style_file);
        if ($script_file && file_exists($script_file)) $version .= '.' . filemtime($script_file);
        wp_enqueue_style('bitey-style', $style, array(), $version);
        wp_enqueue_script('bitey-script', $script, array(), $version, true);
        wp_localize_script('bitey-script', 'bitey_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => esc_url_raw(rest_url('bitey/v1/chat')),
            'nonce' => wp_create_nonce('bitey_nonce'),
            'rest_nonce' => wp_create_nonce('wp_rest'),
            'company_id' => absint(get_option('bitey_company_id', 1)) ?: 1,
            'channel' => 'website',
            'backend_url' => untrailingslashit((string) get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com')),
            'plugin_version' => $version,
            'process_labels' => array(
                'es' => array('understanding'=>'Entendiendo tu consulta…','analyzing'=>'Analizando posibles soluciones…','consulting'=>'Consultando conocimiento…','verifying'=>'Verificando la información…','preparing'=>'Preparando la mejor respuesta…'),
                'pt-BR' => array('understanding'=>'Entendendo sua consulta…','analyzing'=>'Analisando possíveis soluções…','consulting'=>'Consultando conhecimento…','verifying'=>'Verificando as informações…','preparing'=>'Preparando a melhor resposta…'),
                'en' => array('understanding'=>'Understanding your request…','analyzing'=>'Analyzing possible solutions…','consulting'=>'Consulting knowledge…','verifying'=>'Verifying information…','preparing'=>'Preparing the best answer…')
            )
        ));
    }
}