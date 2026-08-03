<?php

if (!defined('ABSPATH')) {
    exit;
}



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



        $css_file =
        BITEY_AI_PATH .
        'assets/css/bitey-style.css';



        $js_file =
        BITEY_AI_PATH .
        'assets/js/bitey-script.js';








        /*
        CSS
        */


        if(file_exists($css_file)){


            wp_enqueue_style(

                'bitey-ai-style',

                BITEY_AI_URL .
                'assets/css/bitey-style.css',

                array(),

                filemtime($css_file)

            );


        }








        /*
        JavaScript
        */


        if(file_exists($js_file)){


            wp_enqueue_script(

                'bitey-ai-script',

                BITEY_AI_URL .
                'assets/js/bitey-script.js',

                array(
                    'jquery'
                ),

                filemtime($js_file),

                true

            );







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
                    )

                )

            );



        }



    }


}