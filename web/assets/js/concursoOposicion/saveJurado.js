$('#registrarJurado').click(function (){ 
	
	var inputs = ["cedula", "nombre", "apellido", "facultad", "universidad", "area"];
	var continua = true;

	for (var i = 1; i < 4; i++) {
		
		for (var j = 0; j < inputs.length; j++) {
			
			if ($("#"+inputs[j]+i).val() == ""){

				$("#span"+inputs[j]+i).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]+i).addClass("hide");					
			}				
		}
	}

	if (continua){

		if ($("#cedula1").val() != $("#cedula2").val() &&
			$("#cedula1").val() != $("#cedula3").val() &&
			$("#cedula2").val() != $("#cedula3").val()){

			/*json*/

			for (var i = 1; i <= 3; i++) {

				//8980725

				$.ajax({
		            method: "POST",
		            data: {"cedula":$("#cedula"+i).val(), 
		            "nombre":$("#nombre"+i).val(),
		            "tipo":"Oposicion", 
		            "apellido":$("#apellido"+i).val(), 
		            "facultad":$("#facultad"+i).val(), 
		            "universidad":$("#universidad"+i).val(), 
		            "area":$("#area"+i).val()},
		            url:  "/concursoOposicion/registroJuradosAjax",
		            dataType: 'json',
		            success: function(data)
		            {
		                if (data == "S"){

							document.getElementById('juradosSave').reset();				

							$('#msgFracaso').addClass("hide");
		                	$('#msgFracaso1').addClass("hide");
		                	$('#msgFracaso2').addClass("hide");
		                	$('#msgPermiso').addClass("hide");
							$('#msgExito').removeClass("hide");
		                } 
		                else{

		                	if (data == "N"){

		                		$('#msgFracaso2').addClass("hide");
			                	$('#msgPermiso').addClass("hide");
		                		$('#msgFracaso').addClass("hide");
			                	$('#msgExito').addClass("hide");
								$('#msgFracaso1').removeClass("hide");
		                	}
		                	else {

			                	$('#msgFracaso2').addClass("hide");
			                	$('#msgPermiso').addClass("hide");
		                		$('#msgFracaso').addClass("hide");
			                	$('#msgExito').addClass("hide");
								$('#msgFracaso1').removeClass("hide");
		                	}		                	
		                }

		                for (var k = 1; k <= 3; k++) {
				
							$('#spancedula'+k).addClass("hide");
							$('#spannombre'+k).addClass("hide");
							$('#spanapellido'+k).addClass("hide");
							$('#spanfacultad'+k).addClass("hide");
							$('#spanuniversidad'+k).addClass("hide");
							$('#spanarea'+k).addClass("hide");
						}
		            }
		        });			
			}

			/*fin del json*/
		}
		else {
			$('#msgFracaso2').removeClass("hide");
		}		
	}
	else {

		$('#msgFracaso').removeClass("hide");
	}
});

$('#limpiarJurado').click(function (){ 

	document.getElementById('juradosSave').reset();

	for (var i = 1; i <= 3; i++) {
		
		$('#spancedula'+i).addClass("hide");
		$('#spannombre'+i).addClass("hide");
		$('#spanapellido'+i).addClass("hide");
		$('#spanfacultad'+i).addClass("hide");
		$('#spanuniversidad'+i).addClass("hide");
		$('#spanarea'+i).addClass("hide");
	}

	$('#msgFracaso').addClass("hide");
	$('#msgFracaso1').addClass("hide");
	$('#msgExito').addClass("hide");
});