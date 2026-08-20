<?php
if (!defined('ABSPATH')) { exit; }
class Bitey_API {
    public function __construct() {
        add_action('wp_ajax_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this, 'send_message'));
        add_action('wp_ajax_bitey_chat', array($this, 'send_message'));
        add_action('wp_ajax_nopriv_bitey_chat', array($this, 'send_message'));
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
    private function normalize_language($value) {
        $value = strtolower(trim((string)$value));
        $map = array('auto'=>'auto','pt'=>'pt-BR','pt-br'=>'pt-BR','es'=>'es','en'=>'en');
        return isset($map[$value]) ? $map[$value] : 'auto';
    }
    private function fail($code, $reply, $status=502, $extra=array()) {
        error_log('[Bitey] '.$code.' '.wp_json_encode($extra));
        wp_send_json_error(array_merge(array('code'=>$code,'reply'=>$reply),$extra),$status);
    }
    private function request_data($source) {
        return array(
            'message'=>isset($source['message'])?sanitize_textarea_field(wp_unslash($source['message'])):'',
            'name'=>isset($source['name'])?sanitize_text_field(wp_unslash($source['name'])):'',
            'last_name'=>isset($source['last_name'])?sanitize_text_field(wp_unslash($source['last_name'])):'',
            'phone'=>isset($source['phone'])?sanitize_text_field(wp_unslash($source['phone'])):'',
            'email'=>isset($source['email'])?sanitize_email(wp_unslash($source['email'])):'',
            'company_id'=>isset($source['company_id'])?absint($source['company_id']):1,
            'channel'=>isset($source['channel'])?sanitize_key(wp_unslash($source['channel'])):'website',
            'conversation_id'=>isset($source['conversation_id'])?sanitize_key(wp_unslash($source['conversation_id'])):'',
            'language_preference'=>$this->normalize_language(isset($source['language_preference'])?wp_unslash($source['language_preference']):'auto'),
            'preferred_contact_channel'=>isset($source['preferred_contact_channel'])?sanitize_key(wp_unslash($source['preferred_contact_channel'])):'',
        );
    }
    private function call_backend($data) {
        if($data['message']==='') return new WP_Error('empty_message','Escribe un mensaje para continuar.');
        $backend_url=untrailingslashit((string)get_option('bitey_backend_url','https://bitefixes-backend.onrender.com'));
        $response=wp_remote_post($backend_url.'/chat',array('timeout'=>30,'headers'=>array('Accept'=>'application/json','Content-Type'=>'application/json'),'body'=>wp_json_encode(array(
            'message'=>$data['message'],'phone'=>$data['phone'],'email'=>$data['email'],'company_id'=>$data['company_id']?:1,
            'customer_name'=>trim($data['name'].' '.$data['last_name'])?:'Customer','channel'=>$data['channel']?:'website','conversation_id'=>$data['conversation_id'],
            'language_preference'=>$data['language_preference'],'preferred_contact_channel'=>$data['preferred_contact_channel'],
        ))));
        if(is_wp_error($response)) return $response;
        $status=wp_remote_retrieve_response_code($response);$raw=wp_remote_retrieve_body($response);$body=json_decode($raw,true);
        if($status<200||$status>=300)return new WP_Error('backend_http_error','Bitey Backend devolvió un error HTTP.',array('status'=>$status));
        if(!is_array($body))return new WP_Error('invalid_backend_json','Bitey Backend respondió con datos no válidos.');
        $reply=$body['response']??$body['reply']??$body['message']??'';
        if(is_array($reply))$reply=$reply['text']??$reply['message']??wp_json_encode($reply);
        if($reply==='')return new WP_Error('empty_backend_response','Bitey recibió tu mensaje, pero no generó una respuesta.');
        return array(
            'reply'=>wp_kses_post((string)$reply),'intent'=>$body['intent']??null,'confidence'=>$body['confidence']??null,
            'ticket_id'=>$body['ticket_id']??null,'customer_id'=>$body['customer_id']??null,'customer_name'=>$body['customer_name']??($data['name']?:null),
            'language'=>$body['language']??null,'language_source'=>$body['language_source']??null,'conversation_id'=>$body['conversation_id']??$data['conversation_id'],
            'preferred_contact_channel'=>$body['preferred_contact_channel']??$data['preferred_contact_channel'],'information_need'=>$body['information_need']??null,
            'knowledge_found'=>$body['knowledge_found']??false,'memory'=>$body['memory']??null,'ai_consultation'=>$body['ai_consultation']??array('used'=>false,'reason'=>'not_reported'),
            'comparative_evaluation'=>$body['comparative_evaluation']??null,'response_source'=>$body['response_source']??'core','process'=>$body['process']??null,
        );
    }
    public function rest_chat(WP_REST_Request $request) {
        $data=$this->request_data($request->get_json_params()?:array());
        $result=$this->call_backend($data);
        if(is_wp_error($result)) return new WP_REST_Response(array('success'=>false,'data'=>array('code'=>$result->get_error_code(),'reply'=>$result->get_error_message())),$result->get_error_code()==='empty_message'?400:502);
        return new WP_REST_Response(array('success'=>true,'data'=>$result),200);
    }
    public function send_message() {
        if(!check_ajax_referer('bitey_nonce','nonce',false))$this->fail('invalid_nonce','Solicitud no autorizada.',403);
        $result=$this->call_backend($this->request_data($_POST));
        if(is_wp_error($result))$this->fail($result->get_error_code(),$result->get_error_message(),502,$result->get_error_data()?:array());
        wp_send_json_success($result);
    }
}
