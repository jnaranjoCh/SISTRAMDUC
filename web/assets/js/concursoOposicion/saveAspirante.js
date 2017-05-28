$( window ).load(function (){

	$.ajax({
        method: "POST",
        url:  "/concursoOposicion/listadoConcursosAjax",
        dataType: 'json',
        success: function(data)
        {
        	var opcion = "<option id='sel' selected='selected'>...</option>";
 
        	for (var i = 0; i < data["id"].length; i++) {
        		
        		var num = data["id"][i];

        		opcion = opcion+"<option value="+num+"><b>Area:</b> "+data["area"][i]+
        		"   -   <b>Vacantes:</b> "+data["vacantes"][i]
        		+"   -   <b>Fecha Inicio:</b> "+data["inicio"][i]
        		+"</option>";   		
        	}

        	$("#lista").html(opcion);
        }
    });	
});

$('#registrarAspirante').click(function (){ 
	
	var inputs = ["cedula", "nombre1", "apellido1", "tlf1", "email1", "universidad", "tiulo", "graduacion"];
	var continua = true;	

	toastr.clear();
	var text = "";

	for (var j = 0; j < inputs.length; j++) {
		
		if ($("#"+inputs[j]).val() == ""){

			$("#span"+inputs[j]).removeClass("hide");
			continua = false;
			text = "Debe llenar los campos";
		} 
		else {
			$("#span"+inputs[j]).addClass("hide");
		}
	}

	if ($("#lista").val() == null || 
		$("#lista").val() == "..." || 
		$("#lista").val() == ""){
		continua = false;
		text = "Concurso vacío";
	}

	if (continua){

		$.ajax({
            method: "POST",
            data: {"cedula":$("#cedula").val(),
            "nombre1":$("#nombre1").val(),
            "nombre2":$("#nombre2").val(),
            "apellido1":$("#apellido1").val(),
            "apellido2":$("#apellido2").val(),
            "tlf1":$("#tlf1").val(),
            "tlf2":$("#tlf2").val(),
            "email1":$("#email1").val(),
            "universidad":$("#universidad").val(),
            "tiulo":$("#tiulo").val(),
            "graduacion":$("#graduacion").val(),
            "observacion":$("#observacion").val(),
            "concurso": $("#lista").val() },
            url:  "/concursoOposicion/registroAspiranteAjax",
            dataType: 'json',
            success: function(data)
            {
                if (data == "S"){

					text = "Aspirante Insertado";

					document.getElementById('aspiranteForm').reset();

			        toastr.success(text, "Exito", {
			                    "timeOut": "0",
			                    "extendedTImeout": "0"
			                 });			
                } 
                else{

                	if (data == "N"){

	                	text = "Usted no tiene permiso";
                	}
                	else {

                		if (data == "R") text = "Aspirante Ya Registrado";
                		else text = "Error al Registrar Aspirante";
                	} 

			        toastr.error(text, "Error", {
	                    "timeOut": "0",
	                    "extendedTImeout": "0"
	                 });	               	
                }
            }
        }); 

	} else {
		toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
         });
	}
});

$('#limpiarAspirante').click(function (){ 
	
	var inputs = ["cedula", "nombre1", "apellido1", "tlf1", "email1", "universidad", "tiulo", "graduacion"];
		
	for (var j = 0; j < inputs.length; j++)	
		$("#span"+inputs[j]).addClass("hide");

	document.getElementById('aspiranteForm').reset();

	toastr.clear();
});