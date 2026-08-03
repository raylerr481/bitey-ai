<?php

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Configuración del plugin Bitey AI
 */

class Bitey_AI_Settings
{


    public function __construct()
    {

        add_action(
            'admin_menu',
            array($this,'add_menu')
        );


        add_action(
            'admin_init',
            array($this,'register_settings')
        );

    }




    public function add_menu()
    {

        add_options_page(

            'Bitey AI Settings',

            'Bitey AI',

            'manage_options',

            'bitey-ai-settings',

            array(
                $this,
                'settings_page'
            )

        );

    }





    public function register_settings()
    {

        register_setting(

            'bitey_ai_options',

            'bitey_backend_url'

        );


    }






    public function settings_page()
    {

        ?>

        <div class="wrap">

            <h1>
                Bitey AI Configuration
            </h1>


            <form method="post" action="options.php">


            <?php

            settings_fields(
                'bitey_ai_options'
            );


            ?>


            <table class="form-table">


            <tr>

            <th>
            FastAPI Backend URL
            </th>


            <td>

            <input 
            type="text"
            name="bitey_backend_url"
            value="<?php

            echo esc_attr(

                get_option(

                    'bitey_backend_url',

                    'http://127.0.0.1:8000'

                )

            );

            ?>"
            size="50"
            >


            </td>


            </tr>


            </table>


            <?php

            submit_button();

            ?>


            </form>


        </div>


        <?php

    }


}