<?php
/*
Plugin Name: Bitey AI Assistant
Plugin URI: https://bitefixes.com
Description: Bitey AI enterprise assistant for WordPress, connected to the BiteFixes FastAPI engine.
Version: 2.0.0
Author: BiteFixes
Author URI: https://bitefixes.com
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

define('BITEY_VERSION', '2.0.0');
define('BITEY_PATH', plugin_dir_path(__FILE__));
define('BITEY_URL', plugin_dir_url(__FILE__));

define('BITEY_DEFAULT_BACKEND', 'https://bitefixes-backend.onrender.com');

require_once BITEY_PATH . 'includes/class-bitey-api.php';
require_once BITEY_PATH . 'includes/class-bitey-assets.php';
require_once BITEY_PATH . 'includes/class-bitey-widget.php';
require_once BITEY_PATH . 'includes/class-bitey-settings.php';
require_once BITEY_PATH . 'includes/class-bitey-rest.php';

function bitey_initialize() {
    static $initialized = false;
    if ($initialized) {
        return;
    }
    $initialized = true;

    new Bitey_API();
    new Bitey_Assets();
    new Bitey_Widget();
    new Bitey_AI_Settings();
    new Bitey_REST();
}

add_action('plugins_loaded', 'bitey_initialize');
