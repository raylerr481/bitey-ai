<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_API {

    public function __construct() {
        add_action('wp_ajax_bitey_send_message', array($this, 'process_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this, 'process_message'));
    }

    private function backend_url() {
        $url = get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com');
        $url = esc_url_raw(trim($url));
        return rtrim($url, '/') . '/chat';
    }

    public function process_message() {
        check_ajax_referer('bitey_nonce', 'nonce');

        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : 'Visitor';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
        $company_id = isset($_POST['company_id']) ? absint($_POST['company_id']) : 1;
        $channel = isset($_POST['channel']) ? sanitize_key(wp_unslash($_POST['channel'])) : 'website';

        if ($message === '') {
            wp_send_json_error(array('reply' => 'Escribe un mensaje para Bitey.'), 400);
        }

        if ($company_id < 1) {
            $company_id = 1;
        }

        if ($phone === '') {
            $phone = 'web-' . wp_generate_uuid4();
        }

        $payload = array(
            'message' => $message,
            'phone' => $phone,
            'company_id' => $company_id,
            'customer_name' => $name !== '' ? $name : 'Visitor',
            'channel' => $channel !== '' ? $channel : 'website',
        );

        $response = wp_remote_post(
            $this->backend_url(),
            array(
                'timeout' => 45,
                'headers' => array(
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json; charset=utf-8',
                ),
                'body' => wp_json_encode($payload),
                'data_format' => 'body',
            )
        );

        if (is_wp_error($response)) {
            error_log('[Bitey] Backend connection failed: ' . $response->get_error_message());
            wp_send_json_error(array(
                'reply' => 'No fue posible conectar con Bitey en este momento.',
            ), 502);
        }

        $status = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);

        if (!is_array($json)) {
            error_log('[Bitey] Invalid backend response. HTTP ' . $status);
            wp_send_json_error(array(
                'reply' => 'Bitey recibió una respuesta inválida del backend.',
            ), 502);
        }

        $reply = isset($json['response']) ? $json['response'] : '';
        if ($reply === '' && isset($json['message'])) {
            $reply = $json['message'];
        }

        if ($status < 200 || $status >= 300 || empty($json['success'])) {
            error_log('[Bitey] Backend returned HTTP ' . $status . ': ' . wp_json_encode($json));
            wp_send_json_error(array(
                'reply' => $reply !== '' ? $reply : 'Bitey no pudo procesar la solicitud.',
                'backend_status' => $status,
            ), $status >= 400 && $status < 600 ? $status : 502);
        }

        wp_send_json_success(array(
            'reply' => wp_kses_post($reply),
            'intent' => isset($json['intent']) ? sanitize_key($json['intent']) : null,
            'ticket_id' => isset($json['ticket_id']) ? absint($json['ticket_id']) : null,
            'conversation_id' => isset($json['conversation_id']) ? absint($json['conversation_id']) : null,
            'language' => isset($json['language']) ? sanitize_key($json['language']) : null,
        ));
    }
}
