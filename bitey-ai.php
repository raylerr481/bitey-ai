<?php
/**
 * Plugin Name: Bitey AI Assistant
 * Plugin URI: https://www.bitefixes.com
 * Description: Enterprise AI facade for WordPress. Bitey supplies live company/page context to external AI directors and learns from evaluated interactions.
 * Version: 3.1.0
 * Author: BiteFixes
 * Author URI: https://www.bitefixes.com
 * License: GPL-2.0-or-later
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Text Domain: bitey-ai
 */
if (!defined('ABSPATH')) { exit; }

define('BITEY_VERSION', '3.1.0');
define('BITEY_PATH', plugin_dir_path(__FILE__));
define('BITEY_URL', plugin_dir_url(__FILE__));

require_once BITEY_PATH . 'includes/class-bitey-api.php';
require_once BITEY_PATH . 'includes/class-bitey-assets.php';
require_once BITEY_PATH . 'includes/class-bitey-widget.php';
require_once BITEY_PATH . 'includes/class-bitey-settings.php';
require_once BITEY_PATH . 'includes/class-bitey-company-profile.php';

function bitey_initialize() {
    new Bitey_API();
    new Bitey_Assets();
    new Bitey_Widget();
    new Bitey_AI_Settings();
    new Bitey_Company_Profile();
}
add_action('plugins_loaded', 'bitey_initialize');

register_activation_hook(__FILE__, function () {
    if (get_option('bitey_company_id', null) === null) add_option('bitey_company_id', 1);
    if (get_option('bitey_backend_url', null) === null) add_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com');
});
