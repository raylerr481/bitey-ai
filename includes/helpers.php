<?php
/**
 * ==========================================================
 * Bitey AI Assistant
 * Helper Functions
 * ==========================================================
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Obtener una opción del plugin.
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function bitey_get_option($key, $default = '')
{
    $options = get_option('bitey_ai_settings', []);

    if (!is_array($options)) {
        $options = [];
    }

    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Guardar una opción del plugin.
 *
 * @param string $key
 * @param mixed $value
 */
function bitey_update_option($key, $value)
{
    $options = get_option('bitey_ai_settings', []);

    if (!is_array($options)) {
        $options = [];
    }

    $options[$key] = $value;

    update_option('bitey_ai_settings', $options);
}

/**
 * Escribir en el log si el modo debug está activado.
 *
 * @param mixed $message
 */
function bitey_log($message)
{
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }

    if (is_array($message) || is_object($message)) {
        $message = print_r($message, true);
    }

    error_log('[BITEY] ' . $message);
}

/**
 * Sanitizar texto.
 *
 * @param string $text
 * @return string
 */
function bitey_clean_text($text)
{
    return sanitize_text_field(trim($text));
}

/**
 * Escapar HTML.
 *
 * @param string $text
 * @return string
 */
function bitey_escape($text)
{
    return esc_html($text);
}

/**
 * Comprobar si la API está configurada.
 *
 * @return bool
 */
function bitey_api_is_configured()
{
    return !empty(bitey_get_option('api_url'));
}

/**
 * Obtener la URL del backend.
 *
 * @return string
 */
function bitey_api_url()
{
    return untrailingslashit(
        bitey_get_option(
            'api_url',
            'https://api.bitefixes.com'
        )
    );
}

/**
 * Obtener endpoint del chat.
 *
 * @return string
 */
function bitey_chat_endpoint()
{
    return bitey_api_url() . '/chat';
}

/**
 * Obtener idioma actual.
 *
 * @return string
 */
function bitey_language()
{
    return determine_locale();
}

/**
 * Obtener ID de sesión.
 *
 * @return string
 */
function bitey_session_id()
{
    if (!session_id()) {
        @session_start();
    }

    if (empty($_SESSION['bitey_session'])) {
        $_SESSION['bitey_session'] = wp_generate_uuid4();
    }

    return $_SESSION['bitey_session'];
}

/**
 * Obtener versión del plugin.
 *
 * @return string
 */
function bitey_version()
{
    return BITEY_VERSION;
}