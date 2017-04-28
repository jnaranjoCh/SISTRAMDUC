$('#registrar').click(function (){ 

	toastr.clear();
	var text = "";

	var continua = true;
	var fecha = $('#fecha').val();

	if (fecha == ""){
		continua = false;
		$('#fechaSpam').removeClass("hide");
		text = "Campo vacío";
	} else {

		$('#aspitanteSpam').addClass("hide");
		$('#fechaSpam').addClass("hide");
		$('#juradoSpam').addClass("hide");
	}

	if ($('#aspirante').val() == ""){
		continua = false;
		$('#aspitanteSpam').removeClass("hide");
		text = "Campo vacío";
	} else {

		$('#aspitanteSpam').addClass("hide");
		$('#juradoSpam').addClass("hide");
	}

	if ($('#jurado').val() == ""){
		continua = false;
		$('#juradoSpam').removeClass("hide");
		text = "Campo vacío";
	} else {

		$('#juradoSpam').addClass("hide");
	}

	if (continua){

		/*json*/

		$.ajax({
			method: "POST",
			data: {"fecha":$('#fecha').val(), 
			"aspirante": $('#aspirante').val(),
			"jurado": $('#jurado').val()},
			url: "/concursoOposicion/registroRecusacionAjax",
			dataType: "json",
			success: function(data)
			{
				if (data == "S") {

					document.getElementById('recusa').reset();

					$('#aspitanteSpam').addClass("hide");
					$('#juradoSpam').addClass("hide");
					$('#fechaSpam').addClass("hide");

					text = "Recusación Registrado";

			        toastr.success(text, "Exito", {
	                    "timeOut": "0",
	                    "extendedTImeout": "0"
	                 });

				} else {
					if (data == "N") {

						text = "Usted No Tiene Permiso";
					}
					else {

						if (data == "A") {

							text = "El Aspirante No Existe";
						}
						else{

							if (data == "J") {

								text = "El Jurado No Existe";
							}
							else {

								text = "Error al Registrar Concurso";
							}
						}						
					}

					toastr.error(text, "Error", {
			            "timeOut": "0",
			            "extendedTImeout": "0"
			        });
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
