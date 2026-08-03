<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey API Connector
|--------------------------------------------------------------------------
|
| WordPress → Bitey Core FastAPI
|
| Handles communication between:
| Website Widget
|       ↓
| WordPress
|       ↓
| FastAPI
|       ↓
| Supabase
|
|--------------------------------------------------------------------------
*/


class Bitey_API {


    private $api_url;



    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */


    public function __construct(){


        $this->api_url = rtrim(
            get_option(
                'bitey_api_url',
                'http://localhost:8000'
            ),
            '/'
        );



        /*
        |--------------------------------------------------------------------------
        | AJAX Endpoints
        |--------------------------------------------------------------------------
        */


        add_action(
            'wp_ajax_bitey_send_message',
            array(
                $this,
                'send_message'
            )
        );


        add_action(
            'wp_ajax_nopriv_bitey_send_message',
            array(
                $this,
                'send_message'
            )
        );


    }






    /*
    |--------------------------------------------------------------------------
    | Send Message To Bitey Core
    |--------------------------------------------------------------------------
    */


    public function send_message(){



        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */


        if(
            !isset($_POST['nonce'])
            ||
            !wp_verify_nonce(
                $_POST['nonce'],
                'bitey_nonce'
            )
        ){


            wp_send_json_error(

                array(

                    'message' =>
                    'Solicitud no autorizada'

                )

            );

        }






        /*
        |--------------------------------------------------------------------------
        | Receive Data
        |--------------------------------------------------------------------------
        */


        $message = sanitize_text_field(

            $_POST['message'] ?? ''

        );



        $name = sanitize_text_field(

            $_POST['name'] ?? 'Visitante'

        );



        $phone = sanitize_text_field(

            $_POST['phone'] ?? ''

        );



        $customer_id = intval(

            $_POST['customer_id'] ?? 0

        );






        if(empty($message)){


            wp_send_json_error(

                array(

                    'message' =>
                    'Mensaje vacío'

                )

            );


        }







        /*
        |--------------------------------------------------------------------------
        | Payload For Bitey Core
        |--------------------------------------------------------------------------
        */


        $payload = array(



            /*
            Existing customer
            */


            "cliente_id" => $customer_id,



            /*
            Message
            */


            "mensagem" => $message,



            /*
            Customer data
            */


            "nome_cliente" => $name,


            "whatsapp" => $phone,



            /*
            Channel identification
            */


            "canal" => "website",



            /*
            Multi company support
            */


            "company_id" => 1



        );









        /*
        |--------------------------------------------------------------------------
        | Send Request
        |--------------------------------------------------------------------------
        */


        $response = wp_remote_post(

            $this->api_url . '/chat',

            array(

                'headers' => array(

                    'Content-Type' =>
                    'application/json'

                ),


                'body' => wp_json_encode(

                    $payload

                ),


                'timeout' => 30

            )

        );








        /*
        |--------------------------------------------------------------------------
        | Connection Error
        |--------------------------------------------------------------------------
        */


        if(
            is_wp_error($response)
        ){


            wp_send_json_error(

                array(

                    'message' =>
                    'No fue posible conectar con Bitey Core',


                    'error' =>
                    $response->get_error_message()

                )

            );


        }








        /*
        |--------------------------------------------------------------------------
        | Response Data
        |--------------------------------------------------------------------------
        */


        $status = wp_remote_retrieve_response_code(

            $response

        );



        $body = wp_remote_retrieve_body(

            $response

        );




        error_log(

            'BITEY CORE RESPONSE: ' . $body

        );







        if($status !== 200){


            wp_send_json_error(

                array(

                    'message' =>
                    'Bitey Core devolvió un error',


                    'status' =>
                    $status,


                    'response' =>
                    $body

                )

            );


        }









        /*
        |--------------------------------------------------------------------------
        | Decode JSON
        |--------------------------------------------------------------------------
        */


        $data = json_decode(

            $body,

            true

        );






        if(!is_array($data)){


            wp_send_json_error(

                array(

                    'message' =>
                    'Respuesta inválida desde Bitey Core'

                )

            );


        }








        /*
        |--------------------------------------------------------------------------
        | Extract Reply
        |--------------------------------------------------------------------------
        */


        $reply = '';



        if(isset($data['respuesta'])){


            $reply =
            $data['respuesta'];


        }
        elseif(isset($data['response'])){


            $reply =
            $data['response'];


        }
        elseif(isset($data['reply'])){


            $reply =
            $data['reply'];


        }
        else{


            $reply =
            'Bitey recibió tu mensaje correctamente.';


        }








        /*
        |--------------------------------------------------------------------------
        | Return To Javascript Widget
        |--------------------------------------------------------------------------
        */


        wp_send_json_success(

            array(


                "reply" =>
                $reply,


                "intent" =>
                $data['intent'] ?? null,


                "service_id" =>
                $data['service_id'] ?? null,


                "ticket_id" =>
                $data['ticket_id'] ?? null,


                "raw" =>
                $data


            )

        );


    }


}