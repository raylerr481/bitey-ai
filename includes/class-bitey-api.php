<?php
if (!defined('ABSPATH')) { exit; }

class Bitey_API {
    private $allowed_extensions = array('pdf','docx','txt','csv','json','md');

    public function __construct() {
        add_action('wp_ajax_bitey_send_message', array($this,'send_message'));
        add_action('wp_ajax_nopriv_bitey_send_message', array($this,'send_message'));
        add_action('wp_ajax_bitey_chat', array($this,'send_message'));
        add_action('wp_ajax_nopriv_bitey_chat', array($this,'send_message'));
        add_action('wp_ajax_bitey_import_company', array($this,'import_company'));
        add_action('wp_ajax_nopriv_bitey_import_company', array($this,'import_company'));
        add_action('wp_ajax_bitey_import_company_document', array($this,'import_company_document'));
        add_action('wp_ajax_nopriv_bitey_import_company_document', array($this,'import_company_document'));
        add_action('rest_api_init', array($this,'register_rest_routes'));
    }

    public function register_rest_routes() {
        register_rest_route('bitey/v1','/chat',array('methods'=>'POST','permission_callback'=>array($this,'rest_permission'),'callback'=>array($this,'rest_chat')));
    }

    public function rest_permission(WP_REST_Request $request) {
        $nonce=$request->get_header('X-WP-Nonce');
        return $nonce && wp_verify_nonce($nonce,'wp_rest');
    }

    private function normalize_language($value) {
        $value=strtolower(trim((string)$value));
        $map=array('auto'=>'auto','pt'=>'pt-BR','pt-br'=>'pt-BR','es'=>'es','en'=>'en');
        return isset($map[$value])?$map[$value]:'auto';
    }

