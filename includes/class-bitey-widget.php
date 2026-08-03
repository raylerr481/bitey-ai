<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey AI Widget
|--------------------------------------------------------------------------
*/


class Bitey_Widget {


    private static $loaded = false;



    public function __construct(){


        /*
        |--------------------------------------------------------------------------
        | Load widget automatically
        |--------------------------------------------------------------------------
        */


        add_action(
            'wp_footer',
            array(
                $this,
                'render_widget'
            ),
            99
        );


    }





    /*
    |--------------------------------------------------------------------------
    | Render Widget
    |--------------------------------------------------------------------------
    */


    public function render_widget(){



        if(
            is_admin()
        ){

            return;

        }




        /*
        Prevent duplicates
        */


        if(
            self::$loaded
        ){

            return;

        }



        self::$loaded = true;



?>



<!-- ==========================
     Bitey AI Button
========================== -->


<button

    id="bitey-button"

    type="button"

>

    💬 Bitey AI

</button>






<!-- ==========================
     Bitey AI Window
========================== -->


<div

    id="bitey-window"

>





    <!-- Header -->

    <div

        id="bitey-header"

    >


        <span>

            🤖 Bitey AI Assistant

        </span>





        <button

            id="bitey-close"

            type="button"

        >

            ✕

        </button>


    </div>









    <!-- Messages -->


    <div

        id="bitey-messages"

    >



        <div

            class="bitey-message bot"

        >

            Hola, soy Bitey 🤖

            <br>

            ¿En qué puedo ayudarte?


        </div>



    </div>









    <!-- Footer -->


    <div

        id="bitey-footer"

    >



        <input


            id="bitey-input"


            type="text"


            placeholder="Describe tu problema técnico..."


        >






        <button


            id="bitey-send"


            type="button"


        >

            Enviar


        </button>




    </div>






</div>





<?php



    }



}