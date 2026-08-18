<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_Assets {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
    }

    public function enqueue() {
        if (is_admin()) {
            return;
        }

        wp_enqueue_style(
            'bitey-ai',
            BITEY_URL . 'assets/css/bitey.css',
            array(),
            BITEY_VERSION
        );

        wp_enqueue_script(
            'bitey-ai',
            BITEY_URL . 'assets/js/bitey.js',
            array(),
            BITEY_VERSION,
            true
        );

        wp_localize_script('bitey-ai', 'bitey_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bitey_nonce'),
        ));
    }
}
