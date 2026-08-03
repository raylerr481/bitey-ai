document.addEventListener(
    "DOMContentLoaded",
    function(){


        console.log(
            "Bitey JS loaded"
        );



        const button =
        document.getElementById(
            "bitey-button"
        );


        const windowBox =
        document.getElementById(
            "bitey-window"
        );


        const close =
        document.getElementById(
            "bitey-close"
        );


        const send =
        document.getElementById(
            "bitey-send"
        );


        const input =
        document.getElementById(
            "bitey-input"
        );


        const nameInput =
        document.getElementById(
            "bitey-name"
        );


        const phoneInput =
        document.getElementById(
            "bitey-phone"
        );


        const messages =
        document.getElementById(
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
        Open
        */


        button.addEventListener(
            "click",
            function(){


                windowBox.style.display =
                "flex";


                input.focus();


            }
        );







        /*
        Close
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
        Send
        */


        send.addEventListener(
            "click",
            sendMessage
        );







        input.addEventListener(
            "keypress",
            function(e){


                if(e.key === "Enter"){

                    sendMessage();

                }


            }
        );









        function sendMessage(){



            const text =
            input.value.trim();



            if(!text){

                return;

            }





            addMessage(
                text,
                "user"
            );



            input.value = "";





            const loading =
            addMessage(
                "Bitey está pensando 🤖...",
                "bot loading"
            );






            const formData =
            new FormData();



            formData.append(
                "action",
                "bitey_send_message"
            );


            formData.append(
                "nonce",
                bitey_ajax.nonce
            );


            formData.append(
                "message",
                text
            );


            formData.append(
                "name",
                nameInput.value || "Visitante"
            );


            formData.append(
                "phone",
                phoneInput.value || ""
            );


            formData.append(
                "company_id",
                bitey_ajax.company_id
            );


            formData.append(
                "channel",
                bitey_ajax.channel
            );







            fetch(
                bitey_ajax.ajax_url,
                {

                    method:"POST",

                    body:formData

                }

            )
            .then(
                response =>
                response.json()
            )
            .then(
                response => {



                    if(loading){

                        loading.remove();

                    }





                    if(response.success){


                        addMessage(

                            response.data.reply,

                            "bot"

                        );


                    }
                    else{


                        addMessage(

                            "Bitey tuvo un problema procesando la solicitud.",

                            "bot"

                        );


                    }



                }

            )
            .catch(

                error => {


                    console.error(
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

            );



        }









        function addMessage(
            text,
            type
        ){



            if(!messages){

                return;

            }





            const div =
            document.createElement(
                "div"
            );



            div.className =
            "bitey-message " + type;




            div.textContent =
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