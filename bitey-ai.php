<?php
/*
Plugin Name: Bitey AI Assistant
Plugin URI: https://bitefixes.com
Description: Asistente IA conectado con Bitey Core FastAPI.
Version: 1.2.0
Author: BiteFixes
Author URI: https://bitefixes.com
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit;
}

define('BITEY_VERSION', '1.2.0');
define('BITEY_PATH', plugin_dir_path(__FILE__));
define('BITEY_URL', plugin_dir_url(__FILE__));

require_once BITEY_PATH . 'includes/class-bitey-api.php';
require_once BITEY_PATH . 'includes/class-bitey-assets.php';
require_once BITEY_PATH . 'includes/class-bitey-widget.php';
require_once BITEY_PATH . 'includes/class-bitey-settings.php';

function bitey_initialize()
{
    new Bitey_API();
    new Bitey_Assets();
    new Bitey_Widget();
    new Bitey_AI_Settings();
}

add_action('plugins_loaded', 'bitey_initialize');
