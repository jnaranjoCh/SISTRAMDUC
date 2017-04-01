$('#registrarSuplentesJurado').click(function (){ 

	var inputs = ["cedula", "nombre", "apellido", "facultad", "universidad", "area"];

	for (var i = 1; i < 4; i++) {
		
		for (var j = 0; j < inputs.length; j++) {
			
			if ($("#"+inputs[j]+i).val() == ""){

				$("#span"+inputs[j]+i).removeClass("hide");
			} 
			else {
				$("#span"+inputs[j]+i).addClass("hide");
			}
		}
	}

});