    private function fail($code,$reply,$status=502,$extra=array()) {
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
            'company_id'=>absint(get_option('bitey_company_id',1))?:1,
            'channel'=>isset($source['channel'])?sanitize_key(wp_unslash($source['channel'])):'website',
            'conversation_id'=>isset($source['conversation_id'])?sanitize_key(wp_unslash($source['conversation_id'])):'',
            'language_preference'=>$this->normalize_language(isset($source['language_preference'])?wp_unslash($source['language_preference']):'auto'),
            'preferred_contact_channel'=>isset($source['preferred_contact_channel'])?sanitize_key(wp_unslash($source['preferred_contact_channel'])):''
        );
    }

    private function backend_url() { return untrailingslashit((string)get_option('bitey_backend_url','https://bitefixes-backend.onrender.com')); }
    private function channel_key() { return trim((string)get_option('bitey_channel_api_key','')); }

    private function parse_backend_response($response) {
        if(is_wp_error($response)) return $response;
        $status=wp_remote_retrieve_response_code($response);
        $raw=wp_remote_retrieve_body($response);
        $body=json_decode($raw,true);
        if($status<200||$status>=300) return new WP_Error('backend_http_error','Bitey API no pudo completar la consulta en este momento.',array('status'=>$status,'body'=>is_array($body)?$body:null));
        if(!is_array($body)) return new WP_Error('invalid_backend_json','Bitey API respondió con datos no válidos.');
        return $body;
    }

    private function call_backend($data) {
        if($data['message']==='') return new WP_Error('empty_message','Escribe un mensaje para continuar.');
        $headers=array('Accept'=>'application/json','Content-Type'=>'application/json');
        if($this->channel_key()!=='') $headers['X-Bitey-Channel-Key']=$this->channel_key();
        $response=wp_remote_post($this->backend_url().'/chat',array('timeout'=>45,'headers'=>$headers,'body'=>wp_json_encode(array('message'=>$data['message'],'phone'=>$data['phone'],'email'=>$data['email'],'company_id'=>$data['company_id'],'customer_name'=>trim($data['name'].' '.$data['last_name'])?:'Customer','channel'=>$data['channel']?:'website','conversation_id'=>$data['conversation_id'],'language_preference'=>$data['language_preference'],'preferred_contact_channel'=>$data['preferred_contact_channel']))));
        $body=$this->parse_backend_response($response);
        if(is_wp_error($body)) return $body;
        $reply=$body['response']??$body['reply']??$body['message']??'';
        if(is_array($reply)) $reply=$reply['text']??$reply['message']??wp_json_encode($reply);
        if($reply==='') return new WP_Error('empty_backend_response','Bitey recibió tu mensaje, pero no devolvió una respuesta.');
        return array('reply'=>wp_kses_post((string)$reply),'customer_id'=>$body['customer_id']??null,'customer_name'=>$body['customer_name']??($data['name']?:null),'language'=>$body['language']??null,'conversation_id'=>$body['conversation_id']??$data['conversation_id'],'preferred_contact_channel'=>$body['preferred_contact_channel']??$data['preferred_contact_channel'],'memory'=>$body['memory']??null);
    }

    public function rest_chat(WP_REST_Request $request) {
        $data=$this->request_data($request->get_json_params()?:array());
        $result=$this->call_backend($data);
        if(is_wp_error($result)) return new WP_REST_Response(array('success'=>false,'data'=>array('code'=>$result->get_error_code(),'reply'=>$result->get_error_message())),$result->get_error_code()==='empty_message'?400:502);
        return new WP_REST_Response(array('success'=>true,'data'=>$result),200);
    }

    private function validate_upload($file) {
        if(empty($file)||!isset($file['error'])) return new WP_Error('file_missing','No se recibió ningún documento.');
        if((int)$file['error']!==UPLOAD_ERR_OK) return new WP_Error('file_upload_error','No se pudo recibir el documento.');
        if((int)$file['size']>10*1024*1024) return new WP_Error('file_too_large','El documento supera el límite de 10 MB.');
        $name=sanitize_file_name($file['name']);
        $ext=strtolower(pathinfo($name,PATHINFO_EXTENSION));
        if(!in_array($ext,$this->allowed_extensions,true)) return new WP_Error('file_type_not_allowed','Tipo de documento no permitido.');
        $check=wp_check_filetype_and_ext($file['tmp_name'],$name);
        if(!empty($check['ext']) && !in_array(strtolower($check['ext']),$this->allowed_extensions,true)) return new WP_Error('file_type_not_allowed','Tipo de documento no permitido.');
        return array('name'=>$name,'ext'=>$ext,'type'=>sanitize_text_field($file['type']??'application/octet-stream'),'data'=>file_get_contents($file['tmp_name']));
    }

    private function multipart_request($url,$fields,$file) {
        $boundary='----BiteyBoundary'.wp_generate_password(20,false,false); $body='';
        foreach($fields as $key=>$value){$body.="--{$boundary}\r\nContent-Disposition: form-data; name=\"{$key}\"\r\n\r\n".(string)$value."\r\n";}
        $body.="--{$boundary}\r\nContent-Disposition: form-data; name=\"file\"; filename=\"".str_replace('"','',$file['name'])."\"\r\nContent-Type: {$file['type']}\r\n\r\n".$file['data']."\r\n--{$boundary}--\r\n";
        $headers=array('Accept'=>'application/json','Content-Type'=>'multipart/form-data; boundary='.$boundary);
        if($this->channel_key()!=='') $headers['X-Bitey-Channel-Key']=$this->channel_key();
        return wp_remote_post($url,array('timeout'=>60,'headers'=>$headers,'body'=>$body));
    }

    public function import_company_document() {
        if(!check_ajax_referer('bitey_nonce','nonce',false)) $this->fail('invalid_nonce','Solicitud no autorizada.',403);
        if($this->channel_key()==='') $this->fail('channel_key_missing','La carga de documentos no está configurada.',503);
        $file=$this->validate_upload($_FILES['file']??null);
        if(is_wp_error($file)) $this->fail($file->get_error_code(),$file->get_error_message(),400);
        $company_id=absint(get_option('bitey_company_id',0));
        if(!$company_id) $this->fail('company_context_missing','El contexto empresarial todavía no está configurado.',503);
        $response=$this->multipart_request($this->backend_url().'/company-profile/import',array('company_id'=>$company_id,'company_name'=>sanitize_text_field(wp_unslash($_POST['company_name']??'')),'source'=>'wordpress','channel'=>'website'),$file);
        $body=$this->parse_backend_response($response);
        if(is_wp_error($body)) $this->fail($body->get_error_code(),$body->get_error_message(),502,$body->get_error_data()?:array());
        wp_send_json_success($body);
    }

    public function import_company() { $this->import_company_document(); }

    public function send_message() {
        if(!check_ajax_referer('bitey_nonce','nonce',false)) $this->fail('invalid_nonce','Solicitud no autorizada.',403);
        $result=$this->call_backend($this->request_data($_POST));
        if(is_wp_error($result)) $this->fail($result->get_error_code(),$result->get_error_message(),502,$result->get_error_data()?:array());
        wp_send_json_success($result);
    }
}
