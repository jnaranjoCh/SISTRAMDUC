$('#registrar').click(function (){ 

	var continua = true;
	var fecha = $('#fecha').val();

	if (fecha == ""){
		continua = false;
		$('#fechaSpam').removeClass("hide");
	} else {

		$('#aspitanteSpam').addClass("hide");
		$('#fechaSpam').addClass("hide");
		$('#juradoSpam').addClass("hide");
		$('#msgExito').addClass("hide");
		$('#msgFracaso1').addClass("hide");
		$('#msgFracaso2').addClass("hide");
		$('#msgPermiso').addClass("hide");
		$('#msgAspirante').addClass("hide");
		$('#msgJurado').addClass("hide");
	}

	if ($('#aspirante').val() == ""){
		continua = false;
		$('#aspitanteSpam').removeClass("hide");
	} else {

		$('#aspitanteSpam').addClass("hide");
		$('#juradoSpam').addClass("hide");
		$('#msgExito').addClass("hide");
		$('#msgFracaso1').addClass("hide");
		$('#msgFracaso2').addClass("hide");
		$('#msgPermiso').addClass("hide");
		$('#msgAspirante').addClass("hide");
		$('#msgJurado').addClass("hide");
	}

	if ($('#jurado').val() == ""){
		continua = false;
		$('#juradoSpam').removeClass("hide");
	} else {

		$('#juradoSpam').addClass("hide");
		$('#msgExito').addClass("hide");
		$('#msgFracaso1').addClass("hide");
		$('#msgFracaso2').addClass("hide");
		$('#msgPermiso').addClass("hide");
		$('#msgAspirante').addClass("hide");
		$('#msgJurado').addClass("hide");
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
					$('#msgExito').removeClass("hide");
					$('#msgFracaso').addClass("hide");
					$('#msgFracaso1').addClass("hide");
					$('#msgFracaso2').addClass("hide");
					$('#msgPermiso').addClass("hide");
					$('#msgAspirante').addClass("hide");
					$('#msgJurado').addClass("hide");

				} else {
					if (data == "N") {

						$('#msgExito').addClass("hide");
						$('#msgFracaso').addClass("hide");
						$('#msgFracaso1').addClass("hide");
						$('#msgFracaso2').addClass("hide");
						$('#msgPermiso').removeClass("hide");
						$('#msgAspirante').addClass("hide");
						$('#msgJurado').addClass("hide");
					}
					else {

						if (data == "A") {

							$('#msgExito').addClass("hide");
							$('#msgFracaso').addClass("hide");
							$('#msgFracaso1').addClass("hide");
							$('#msgFracaso2').addClass("hide");
							$('#msgPermiso').addClass("hide");
							$('#msgAspirante').removeClass("hide");
							$('#msgJurado').addClass("hide");
						}
						else{

							if (data == "J") {

								$('#msgExito').addClass("hide");
								$('#msgFracaso').addClass("hide");
								$('#msgFracaso1').addClass("hide");
								$('#msgFracaso2').addClass("hide");
								$('#msgPermiso').addClass("hide");
								$('#msgAspirante').addClass("hide");
								$('#msgJurado').removeClass("hide");
							}
							else {

								$('#msgExito').addClass("hide");
								$('#msgFracaso').addClass("hide");
								$('#msgFracaso1').removeClass("hide");
								$('#msgFracaso2').addClass("hide");
								$('#msgPermiso').addClass("hide");
								$('#msgAspirante').addClass("hide");
								$('#msgJurado').addClass("hide");
							}
						}						
					}
				}
			}
		});

		/*fin json*/

	} else{
		$('#msgFracaso').removeClass("hide");
	}
});

$('#limpiarRecusacion').click(function (){ 

	document.getElementById('recusa').reset();

	$('#aspitanteSpam').addClass("hide");
	$('#juradoSpam').addClass("hide");
	$('#fechaSpam').addClass("hide");
	$('#msgExito').addClass("hide");
	$('#msgFracaso').addClass("hide");
	$('#msgFracaso1').addClass("hide");
	$('#msgFracaso2').addClass("hide");
	$('#msgPermiso').addClass("hide");
	$('#msgAspirante').addClass("hide");
	$('#msgJurado').addClass("hide");
});

$('#quitar4').click(function (){

	$('#msgAspirante').addClass("hide");
});

$('#quitar5').click(function (){

	$('#msgJurado').addClass("hide");
});