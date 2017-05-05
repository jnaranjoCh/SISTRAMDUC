var ident;
var posicion;

$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoSuplenteCPECAjax",
		dataType: 'json',
        success: function(data)
        {
                if (data == "N"){

                        text = "No Hay Jurados Registrados";

                        toastr.error(text, "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                } else{

                    for (var i = 0; i <= data["nombre"].length-1; i++) {

                        var num = data["id"][i];

                        tabla.row.add( {
                                "nombre": data["nombre"][i],
                                "apellido": data["apellido"][i],
                                "areainvestigacion": data["areainvestigacion"][i],
                                "facultad": data["facultad"][i],
                                "universidad": data["universidad"][i],
                                "modif": '<span class="glyphicon glyphicon-pencil" onclick="javascript:modificar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>'
                                //,"elim": '<span class="glyphicon glyphicon-trash" onclick="javascript:eliminar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>'
                        }).draw();                               
                    }                                           
                }        	
        }
        });
});


$("#eliminarConcurso").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/borrarJuradoAjax",
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

                text = "Suplente Eliminado";

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

function eliminar(id, pos){

    ident = id;
    posicion = pos;

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/buscarCpecSuplenteAjax",
        dataType: 'json',
        data: {"id": id},
        success: function(data)
        {
            if (data == "N"){

                text = "No Se Encontró El Evaluador";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{

                $("#modif").addClass("hide");
                $("#elim").removeClass("hide");

                $("#modificarConcurso").addClass("hide");
                $("#eliminarConcurso").removeClass("hide"); 

                document.getElementById("nombre").value = data["nombre"][0];
                document.getElementById("apellido").value = data["apellido"][0];
                document.getElementById("areainvestigacion").value = data["areainvestigacion"][0];
                document.getElementById("facultad").value = data["facultad"][0];
                document.getElementById("universidad").value = data["universidad"][0];
                document.getElementById("idusuarioasigna").value = data["idusuarioasigna"][0];
                document.getElementById("cedula").value = data["cedula"][0];           
            }                           
        }
    });
}

$("#modificarConcurso").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/modificarJuradoAjax",
        dataType: 'json',
        data: {"id": ident,
                "cedula":$("#cedula").val(), 
                "nombre":$("#nombre").val(),
                "apellido":$("#apellido").val(), 
                "facultad":$("#facultad").val(), 
                "universidad":$("#universidad").val(), 
                "area":$("#areainvestigacion").val()},
        success: function(data)
        {
            if (data == "N"){

                text = "Usted No Tiene Permiso";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else {

                text = "Suplente Modificado";

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

function modificar(id, pos){

    ident = id;
    posicion = pos;

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/buscarCpecSuplenteAjax",
        dataType: 'json',
        data: {"id": id},
        success: function(data)
        {
            if (data == "N"){

                text = "No Se Encontró El Suplente";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{

                $("#elim").addClass("hide");
                $("#modif").removeClass("hide");

                $("#eliminarConcurso").addClass("hide");
                $("#modificarConcurso").removeClass("hide");                  
                
                document.getElementById("nombre").value = data["nombre"][0];
                document.getElementById("apellido").value = data["apellido"][0];
                document.getElementById("areainvestigacion").value = data["areainvestigacion"][0];
                document.getElementById("facultad").value = data["facultad"][0];
                document.getElementById("universidad").value = data["universidad"][0];
                document.getElementById("idusuarioasigna").value = data["idusuarioasigna"][0];
                document.getElementById("cedula").value = data["cedula"][0]; 
            }                           
        }
        });
}

function justNumbers(e){

    var keynum = window.event ? window.event.keyCode : e.which;

    if (keynum == 8 || keynum == 46){
        return true;
    }
    else return /\d/.test(String.fromCharCode(keynum));
}