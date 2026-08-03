<?php

if (!defined('ABSPATH')) {
    exit;
}



/*
|--------------------------------------------------------------------------
| Bitey Assets Manager
|--------------------------------------------------------------------------
|
| Loads Bitey frontend resources
|
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







    /*
    |--------------------------------------------------------------------------
    | Load CSS and JS
    |--------------------------------------------------------------------------
    */


    public function load_assets(){



        /*
        |--------------------------------------------------------------------------
        | Paths
        |--------------------------------------------------------------------------
        */


        $css_path =
        BITEY_AI_PATH .
        'assets/css/bitey-style.css';



        $js_path =
        BITEY_AI_PATH .
        'assets/js/bitey-script.js';








        /*
        |--------------------------------------------------------------------------
        | CSS
        |--------------------------------------------------------------------------
        */


        if(file_exists($css_path)){


            wp_enqueue_style(

                'bitey-ai-style',

                BITEY_AI_URL .
                'assets/css/bitey-style.css',

                array(),

                filemtime($css_path)

            );


        }








        /*
        |--------------------------------------------------------------------------
        | JavaScript
        |--------------------------------------------------------------------------
        */


        if(file_exists($js_path)){


            wp_enqueue_script(

                'bitey-ai-script',

                BITEY_AI_URL .
                'assets/js/bitey-script.js',

                array(),

                filemtime($js_path),

                true

            );







            /*
            |--------------------------------------------------------------------------
            | Data For Frontend
            |--------------------------------------------------------------------------
            */


            wp_localize_script(

                'bitey-ai-script',

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
                    'website',



                    'version' =>
                    BITEY_AI_VERSION


                )

            );



        }


    }



}