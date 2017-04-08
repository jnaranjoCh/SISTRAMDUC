$('#registrarConcurso').click(function (){ 
	
	var fecha = $('#fechaConcurso').val();
	var continua = true;

	if (fecha == ""){

		continua = false;
		$('#spanfechaConcurso').removeClass("hide");
	}

	var cedula = $('#cedula').val();

	if (cedula == ""){

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

		$('#msgExito').removeClass("hide");
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

$('#quitar2').click(function (){

	$('#msgExito').addClass("hide");
});