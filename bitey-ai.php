<?php
/**
 * Plugin Name: Bitey AI Assistant
 * Plugin URI: https://bitefixes.com
 * Description: Bitey AI website assistant connected to the BiteFixes FastAPI backend.
 * Version: 1.1.0
 * Author: BiteFixes
 * Author URI: https://bitefixes.com
 * License: GPL-2.0-or-later
 * Text Domain: bitey-ai
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BITEY_VERSION', '1.1.0');
define('BITEY_PATH', plugin_dir_path(__FILE__));
define('BITEY_URL', plugin_dir_url(__FILE__));

require_once BITEY_PATH . 'includes/class-bitey-settings.php';
require_once BITEY_PATH . 'includes/class-bitey-api.php';
require_once BITEY_PATH . 'includes/class-bitey-assets.php';
require_once BITEY_PATH . 'includes/class-bitey-widget.php';

function bitey_initialize() {
    new Bitey_AI_Settings();
    new Bitey_API();
    new Bitey_Assets();
    new Bitey_Widget();
}

add_action('plugins_loaded', 'bitey_initialize');
