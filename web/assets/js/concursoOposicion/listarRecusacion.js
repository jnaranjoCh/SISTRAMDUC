var ident;
var posicion;

$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoRecusacionAjax",
		dataType: 'json',
        success: function(data)
        {
            if (data == "N"){

                text = "No Hay Recusaciones Registradas";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{                   

                for (var i = 0; i <= data["id"].length-1; i++) {

                    var num = data["id"][i];

                    tabla.row.add( {
                        "aspirante": data["aspirante"][i],
                        "jurado": data["jurado"][i],
                        "fecha": data["fecha"][i],
                        "usuario": data["usuario"][i],
                        "elim": '<span class="glyphicon glyphicon-trash" onclick="javascript:eliminar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>'
                    }).draw();                          
                }                                             
            }                       	
        }
        });
});


function eliminar(id, pos){

    ident = id;
    posicion = pos;

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/buscarRecusadoAjax",
        dataType: 'json',
        data: {"id": id},
        success: function(data)
        {
            if (data == "N"){

                text = "No Se Encontró El Concurso";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{

                $("#modif").addClass("hide");
                $("#elim").removeClass("hide");

                $("#modificarRecusacion").addClass("hide");
                $("#eliminarRecusacion").removeClass("hide");                  
                
                document.getElementById("fecha").value = data["fecha"][0];
                document.getElementById("aspiren").value = data["aspirante"][0];
                document.getElementById("jurar").value = data["jurado"][0];
                document.getElementById("usuario").value = data["usuario"][0];          
            }                           
        }
    });
}


$("#eliminarRecusacion").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/borrarRecusacionAjax",
        dataType: 'json',
        data: {"id": ident},
        success: function(data)
        {
            if (data == "N"){

                text = "Usted No Tiene Permiso";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else {

                text = "Recusación Eliminada";

                toastr.success(text, "Exito", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

                $('#myModal').modal('toggle');

                location.reload();      //funciona, pero no es la idea

               ident = null; 
               posicion = null;
            }                                                               
        }
    });
});