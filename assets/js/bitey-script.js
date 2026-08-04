<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey Assets
|--------------------------------------------------------------------------
|
| Load CSS / JS
| Pass AJAX variables
|
|--------------------------------------------------------------------------
*/


class Bitey_Assets {



    public function __construct(){


        add_action(

            'wp_enqueue_scripts',

            array(

                $this,

                'load_assets'

            )

        );


    }








    public function load_assets(){



        /*
        |--------------------------------------------------------------------------
        | CSS
        |--------------------------------------------------------------------------
        */


        wp_enqueue_style(

            'bitey-style',

            BITEY_URL . 'assets/css/bitey-style.css',

            array(),

            BITEY_VERSION

        );








        /*
        |--------------------------------------------------------------------------
        | JavaScript
        |--------------------------------------------------------------------------
        */


        wp_enqueue_script(

            'bitey-script',

            BITEY_URL . 'assets/js/bitey-script.js',

            array(),

            BITEY_VERSION,

            true

        );









        /*
        |--------------------------------------------------------------------------
        | AJAX Variables
        |--------------------------------------------------------------------------
        */


        wp_localize_script(

            'bitey-script',

            'bitey_ajax',

            array(


                'ajax_url' =>

                    admin_url(
                        'admin-ajax.php'
                    ),



                'nonce' =>

                    wp_create_nonce(
                        'bitey_nonce'
                    ),




                'company_id' =>

                    1,




                'channel' =>

                    'website'



            )

        );




    }



}