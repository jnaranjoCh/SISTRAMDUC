$('#registrarConcurso').click(function (){ 
	 
	var fecha = $('#fechaConcurso').val();
	var continua = true;

	if (fecha == ""){

		continua = false;
		$('#spanfechaConcurso').removeClass("hide");
	}

	var cedula = $('#cedula').val();

	if (cedula == "" || cedula == 0 || cedula == '0'){

		continua = false;
		$('#spancedula').removeClass("hide");
	}
	else {

		for (var i = 0; i < cedula.length; i++) {
			
			var letra = cedula.charAt(i)

			if (letra < "0" || letra > "9"){

				continua = false;
				$('#spancedula2').removeClass("hide");
				break;
			}
			else {

				$('#spancedula2').addClass("hide");
			}
		}
	}

	if ($('#area').val() == ""){

		continua = false;
		$('#spanarea').removeClass("hide");
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

                	$('#msgFracaso').addClass("hide");
                	$('#msgFracaso1').addClass("hide");
					$('#msgExito').removeClass("hide");	
                } 
                else{

                	if (data == "N"){

                		$('#spanfechaConcurso').addClass("hide");
						$('#spancedula').addClass("hide");
						$('#spancedula2').addClass("hide");
						$('#spanarea').addClass("hide");
	                	$('#msgFracaso').addClass("hide");
	                	$('#msgFracaso1').addClass("hide");
	                	$('#msgExito').addClass("hide");
	                	$('#msgPermiso').removeClass("hide");	
                	}
                	else {

                		$('#spanfechaConcurso').addClass("hide");
						$('#spancedula').addClass("hide");
						$('#spancedula2').addClass("hide");
						$('#spanarea').addClass("hide");
	                	$('#msgFracaso').addClass("hide");
	                	$('#msgExito').addClass("hide");
	                	$('#msgPermiso').addClass("hide");

						$('#msgFracaso1').removeClass("hide");
                	}                	
                }
            }
        }); 
		
		/*fin del json*/		
	}
	else {

		$('#msgFracaso').removeClass("hide");
	}
});

$('#limpiarConcurso').click(function (){ 

	document.getElementById('aperturaConcurso').reset();

	$('#spanfechaConcurso').addClass("hide");
	$('#spancedula').addClass("hide");
	$('#spancedula2').addClass("hide");
	$('#spanarea').addClass("hide");
	$('#msgExito').addClass("hide");
	$('#msgFracaso').addClass("hide");
	$('#msgFracaso1').addClass("hide");
	$('#msgPermiso').addClass("hide");
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}

$('#quitar').click(function (){

	$('#msgFracaso').addClass("hide");
});

$('#quitar1').click(function (){

	$('#msgFracaso1').addClass("hide");
});

$('#quitar3').click(function (){

	$('#msgFracaso2').addClass("hide");
});

$('#quitar2').click(function (){

	$('#msgExito').addClass("hide");
});

$('#quitar3').click(function (){

	$('#msgPermiso').addClass("hide");
});