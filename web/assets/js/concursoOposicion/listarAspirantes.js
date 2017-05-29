var ident;
var posicion;

$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoAspiranteAjax",
		dataType: 'json',
        success: function(data)
        {
            if (data == "N"){

                text = "No Hay Aspirantes Registrados";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{                   

                for (var i = 0; i <= data["id"].length-1; i++) {

                    var num = data["id"][i];

                    tabla.row.add( {
                        "nombre": data["nombre1"][i]+" "+data["nombre2"][i],
                        "apellido": data["apellido1"][i]+" "+data["apellido2"][i],
                        "cedula": data["cedula"][i],
                        "telefono": data["tlf1"][i],
                        "correo": data["correo"][i],
                        "profesion": data["titulo"][i],
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
        url: "/concursoOposicion/borrarAspiranteAjax",
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

                text = "Aspirante Eliminado";

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
        url: "/concursoOposicion/buscarAspiranteAjax",
        dataType: 'json',
        data: {"id": id},
        success: function(data)
        {
            if (data == "N"){

                text = "No Se Encontró El Aspirante";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{

                $("#modif").addClass("hide");
                $("#elim").removeClass("hide");

                $("#modificarConcurso").addClass("hide");
                $("#eliminarConcurso").removeClass("hide");                  
                
                document.getElementById("nombres").value = data["nombre1"][0]+" "+data["nombre2"][0];
                document.getElementById("apellidos").value = data["apellido1"][0]+" "+data["apellido2"][0];
                document.getElementById("cedula").value = data["cedula"][0]; 
                document.getElementById("tlfn").value = data["tlf1"][0]; 
                document.getElementById("correos").value = data["correo"][0]; 
                document.getElementById("prof").value = data["titulo"][0];        
            }                           
        }
    });
}

$("#modificarConcurso").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/modificarAspiranteAjax",
        dataType: 'json',
        data: {"id": ident,
        "tlf":$("#tlfn").val(),
        "correo":$("#correos").val(),},
        success: function(data)
        {
            if (data == "N"){

                text = "Usted No Tiene Permiso";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else {

                text = "Concurso Modificado";

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
        url: "/concursoOposicion/buscarAspiranteAjax",
        dataType: 'json',
        data: {"id": id},
        success: function(data)
        {
            if (data == "N"){

                text = "No Se Encontró El Aspirante";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{

                $("#elim").addClass("hide");
                $("#modif").removeClass("hide");

                $("#eliminarConcurso").addClass("hide");
                $("#modificarConcurso").removeClass("hide");                  
                
                document.getElementById("nombres").value = data["nombre1"][0]+" "+data["nombre2"][0];
                document.getElementById("apellidos").value = data["apellido1"][0]+" "+data["apellido2"][0];
                document.getElementById("cedula").value = data["cedula"][0]; 
                document.getElementById("tlfn").value = data["tlf1"][0]; 
                document.getElementById("correos").value = data["correo"][0]; 
                document.getElementById("prof").value = data["titulo"][0];  
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
