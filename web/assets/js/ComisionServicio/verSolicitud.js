$("#id").click(function (){
    
    var res = "";
    $.ajax({

        method:"POST",
        url: "comision_servicio_ver_solicitud",
        data: {"tramite": $('#id').val()},
        dataType: 'json',
        success: function(data)
        {
            res = res+"<tr><th><h4>Solicitud Nº<b>" + data["id"][0] + "</b></h4></th>" +
                "</tr><tr><th></th><th></th><th><h5><b>Nombres y Apellidos:</b>&nbsp;&nbsp;" +
                data["nombreCompleto"][1] + "</h5><h5><b>Cédula de Identidad:</b>&nbsp;&nbsp;" +
                data["cedula"][2] + "</h5><h5><b>Correo:</b>&nbsp;&nbsp;" +
                data["correo"][3] + "</h5><h5><b>Tlf:</b>&nbsp;&nbsp;" + data["telefono"][4] +
                "</h5><h5><b>Tlf:</b>&nbsp;&nbsp;" + data[fechaRecibido][5] + "</h5></th></tr>";
            
            $("#myModal").html(res).modal("show");
        }
    });
});


    
    /*var editId = $(ctrl).data('id');
    var success=false; //open modal only if success=true
    //url should match your server function so I will assign url as below:
    var url="{{ path('comision_servicio_ver_solicitud') }}" ; //this is the server function you are calling
    var data=JSON.stringify({"id":editId});
    $.when( //To execute some other functionality once ajax call is done
        $.ajax({
            type: 'GET',
            url: url,
            data: data,
            dataType:'json',
            success: function(response){
                res=response;
                success=true;
            },
            error:function(){
            }
        })).then(function(){
        if(success)
        {
            $("#myModal").html(res).modal("show"); //show the modal
        }
    });*/
