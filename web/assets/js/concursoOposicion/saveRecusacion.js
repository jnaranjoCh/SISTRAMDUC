$( window ).load(function (){

	$.ajax({
        method: "POST",
        url:  "/concursoOposicion/listadoJuradosAjax",
        dataType: 'json',
        success: function(data)
        {
        	var opcion = "<option id='jur' selected='selected'>...</option>";
 
        	for (var i = 0; i < data["id"].length; i++) {
        		
        		var num = data["id"][i];

        		opcion = opcion+"<option value="+num+">"+data["nombre"][i]+
        		" "+data["apellido"][i]
        		+"   -   <b>Area:</b> "+data["areainvestigacion"][i]
        		+"   -   <b>Facultad:</b> "+data["facultad"][i]
        		+"   -   <b>Universidad:</b> "+data["universidad"][i]
        		+"</option>";   		
        	}

        	$("#juradoLista").html(opcion);
        }
    });	

    $.ajax({
        method: "POST",
        url:  "/concursoOposicion/listadoAspirantesAjax",
        dataType: 'json',
        success: function(data)
        {
        	var opcion = "<option id='asp' selected='selected'>...</option>";
 
        	for (var i = 0; i < data["id"].length; i++) {
        		
        		var num = data["id"][i];

        		opcion = opcion+"<option value="+num+">"+data["nombre1"][i]
        		+" "+data["nombre2"][i]+" "+data["apellido1"][i]+" "+data["apellido2"][i]
        		+"   -   <b>Cedula:</b> "+data["cedula"][i]
        		+"</option>";   		
        	}

        	$("#aspiranteLista").html(opcion);
        }
    });	
});

$('#registrar').click(function (){ 

	toastr.clear();
	var text = "";

	var continua = true;

	if (continua){

		/*json*/

		$.ajax({
			method: "POST",
			data: {"aspirante": $('#aspirante').val(),
			"jurado": $('#jurado').val()},
			url: "/concursoOposicion/registroRecusacionAjax",
			dataType: "json",
			success: function(data)
			{
				if (data == "S") {


				} else {


				}
			}
		});

		/*fin json*/

	} else{
		toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
         });
	}
});

$('#limpiarRecusacion').click(function (){ 

	document.getElementById('recusa').reset();

	$('#aspitanteSpam').addClass("hide");
	$('#juradoSpam').addClass("hide");
	$('#fechaSpam').addClass("hide");

	toastr.clear();
});
