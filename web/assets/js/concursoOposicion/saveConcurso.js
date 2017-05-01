$('#registrarConcurso').click(function (){ 
	
	toastr.clear();
	var text = "";

	var fecha = $('#fechaConcurso').val();
	var continua = true;

	if (fecha == ""){

		continua = false;
		$('#spanfechaConcurso').removeClass("hide");
		text = "Campo vacío";
	}

	var cedula = $('#cedula').val();

	if (cedula == "" || cedula == 0 || cedula == '0'){

		continua = false;
		$('#spancedula').removeClass("hide");
		text = "Campo vacío";
	}

	if ($('#area').val() == ""){

		continua = false;
		$('#spanarea').removeClass("hide");
		text = "Campo vacío";
	}

	if (continua){

		/*json*/

		$.ajax({
            method: "POST",
            data: {"Inicio":$("#fechaConcurso").val(), 
            "Vacantes":$("#cedula").val(), 
            "Area":$("#area").val(), 
            "fechaDoc":$("#fechaDoc").val(), 
            "fechaPre":$("#fechaPre").val(),
            "": $("observacion").val(), 
            "tipo":"Oposicion"},
            url:  "/concursoOposicion/registroConcursoAjax",
            dataType: 'json',
            success: function(data)
            {
                if (data == "S"){

                	document.getElementById('aperturaConcurso').reset();

					$('#spanfechaConcurso').addClass("hide");
					$('#spancedula').addClass("hide");
					$('#spancedula2').addClass("hide");
					$('#spanarea').addClass("hide");

					text = "Concurso Insertado";

			        toastr.success(text, "Exito", {
			                    "timeOut": "0",
			                    "extendedTImeout": "0"
			                 });			
                } 
                else{

                	if (data == "N"){

                		$('#spanfechaConcurso').addClass("hide");
						$('#spancedula').addClass("hide");
						$('#spancedula2').addClass("hide");
						$('#spanarea').addClass("hide");

	                	text = "Usted no tiene permiso";
                	}
                	else {

                		$('#spanfechaConcurso').addClass("hide");
						$('#spancedula').addClass("hide");
						$('#spancedula2').addClass("hide");
						$('#spanarea').addClass("hide");

						text = "Error al Registrar Concurso";
                	} 

			        toastr.error(text, "Error", {
	                    "timeOut": "0",
	                    "extendedTImeout": "0"
	                 });	               	
                }
            }
        }); 
		
		/*fin del json*/		
	}
	else {
         toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
         });
	}
});

$('#limpiarConcurso').click(function (){ 

	document.getElementById('aperturaConcurso').reset();

	$('#spanfechaConcurso').addClass("hide");
	$('#spancedula').addClass("hide");
	$('#spancedula2').addClass("hide");
	$('#spanarea').addClass("hide");

	toastr.clear();
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}