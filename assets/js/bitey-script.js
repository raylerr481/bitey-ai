(function ($) {


console.log("Bitey JS loaded");



document.addEventListener(
    "DOMContentLoaded",
    function(){



        console.log("Bitey DOM loaded");



        const button = document.getElementById(
            "bitey-button"
        );


        const windowBox = document.getElementById(
            "bitey-window"
        );


        const close = document.getElementById(
            "bitey-close"
        );


        const send = document.getElementById(
            "bitey-send"
        );


        const input = document.getElementById(
            "bitey-input"
        );


        const messages = document.getElementById(
            "bitey-messages"
        );





        if(!button){

            console.error(
                "Bitey button not found"
            );

            return;

        }



        console.log(
            "Bitey interface ready"
        );






        /*
        Open Chat
        */


        button.addEventListener(
            "click",
            function(){


                if(windowBox){


                    windowBox.style.display =
                    "flex";


                }


                if(input){

                    input.focus();

                }


            }
        );








        /*
        Close Chat
        */


        if(close){


            close.addEventListener(
                "click",
                function(){


                    windowBox.style.display =
                    "none";


                }
            );


        }








        /*
        Send Button
        */


        if(send){


            send.addEventListener(
                "click",
                sendMessage
            );


        }








        /*
        Enter key
        */


        if(input){


            input.addEventListener(
                "keypress",
                function(e){


                    if(e.key === "Enter"){

                        sendMessage();

                    }


                }
            );


        }









        function sendMessage(){



            let text =
            input.value.trim();




            if(text === ""){

                return;

            }






            addMessage(
                text,
                "user"
            );



            input.value = "";







            let loading =
            addMessage(
                "Bitey está pensando 🤖...",
                "bot loading"
            );








            $.ajax({



                url:
                bitey_ajax.ajax_url,



                type:
                "POST",




                data:{



                    action:
                    "bitey_send_message",



                    nonce:
                    bitey_ajax.nonce,



                    message:
                    text,



                    name:
                    "Visitante",



                    phone:
                    "website"


                },





                success:
                function(response){



                    console.log(
                        "Bitey AJAX:",
                        response
                    );




                    if(loading){

                        loading.remove();

                    }





                    if(response.success){



                        let reply =
                        response.data.reply
                        ||
                        response.data.respuesta
                        ||
                        response.data.response
                        ||
                        response.data.message
                        ||
                        "Bitey no recibió respuesta";





                        addMessage(
                            reply,
                            "bot"
                        );




                    }
                    else{



                        addMessage(

                            "Bitey recibió el mensaje pero no pudo responder.",

                            "bot"

                        );



                    }



                },





                error:
                function(error){



                    console.error(
                        "Bitey error:",
                        error
                    );



                    if(loading){

                        loading.remove();

                    }




                    addMessage(

                        "Error conectando con Bitey Backend.",

                        "bot"

                    );



                }




            });



        }









        function addMessage(
            text,
            type
        ){



            if(!messages){

                return;

            }




            let div =
            document.createElement(
                "div"
            );




            div.className =
            "bitey-message " + type;




            div.innerHTML =
            text;




            messages.appendChild(
                div
            );




            messages.scrollTop =
            messages.scrollHeight;



            return div;


        }




    }


);



})(jQuery);