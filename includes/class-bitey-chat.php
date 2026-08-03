<?php
if (!defined('ABSPATH')) {
    exit;
}

class Bitey_Chat {

    /**
     * URL del backend FastAPI
     * Cambiar por la URL de producción cuando se despliegue.
     */
    private $backend_url = 'http://127.0.0.1:8000/chat';

    public function __construct() {

        add_action('wp_ajax_bitey_chat', array($this, 'process_chat'));
        add_action('wp_ajax_nopriv_bitey_chat', array($this, 'process_chat'));

    }

    /**
     * Procesa el mensaje recibido desde el widget.
     */
    public function process_chat() {

        check_ajax_referer('bitey_nonce', 'nonce');

        $message = isset($_POST['message'])
            ? sanitize_text_field($_POST['message'])
            : '';

        if (empty($message)) {

            wp_send_json(array(
                'success' => false,
                'reply'   => 'El mensaje está vacío.'
            ));

        }

        $payload = array(
            'mensagem' => $message
        );

        $response = wp_remote_post(
            $this->backend_url,
            array(
                'timeout' => 30,
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'body' => wp_json_encode($payload)
            )
        );

        if (is_wp_error($response)) {

            wp_send_json(array(
                'success' => false,
                'reply' => 'No fue posible conectar con Bitey.'
            ));

        }

        $body = wp_remote_retrieve_body($response);

        $json = json_decode($body, true);

        if (!$json) {

            wp_send_json(array(
                'success' => false,
                'reply' => 'Respuesta inválida del servidor.'
            ));

        }

        $reply = '';

        if (isset($json['respuesta'])) {

            $reply = $json['respuesta'];

        } elseif (isset($json['response'])) {

            $reply = $json['response'];

        } else {

            $reply = 'No se recibió respuesta.';

        }

        wp_send_json(array(
            'success' => true,
            'reply'   => $reply
        ));

    }

}