var ident;
var posicion;

$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoConcursosAjax",
		dataType: 'json',
        success: function(data)
        {
            if (data == "N"){

                text = "No Hay Concursos Registrados";

                toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });

            } else{                   

                for (var i = 0; i <= data["vacantes"].length-1; i++) {

                    var num = data["id"][i];

                    tabla.row.add( {
                        "inicio": data["inicio"][i],
                        "vacantes": data["vacantes"][i],
                        "area": data["area"][i],
                        "doc": data["recepcion"][i],
                        "pre": data["presentacion"][i],
                        "modif": '<span class="glyphicon glyphicon-pencil" onclick="javascript:modificar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>',
                        "elim": '<span class="glyphicon glyphicon-trash" onclick="javascript:eliminar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>'
                    }).draw();                          
                }                                             
            }                       	
        }
        });
});

$("#eliminarConcurso").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/borrarConcursoAjax",
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

                text = "Concurso Eliminado";

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
        url: "/concursoOposicion/buscarConcursoAjax",
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

                $("#modificarConcurso").addClass("hide");
                $("#eliminarConcurso").removeClass("hide");                  
                
                document.getElementById("vacantes").value = data["vacantes"][0];
                document.getElementById("usuario").value = data["usuario"][0];
                document.getElementById("inicio").value = data["inicio"][0];
                document.getElementById("area").value = data["area"][0];
                document.getElementById("recepcion").value = data["recepcion"][0];
                document.getElementById("presentacion").value = data["presentacion"][0];
                document.getElementById("observacion").value = data["observacion"][0];           
            }                           
        }
    });
}

$("#modificarConcurso").click(function(){

    $.ajax({

        method:"POST",
        url: "/concursoOposicion/modificarConcursoAjax",
        dataType: 'json',
        data: {"id": ident,
        "Inicio":$("#inicio").val(), 
        "Vacantes":$("#vacantes").val(), 
        "Area":$("#area").val(), 
        "fechaDoc":$("#recepcion").val(), 
        "fechaPre":$("#presentacion").val(),
        "observacion": $("#observacion").val()},
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
        url: "/concursoOposicion/buscarConcursoAjax",
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

                $("#elim").addClass("hide");
                $("#modif").removeClass("hide");

                $("#eliminarConcurso").addClass("hide");
                $("#modificarConcurso").removeClass("hide");                  
                
                document.getElementById("vacantes").value = data["vacantes"][0];
                document.getElementById("usuario").value = data["usuario"][0];
                document.getElementById("inicio").value = data["inicio"][0];
                document.getElementById("area").value = data["area"][0];
                document.getElementById("recepcion").value = data["recepcion"][0];
                document.getElementById("presentacion").value = data["presentacion"][0];
                document.getElementById("observacion").value = data["observacion"][0];
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
