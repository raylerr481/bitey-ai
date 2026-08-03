<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey Chat Widget
|--------------------------------------------------------------------------
|
| Frontend chat interface
|
*/


class Bitey_Widget {



    public function __construct(){


        /*
        |--------------------------------------------------------------------------
        | Render Widget
        |--------------------------------------------------------------------------
        */


        add_action(
            'wp_footer',
            array(
                $this,
                'render_widget'
            )
        );


    }







    /*
    |--------------------------------------------------------------------------
    | Output Chat HTML
    |--------------------------------------------------------------------------
    */


    public function render_widget(){



        if(
            !get_option(
                'bitey_enabled',
                true
            )
        ){

            return;

        }




        ?>


        <!-- Bitey Button -->


        <button
            id="bitey-button"
            type="button"
        >

            💬 Bitey AI

        </button>







        <!-- Bitey Window -->


        <div
            id="bitey-window"
            style="display:none;"
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

                    id="bitey-name"

                    type="text"

                    placeholder="Tu nombre"

                />





                <input

                    id="bitey-phone"

                    type="text"

                    placeholder="WhatsApp"

                />







                <input

                    id="bitey-input"

                    type="text"

                    placeholder="Describe tu problema técnico..."

                />





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