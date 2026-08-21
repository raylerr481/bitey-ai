<?php
if (!defined('ABSPATH')) { exit; }

/** Secure gateway between WordPress and the Bitey/FastAPI backend. */
class Bitey_API {
    private $allowed_extensions = array('pdf','doc','docx','txt','csv','json','md');

    public function __construct() {
        add_action('wp_ajax_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_bitey_import_company', array($this, 'import_company'));
        add_action('wp_ajax_bitey_save_company_context', array($this, 'save_company_context'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    public function register_rest_routes() {
        register_rest_route('bitey/v1', '/chat', array(
            'methods' => 'POST',
            'permission_callback' => array($this, 'rest_permission'),
            'callback' => array($this, 'rest_chat'),
        ));
    }

    public function rest_permission(WP_REST_Request $request) {
        $nonce = $request->get_header('X-WP-Nonce');
        return $nonce && wp_verify_nonce($nonce, 'wp_rest');
    }

    private function backend_url() {
        return untrailingslashit((string) get_option('bitey_backend_url', 'https://bitefixes-backend.onrender.com'));
    }

    private function language($value) {
        $value = strtolower(trim((string) $value));
        $map = array('auto'=>'auto','es'=>'es','pt'=>'pt-BR','pt-br'=>'pt-BR','en'=>'en');
        return isset($map[$value]) ? $map[$value] : 'auto';
    }

    private function payload($source) {
        $company_id = absint(isset($source['company_id']) ? $source['company_id'] : get_option('bitey_company_id', 1));
        return array(
            'message' => sanitize_textarea_field(wp_unslash($source['message'] ?? '')),
            'name' => sanitize_text_field(wp_unslash($source['name'] ?? '')),
            'last_name' => sanitize_text_field(wp_unslash($source['last_name'] ?? '')),
            'phone' => sanitize_text_field(wp_unslash($source['phone'] ?? '')),
            'email' => sanitize_email(wp_unslash($source['email'] ?? '')),
            'company_id' => $company_id ?: 1,
            'channel' => sanitize_key(wp_unslash($source['channel'] ?? 'website')),
            'conversation_id' => sanitize_key(wp_unslash($source['conversation_id'] ?? '')),
            'language_preference' => $this->language(wp_unslash($source['language_preference'] ?? 'auto')),
        );
    }

    private function backend_post($path, $body, $timeout = 45) {
        $response = wp_remote_post($this->backend_url() . $path, array(
            'timeout' => $timeout,
            'headers' => array('Accept'=>'application/json','Content-Type'=>'application/json'),
            'body' => wp_json_encode($body),
        ));
        if (is_wp_error($response)) { return $response; }
        $status = wp_remote_retrieve_response_code($response);
        $raw = wp_remote_retrieve_body($response);
        $data = json_decode($raw, true);
        if ($status < 200 || $status >= 300) { return new WP_Error('bitey_backend_error', 'Bitey Backend devolvió un error.', array('status'=>$status,'body'=>$data)); }
        if (!is_array($data)) { return new WP_Error('bitey_invalid_response', 'Bitey Backend respondió con datos no válidos.'); }
        return $data;
    }

    private function chat_result($data) {
        if ($data['message'] === '') { return new WP_Error('empty_message', 'Escribe un mensaje para continuar.'); }
        $result = $this->backend_post('/chat', array(
            'message'=>$data['message'], 'phone'=>$data['phone'], 'email'=>$data['email'],
            'company_id'=>$data['company_id'], 'customer_name'=>trim($data['name'].' '.$data['last_name']) ?: 'Customer',
            'channel'=>$data['channel'], 'conversation_id'=>$data['conversation_id'],
            'language_preference'=>$data['language_preference'],
        ));
        if (is_wp_error($result)) { return $result; }
        $reply = $result['response'] ?? $result['reply'] ?? $result['message'] ?? '';
        if (is_array($reply)) { $reply = $reply['text'] ?? $reply['message'] ?? wp_json_encode($reply); }
        if ($reply === '') { return new WP_Error('empty_backend_response', 'Bitey recibió la consulta, pero no generó una respuesta.'); }
        return array(
            'reply'=>wp_kses_post((string)$reply), 'intent'=>$result['intent'] ?? null,
            'confidence'=>$result['confidence'] ?? null, 'ticket_id'=>$result['ticket_id'] ?? null,
            'customer_id'=>$result['customer_id'] ?? null, 'conversation_id'=>$result['conversation_id'] ?? $data['conversation_id'],
            'language'=>$result['language'] ?? null, 'memory'=>$result['memory'] ?? null,
            'ai_consultation'=>$result['ai_consultation'] ?? array('used'=>false),
            'comparative_evaluation'=>$result['comparative_evaluation'] ?? null,
            'response_source'=>$result['response_source'] ?? 'external_ai_or_core',
            'enterprise_context'=>$result['enterprise_context'] ?? null,
            'maturity'=>$result['maturity'] ?? null,
        );
    }

    public function rest_chat(WP_REST_Request $request) {
        $result = $this->chat_result($this->payload($request->get_json_params() ?: array()));
        if (is_wp_error($result)) { return new WP_REST_Response(array('success'=>false,'data'=>array('code'=>$result->get_error_code(),'reply'=>$result->get_error_message())), 400); }
        return new WP_REST_Response(array('success'=>true,'data'=>$result), 200);
    }

    public function send_message() {
        if (!check_ajax_referer('bitey_nonce','nonce',false)) { wp_send_json_error(array('code'=>'invalid_nonce','reply'=>'Solicitud no autorizada.'),403); }
        $result = $this->chat_result($this->payload($_POST));
        if (is_wp_error($result)) { wp_send_json_error(array('code'=>$result->get_error_code(),'reply'=>$result->get_error_message()), 400); }
        wp_send_json_success($result);
    }

    private function validate_upload($file) {
        if (empty($file) || !isset($file['error'])) { return new WP_Error('file_missing','No se recibió ningún documento.'); }
        if ((int)$file['error'] !== UPLOAD_ERR_OK) { return new WP_Error('file_upload_error','No se pudo recibir el documento.'); }
        if ((int)$file['size'] > 10 * 1024 * 1024) { return new WP_Error('file_too_large','El documento supera el límite de 10 MB.'); }
        $name = sanitize_file_name($file['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowed_extensions, true)) { return new WP_Error('file_type_not_allowed','Tipo no permitido. Usa PDF, DOC, DOCX, TXT, CSV, JSON o MD.'); }
        $check = wp_check_filetype_and_ext($file['tmp_name'], $name);
        if (!empty($check['ext']) && !in_array(strtolower($check['ext']), $this->allowed_extensions, true)) { return new WP_Error('file_type_not_allowed','Tipo de documento no permitido.'); }
        return array('name'=>$name,'type'=>sanitize_text_field($file['type'] ?? 'application/octet-stream'),'data'=>file_get_contents($file['tmp_name']));
    }

    private function multipart($url, $fields, $file) {
        $boundary = '----BiteyBoundary' . wp_generate_password(20, false, false);
        $body = '';
        foreach ($fields as $key=>$value) { $body .= "--{$boundary}\r\nContent-Disposition: form-data; name=\"{$key}\"\r\n\r\n" . (string)$value . "\r\n"; }
        $body .= "--{$boundary}\r\nContent-Disposition: form-data; name=\"file\"; filename=\"" . str_replace('"','',$file['name']) . "\"\r\nContent-Type: {$file['type']}\r\n\r\n" . $file['data'] . "\r\n--{$boundary}--\r\n";
        return wp_remote_post($url, array('timeout'=>90,'headers'=>array('Accept'=>'application/json','Content-Type'=>'multipart/form-data; boundary='.$boundary),'body'=>$body));
    }

    public function import_company() {
        if (!current_user_can('manage_options')) { wp_send_json_error(array('reply'=>'No autorizado.'),403); }
        if (!check_ajax_referer('bitey_nonce','nonce',false)) { wp_send_json_error(array('reply'=>'Solicitud no autorizada.'),403); }
        $file = $this->validate_upload($_FILES['company_document'] ?? null);
        if (is_wp_error($file)) { wp_send_json_error(array('code'=>$file->get_error_code(),'reply'=>$file->get_error_message()),400); }
        $company_id = absint($_POST['company_id'] ?? get_option('bitey_company_id',1)) ?: 1;
        $response = $this->multipart($this->backend_url().'/company-profile/import', array('company_id'=>$company_id,'company_name'=>sanitize_text_field(wp_unslash($_POST['company_name'] ?? '')),'source'=>'wordpress','channel'=>'admin'), $file);
        if (is_wp_error($response)) { wp_send_json_error(array('reply'=>'No fue posible conectar con Bitey Backend.'),502); }
        $status = wp_remote_retrieve_response_code($response); $body = json_decode(wp_remote_retrieve_body($response),true);
        if ($status < 200 || $status >= 300) { wp_send_json_error($body ?: array('reply'=>'El backend rechazó el documento.'),502); }
        wp_send_json_success($body ?: array('message'=>'Documento enviado.'));
    }

    public function save_company_context() {
        if (!current_user_can('manage_options') || !check_ajax_referer('bitey_nonce','nonce',false)) { wp_send_json_error(array('reply'=>'No autorizado.'),403); }
        $company_id = absint($_POST['company_id'] ?? get_option('bitey_company_id',1)) ?: 1;
        $result = $this->backend_post('/company-profile/context', array(
            'company_id'=>$company_id,
            'company_name'=>sanitize_text_field(wp_unslash($_POST['company_name'] ?? '')),
            'website'=>esc_url_raw(wp_unslash($_POST['website'] ?? '')),
            'description'=>sanitize_textarea_field(wp_unslash($_POST['description'] ?? '')),
            'source'=>'wordpress_admin',
        ));
        if (is_wp_error($result)) { wp_send_json_error(array('reply'=>$result->get_error_message()),502); }
        wp_send_json_success($result);
    }
}
