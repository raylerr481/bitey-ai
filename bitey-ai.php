<?php
/*
Plugin Name: Bitey AI Assistant
Plugin URI: https://www.bitefixes.com
Description: Asistente inteligente Bitey conectado al backend FastAPI de BiteFixes.
Version: 1.0.1
Author: BiteFixes
Author URI: https://www.bitefixes.com
License: GPL2
Text Domain: bitey-ai-assistant
*/


if (!defined('ABSPATH')) {
    exit;
}



/*
|--------------------------------------------------------------------------
| Plugin Constants
|--------------------------------------------------------------------------
*/


define(
    'BITEY_AI_VERSION',
    '1.0.1'
);


define(
    'BITEY_AI_PATH',
    plugin_dir_path(__FILE__)
);


define(
    'BITEY_AI_URL',
    plugin_dir_url(__FILE__)
);


define(
    'BITEY_AI_FILE',
    __FILE__
);






/*
|--------------------------------------------------------------------------
| Load Plugin Classes
|--------------------------------------------------------------------------
*/


function bitey_ai_load_classes(){


    $classes = array(

        'class-bitey-assets.php',

        'class-bitey-api.php',

        'class-bitey-widget.php'

    );



    foreach($classes as $class){


        $file = BITEY_AI_PATH . 'includes/' . $class;



        if(file_exists($file)){


            require_once $file;


        }


    }


}



add_action(
    'plugins_loaded',
    'bitey_ai_load_classes'
);









/*
|--------------------------------------------------------------------------
| Initialize Bitey AI Components
|--------------------------------------------------------------------------
*/


function bitey_ai_init(){



    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    */


    if(class_exists('Bitey_Assets')){


        new Bitey_Assets();


    }






    /*
    |--------------------------------------------------------------------------
    | FastAPI Connector
    |--------------------------------------------------------------------------
    */


    if(class_exists('Bitey_API')){


        new Bitey_API();


    }






    /*
    |--------------------------------------------------------------------------
    | Chat Widget
    |--------------------------------------------------------------------------
    */


    if(class_exists('Bitey_Widget')){


        new Bitey_Widget();


    }




}



add_action(
    'init',
    'bitey_ai_init'
);









/*
|--------------------------------------------------------------------------
| Plugin Activation
|--------------------------------------------------------------------------
*/


register_activation_hook(
    __FILE__,
    'bitey_ai_activate'
);




function bitey_ai_activate(){



    /*
    |--------------------------------------------------------------------------
    | Default Backend URL
    |--------------------------------------------------------------------------
    */


    if(
        !get_option(
            'bitey_api_url'
        )
    ){


        add_option(

            'bitey_api_url',

            'http://127.0.0.1:8000'

        );


    }






    /*
    |--------------------------------------------------------------------------
    | Enable Plugin
    |--------------------------------------------------------------------------
    */


    if(
        !get_option(
            'bitey_enabled'
        )
    ){


        add_option(

            'bitey_enabled',

            true

        );


    }






    flush_rewrite_rules();


}









/*
|--------------------------------------------------------------------------
| Plugin Deactivation
|--------------------------------------------------------------------------
*/


register_deactivation_hook(
    __FILE__,
    'bitey_ai_deactivate'
);




function bitey_ai_deactivate(){


    flush_rewrite_rules();


}









/*
|--------------------------------------------------------------------------
| Admin Notice
|--------------------------------------------------------------------------
*/


function bitey_ai_admin_notice(){



    $api_url = get_option(
        'bitey_api_url'
    );



    if(empty($api_url)){



        echo '

        <div class="notice notice-warning">

            <p>

            Bitey AI necesita configurar la conexión con FastAPI.

            </p>

        </div>

        ';



    }



}



add_action(
    'admin_notices',
    'bitey_ai_admin_notice'
);