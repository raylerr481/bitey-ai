<?php

if (!defined('ABSPATH')) {
    exit;
}

class Bitey_API {
    public function __construct() {
        add_action('wp_ajax_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_bitey_chat', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_chat', array($this, 'send_message'));
    }

    private function normalize_language($value) {
        $value = strtolower(trim((string) $value));
        $map = array(
            'auto' => 'auto',
            'pt' => 'pt-BR',
            'pt-br' => 'pt-BR',
            'es' => 'es',
            'en' => 'en',
        );
        return isset($map[$value]) ? $map[$value] : 'auto';
    }

    private function diagnostic_reply($code, $fallback, $status = 502, $extra = array()) {
        error_log('[Bitey] ' . $code . ' ' . wp_json_encode($extra));
        wp_send_json_error(array_merge(array(
            'code' => $code,
            'reply' => $fallback,
        ), $extra), $status);
    }

    public function send_message() {
        if (!check_ajax_referer('bitey_nonce', 'nonce', false)) {
            $this->diagnostic_reply('invalid_nonce', 'Solicitud no autorizada.', 403);
        }

        $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : 'Customer';
        $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
        $company_id = isset($_POST['company_id']) ? absint($_POST['company_id']) : 1;
        $channel = isset($_POST['channel']) ? sanitize_key(wp_unslash($_POST['channel'])) : 'website';
        $conversation_id = isset($_POST['conversation_id']) ? sanitize_key(wp_unslash($_POST['conversation_id'])) : '';
        $language_preference = $this->normalize_language(isset($_POST['language_preference']) ? wp_unslash($_POST['language_preference']) : 'auto');

        if ($message === '') {
            $this->diagnostic_reply('empty_message', 'Escribe un mensaje para continuar.', 400);
        }

        $backend_url = untrailingslashit((string) get_option('bitey_backend_url', BITEY_DEFAULT_BACKEND));
        $endpoint = $backend_url . '/chat';

        $payload = array(
            'message' => $message,
            'phone' => $phone,
            'company_id' => $company_id ?: 1,
            'customer_name' => $name ?: 'Customer',
            'channel' => $channel ?: 'website',
            'conversation_id' => $conversation_id,
            'language_preference' => $language_preference,
        );

        $response = wp_remote_post($endpoint, array(
            'timeout' => 30,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ),
            'body' => wp_json_encode($payload),
        ));

        if (is_wp_error($response)) {
            $this->diagnostic_reply('backend_unreachable', 'No se pudo conectar con Bitey Backend.', 502, array(
                'transport_error' => $response->get_error_message(),
                'endpoint' => $endpoint,
            ));
        }

        $status = wp_remote_retrieve_response_code($response);
        $raw_body = wp_remote_retrieve_body($response);
        $body = json_decode($raw_body, true);

        if ($status < 200 || $status >= 300) {
            $this->diagnostic_reply('backend_http_error', 'Bitey Backend devolvió un error.', 502, array(
                'backend_status' => $status,
            ));
        }

        if (!is_array($body)) {
            $this->diagnostic_reply('invalid_backend_json', 'Bitey Backend respondió con datos no válidos.', 502);
        }

        $reply = $body['response'] ?? $body['reply'] ?? $body['message'] ?? '';
        if (is_array($reply)) {
            $reply = $reply['text'] ?? $reply['message'] ?? wp_json_encode($reply);
        }

        if ($reply === '') {
            $this->diagnostic_reply('empty_backend_response', 'Bitey recibió tu mensaje, pero no generó una respuesta.', 502);
        }

        wp_send_json_success(array(
            'reply' => wp_kses_post((string) $reply),
            'intent' => $body['intent'] ?? null,
            'ticket_id' => $body['ticket_id'] ?? null,
            'language' => $body['language'] ?? null,
            'language_source' => $body['language_source'] ?? null,
            'conversation_id' => $body['conversation_id'] ?? $conversation_id,
        ));
    }
}
