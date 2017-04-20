var id;
var c1 = new Array();
var c2 = new Array();
var c3 = new Array();
var c4 = new Array();
var c5 = new Array();
var inputs = ["cedula", "nombre", "apellido", "facultad", "universidad", "area"];

$('#ingresarCPEC').click(function (){ 
		
	var continua = true;

	if (id == "s1"){

		for (var j = 0; j < inputs.length; j++) {
		
			if ($("#"+inputs[j]).val() == ""){

				$("#span"+inputs[j]).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]).addClass("hide");
			}
		}

		if (continua){

			$('#nombre1').val($('#nombre').val());

			for (var i = 0; i <= 5; i++) c1[i] = $('#'+inputs[i]).val();

			$('#myModal').modal('toggle');
		}		
	}

	continua = true;	

	if (id == "s2"){

		for (var j = 0; j < inputs.length; j++) {
		
			if ($("#"+inputs[j]).val() == ""){

				$("#span"+inputs[j]).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]).addClass("hide");
			}
		}

		if (continua){

			$('#nombre2').val($('#nombre').val());

			for (var i = 0; i <= 5; i++) c2[i] = $('#'+inputs[i]).val();

			$('#myModal').modal('toggle');
		}
	}

	continua = true;

	if (id == "s3"){

		for (var j = 0; j < inputs.length; j++) {
		
			if ($("#"+inputs[j]).val() == ""){

				$("#span"+inputs[j]).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]).addClass("hide");
			}
		}

		if (continua){

			$('#nombre3').val($('#nombre').val());

			for (var i = 0; i <= 5; i++) c3[i] = $('#'+inputs[i]).val();

			$('#myModal').modal('toggle');
		}
	}

	continua = true;

	if (id == "s4"){

		for (var j = 0; j < inputs.length; j++) {
		
			if ($("#"+inputs[j]).val() == ""){

				$("#span"+inputs[j]).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]).addClass("hide");
			}
		}

		if (continua){

			$('#nombre4').val($('#nombre').val());

			for (var i = 0; i <= 5; i++) c4[i] = $('#'+inputs[i]).val();

			$('#myModal').modal('toggle');
		}
	}

	continua = true;

	if (id == "s5"){

		for (var j = 0; j < inputs.length; j++) {
		
			if ($("#"+inputs[j]).val() == ""){

				$("#span"+inputs[j]).removeClass("hide");
				continua = false;
			} 
			else {
				$("#span"+inputs[j]).addClass("hide");
			}
		}

		if (continua){

			$('#nombre5').val($('#nombre').val());

			for (var i = 0; i <= 5; i++) c5[i] = $('#'+inputs[i]).val();

			$('#myModal').modal('toggle');
		}
	}
});

$('#s1').click(function (){ 

	for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val('');

	if (c1[0] != null){

		for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val(c1[i]);
	}

	id = "s1";
});

$('#s2').click(function (){ 

	for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val('');

	if (c2[0] != null){

		for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val(c2[i]);
	}

	id = "s2";
});

$('#s3').click(function (){ 

	for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val('');

	if (c3[0] != null){

		for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val(c3[i]);
	}

	id = "s3";
});

$('#s4').click(function (){ 

	for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val('');

	if (c4[0] != null){

		for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val(c4[i]);
	}

	id = "s4";
});

$('#s5').click(function (){ 

	for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val('');

	if (c5[0] != null){

		for (var i = 0; i <= 5; i++) $('#'+inputs[i]).val(c5[i]);
	}

	id = "s5";
});

$('#registrarCPEC').click(function (){

	$('#msgFracaso').addClass("hide");
	$('#msgFracaso1').addClass("hide");
	$('#msgFracaso2').addClass("hide");
	$('#msgExito').addClass("hide"); 

	var continua = true;

	for (var j = 1; j < 6; j++) {
		
		if ($("#nombre"+j).val() == ""){

			$("#spannombre"+j).removeClass("hide");
			continua = false;
		} 
		else {
			$("#spannombre"+j).addClass("hide");
		}
	}

	if(continua){		

		if (c1[0] != c2[0] && c1[0] != c3[0] && c1[0] != c4[0] &&
			c1[0] != c5[0] && c2[0] != c3[0] && c2[0] != c4[0] &&
			c2[0] != c5[0] && c3[0] != c4[0] && c3[0] != c5[0] && 
			c4[0] != c5[0]){

			//json

			var Arreglo;

			for (var i = 1; i <= 5; i++) {
				
				if (i == 1){

					Arreglo = {"cedula":c1[0], "nombre":c1[1], 
						"tipo":"OposicionCpec", "apellido":c1[2], 
						"facultad":c1[3], "universidad":c1[4], "area":c1[5]};
		        }

		        if (i == 2){

					Arreglo = {"cedula":c2[0], "nombre":c2[1], 
						"tipo":"OposicionCpec", "apellido":c2[2], 
						"facultad":c2[3], "universidad":c2[4], "area":c2[5]};
		        }

		        if (i == 3){

					Arreglo = {"cedula":c3[0], "nombre":c3[1], 
						"tipo":"OposicionCpec", "apellido":c3[2], 
						"facultad":c3[3], "universidad":c3[4], "area":c3[5]};
		        }

		        if (i == 4){

					Arreglo = {"cedula":c4[0], "nombre":c4[1], 
						"tipo":"OposicionCpec", "apellido":c4[2], 
						"facultad":c4[3], "universidad":c4[4], "area":c4[5]};
		        }

		        if (i == 5){

					Arreglo = {"cedula":c5[0], "nombre":c5[1], 
						"tipo":"OposicionCpec", "apellido":c5[2], 
						"facultad":c5[3], "universidad":c5[4], "area":c5[5]};
		        }

				$.ajax({
		            method: "POST",
		            data: Arreglo,
		            url:  "http://localhost:8000/concursoOposicion/registroJuradosAjax",
		            dataType: 'json',
		            success: function(data)
		            {
		                if (data == "S"){

							$('#spannombre'+i).addClass("hide");
							
							$('#span'+inputs[i]).addClass("hide");

		                	$('#msgFracaso').addClass("hide");
		                	$('#msgFracaso1').addClass("hide");
		                	$('#msgFracaso2').addClass("hide");
							$('#msgExito').removeClass("hide");	
		                } 
		                else{

		                	$('#msgFracaso').addClass("hide");
		                	$('#msgExito').addClass("hide");
							$('#msgFracaso1').removeClass("hide");
		                }
		            }
		        });	

		        Arreglo = null;
			}

			for (var i = 0; i <= 5; i++){

				$('#nombre'+i).val('');
				c1[i] = null;
				c2[i] = null;
				c3[i] = null;
				c4[i] = null;
				c5[i] = null;
			}			
			
			//fin json
		}
		else{
			$('#msgFracaso2').removeClass("hide");
		}

	}
	else{

		$("#msgFracaso").removeClass("hide");
	}
});

$('#limpiarJurado').click(function (){ 

	for (var i = 1; i <= 5; i++) {
		
		$('#spannombre'+i).addClass("hide");
		$('#nombre'+i).val('');
	}

	for (var i = 0; i <= 5; i++) $('#span'+inputs[i]).addClass("hide");

	for (var i = 0; i <= 5; i++){

		c1[i] = null;
		c2[i] = null;
		c3[i] = null;
		c4[i] = null;
		c5[i] = null;
	}	

	$('#msgFracaso').addClass("hide");
	$('#msgFracaso1').addClass("hide");
	$('#msgFracaso2').addClass("hide");
	$('#msgExito').addClass("hide");
});