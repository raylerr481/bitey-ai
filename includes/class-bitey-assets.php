<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_Assets {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));
    }

    public function load_assets() {
        wp_enqueue_style('bitey-style', BITEY_URL . 'assets/css/bitey-style.css', array(), BITEY_VERSION);
        wp_enqueue_script('bitey-script', BITEY_URL . 'assets/js/bitey.js', array(), BITEY_VERSION, true);

        wp_localize_script('bitey-script', 'bitey_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bitey_nonce'),
            'company_id' => absint(get_option('bitey_company_id', 1)) ?: 1,
            'channel' => 'website',
            'version' => BITEY_VERSION,
        ));
    }
}
