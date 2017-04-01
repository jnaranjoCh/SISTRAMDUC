$('#registrarAspirante').click(function (){ 
	
	var inputs = ["cedula", "nombre1", "nombre2", "apellido1", "apellido2", "tlf1", "tlf2" , "email1", "email2", "universidad", "tiulo", "graduacion", "observacion"];
		
	for (var j = 0; j < inputs.length; j++) {
		
		if ($("#"+inputs[j]).val() == ""){

			$("#span"+inputs[j]).removeClass("hide");
		} 
		else {
			$("#span"+inputs[j]).addClass("hide");
		}
	}

});

$('#registrarDocumentos').click(function (){ 
	
	var inputs = ["titulo", "nota", "hoja", "cedula"];
		
	for (var j = 0; j < inputs.length; j++) {
		
		if ($("#"+inputs[j]).val() == ""){

			$("#span"+inputs[j]).removeClass("hide");
		} 
		else {
			$("#span"+inputs[j]).addClass("hide");
		}
	}

});