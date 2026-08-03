<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey API Connector
|--------------------------------------------------------------------------
|
| Connects WordPress Bitey Plugin with FastAPI Bitey Core
|
*/


class Bitey_API {



    private $api_url;




    public function __construct(){


        /*
        |--------------------------------------------------------------------------
        | Backend URL
        |--------------------------------------------------------------------------
        */


        $this->api_url = get_option(
            'bitey_api_url',
            'http://localhost:8000'
        );





        /*
        |--------------------------------------------------------------------------
        | AJAX Actions
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
    | Send Message
    |--------------------------------------------------------------------------
    */


    public function send_message(){



        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */


        if(
            isset($_POST['nonce'])
            &&
            !wp_verify_nonce(
                $_POST['nonce'],
                'bitey_nonce'
            )
        ){


            wp_send_json_error(

                array(

                    'message'=>
                    'Solicitud no autorizada'

                )

            );


        }






        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */


        $message = sanitize_text_field(
            $_POST['message'] ?? ''
        );




        if(empty($message)){


            wp_send_json_error(

                array(

                    'message'=>
                    'Mensaje vacío'

                )

            );


        }








        /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */


        $payload = array(


            'mensagem' =>
            $message,


            'whatsapp' =>
            sanitize_text_field(
                $_POST['phone'] ?? 'website'
            ),


            'nome_cliente' =>
            sanitize_text_field(
                $_POST['name'] ?? 'Visitante'
            )


        );







        /*
        |--------------------------------------------------------------------------
        | Request FastAPI
        |--------------------------------------------------------------------------
        */


        $response = wp_remote_post(


            $this->api_url . '/chat',


            array(


                'headers'=>array(

                    'Content-Type'=>
                    'application/json'

                ),



                'body'=>
                wp_json_encode(
                    $payload
                ),



                'timeout'=>30


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

                    'message'=>
                    'No se pudo conectar con Bitey Backend'

                )

            );


        }








        /*
        |--------------------------------------------------------------------------
        | Read Response
        |--------------------------------------------------------------------------
        */


        $status =
        wp_remote_retrieve_response_code(
            $response
        );



        $body =
        wp_remote_retrieve_body(
            $response
        );





        error_log(
            'BITEY RESPONSE: ' . $body
        );







        if($status !== 200){


            wp_send_json_error(

                array(

                    'message'=>
                    'Error del servidor Bitey',

                    'status'=>
                    $status

                )

            );


        }








        $data = json_decode(
            $body,
            true
        );







        if(
            !is_array($data)
        ){


            wp_send_json_error(

                array(

                    'message'=>
                    'Respuesta inválida del backend'

                )

            );


        }







        /*
        |--------------------------------------------------------------------------
        | Normalize Response
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
        elseif(isset($data['message'])){


            $reply =
            $data['message'];


        }
        else{


            $reply =
            'Recibí tu mensaje, pero no tengo una respuesta disponible.';


        }







        /*
        |--------------------------------------------------------------------------
        | Return To Widget
        |--------------------------------------------------------------------------
        */


        wp_send_json_success(

            array(

                'reply'=>
                $reply,

                'raw'=>
                $data

            )

        );



    }



}