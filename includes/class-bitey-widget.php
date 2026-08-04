<?php

if (!defined('ABSPATH')) {
    exit;
}


/*
|--------------------------------------------------------------------------
| Bitey Widget
|--------------------------------------------------------------------------
|
| Frontend Chat Interface
|
*/


class Bitey_Widget {



    public function __construct(){


        add_action(
            'wp_footer',
            array(
                $this,
                'render'
            )
        );


    }





    public function render(){


        ?>


        <div id="bitey-container">



            <!-- Floating Button -->

            <button 
                id="bitey-button"
                class="bitey-button"
                type="button">

                🤖

            </button>







            <!-- Chat Window -->


            <div 
                id="bitey-window"
                class="bitey-window"
                style="display:none;">





                <div class="bitey-header">


                    <span>
                        Bitey AI
                    </span>


                    <button
                        id="bitey-close"
                        type="button">

                        ×

                    </button>


                </div>








                <div 
                    id="bitey-messages"
                    class="bitey-messages">


                    <div class="bitey-message bot">

                        Hola 👋 soy Bitey.
                        ¿Cómo puedo ayudarte?

                    </div>


                </div>









                <div class="bitey-user-data">



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



                </div>









                <div class="bitey-input-area">


                    <input

                        id="bitey-input"

                        type="text"

                        placeholder="Escribe tu mensaje..."

                    />




                    <button

                        id="bitey-send"

                        type="button">


                        Enviar


                    </button>



                </div>







            </div>



        </div>




        <?php


    }



}