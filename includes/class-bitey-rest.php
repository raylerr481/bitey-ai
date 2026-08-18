<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_REST {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('bitey/v1', '/health', array(
            'methods' => WP_REST_Server::READABLE,
            'permission_callback' => function () {
                return current_user_can('manage_options');
            },
            'callback' => array($this, 'health'),
        ));
    }

    public function health() {
        $backend_url = untrailingslashit((string) get_option('bitey_backend_url', BITEY_DEFAULT_BACKEND));
        $response = wp_remote_get($backend_url . '/health', array(
            'timeout' => 10,
            'headers' => array('Accept' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            return new WP_REST_Response(array(
                'status' => 'error',
                'backend' => $backend_url,
                'message' => $response->get_error_message(),
            ), 502);
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        return new WP_REST_Response(array(
            'status' => ($code >= 200 && $code < 300) ? 'ok' : 'error',
            'plugin_version' => BITEY_VERSION,
            'backend' => $backend_url,
            'backend_http_status' => $code,
            'backend_response' => is_array($body) ? $body : null,
        ), $code >= 200 && $code < 300 ? 200 : 502);
    }
}
