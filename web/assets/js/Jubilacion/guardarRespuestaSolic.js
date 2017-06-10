$('#enviarRespuesta').click(function (){

    toastr.clear();
    var text = "";

    var motivo = $('#motivo').val();
    var continua = true;

    if (motivo == ""){

        continua = false;
        $('#spanMotivo').removeClass("hide");
        text = "Campo vacío";
    }

    if (continua){

        /*json*/
        $.ajax({
            method: "POST",
            data: {"Estatus":$("#estatus").val(),
                "": $("motivo").val()},
            url:  "/jubilacion/insertar",
            dataType: 'json',
            success: function(data)
            {
                if (data == "S"){

                    document.getElementById('solicitudes').reset();

                    $('#spanMotivo').addClass("hide");
                    text = "Respuesta de solicitud enviada";

                    toastr.success(text, "Exito", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                    });
                }
                else{
                    if (data == "N"){
                        $('#spanMotivo').addClass("hide");
                        text = "Usted no tiene permiso";
                    }
                    else {
                        $('#spanMotivo').addClass("hide");
                        text = "Error al enviar respuesta a la solicitud";
                    }

                    toastr.error(text, "Error", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                    });
                }
            }
        });
        /*fin del json*/
    }
    else {
        toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
        });
    }
});