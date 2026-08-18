<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Server-side bridge between WordPress AJAX and the BiteFixes FastAPI backend.
 */
class Bitey_API
{
    public function __construct()
    {
        add_action('wp_ajax_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_bitey_chat', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_chat', array($this, 'send_message'));
    }

    public function send_message()
    {
        if (!check_ajax_referer('bitey_nonce', 'nonce', false)) {
            wp_send_json_error(array('reply' => 'Solicitud no autorizada.'), 403);
        }

        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : 'Customer';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
        $company_id = isset($_POST['company_id']) ? absint($_POST['company_id']) : 1;
        $channel = isset($_POST['channel']) ? sanitize_key(wp_unslash($_POST['channel'])) : 'website';

        if ($message === '') {
            wp_send_json_error(array('reply' => 'Escribe un mensaje para continuar.'), 400);
        }

        $backend_url = untrailingslashit((string) get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com'));
        $endpoint = $backend_url . '/chat';

        $response = wp_remote_post(
            $endpoint,
            array(
                'timeout' => 30,
                'headers' => array('Accept' => 'application/json'),
                'body' => array(
                    'message' => $message,
                    'phone' => $phone,
                    'company_id' => $company_id ?: 1,
                    'customer_name' => $name ?: 'Customer',
                    'channel' => $channel ?: 'website',
                ),
            )
        );

        if (is_wp_error($response)) {
            error_log('[Bitey] Backend connection error: ' . $response->get_error_message());
            wp_send_json_error(array('reply' => 'No se pudo conectar con Bitey Backend.'), 502);
        }

        $status = wp_remote_retrieve_response_code($response);
        $raw_body = wp_remote_retrieve_body($response);
        $body = json_decode($raw_body, true);

        if (!is_array($body) || $status < 200 || $status >= 300) {
            error_log('[Bitey] Backend HTTP ' . $status . ': ' . $raw_body);
            wp_send_json_error(array('reply' => 'Bitey Backend no pudo procesar la solicitud.'), 502);
        }

        $reply = $body['response'] ?? $body['reply'] ?? $body['message'] ?? '';

        if (is_array($reply)) {
            $reply = $reply['text'] ?? $reply['message'] ?? wp_json_encode($reply);
        }

        if ($reply === '') {
            $reply = 'Bitey recibió tu mensaje, pero no generó una respuesta.';
        }

        wp_send_json_success(
            array(
                'reply' => wp_kses_post((string) $reply),
                'intent' => $body['intent'] ?? null,
                'ticket_id' => $body['ticket_id'] ?? null,
            )
        );
    }
}
