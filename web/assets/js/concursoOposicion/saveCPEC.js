var id;

$('#ingresarCPEC').click(function (){ 
	
	var inputs = ["cedula", "nombre", "apellido", "facultad", "universidad", "area"];
		
	for (var j = 0; j < inputs.length; j++) {
		
		if ($("#"+inputs[j]).val() == ""){

			$("#span"+inputs[j]).removeClass("hide");
		} 
		else {
			$("#span"+inputs[j]).addClass("hide");
		}
	}

	if (id == "s1"){
		document.getElementById("nombre1").setAttribute("value", $('#nombre').val());
	}	

	if (id == "s2"){
		document.getElementById("nombre2").setAttribute("value", $('#nombre').val());
	}

	if (id == "s3"){
		document.getElementById("nombre3").setAttribute("value", $('#nombre').val());
	}

	if (id == "s4"){
		document.getElementById("nombre4").setAttribute("value", $('#nombre').val());
	}

	if (id == "s5"){
		document.getElementById("nombre5").setAttribute("value", $('#nombre').val());
	}

	/*
	no es funcionando la limpiada
	 */

	 document.getElementById('cedula').setAttribute("value", "");
	 document.getElementById('nombre').setAttribute("value", "");
	 document.getElementById('apellido').setAttribute("value", "");
	 document.getElementById('facultad').setAttribute("value", "");
	 document.getElementById('universidad').setAttribute("value", "");
	 document.getElementById('area').setAttribute("value", "");
});

$('#s1').click(function (){ 
	
	id = "s1";
});

$('#s2').click(function (){ 

	id = "s2";
});

$('#s3').click(function (){ 

	id = "s3";
});

$('#s4').click(function (){ 

	id = "s4";
});

$('#s5').click(function (){ 

	id = "s5";
});

$('#registrarCPEC').click(function (){ 

	for (var j = 1; j < 6; j++) {
		
		if ($("#nombre"+j).val() == ""){

			$("#spannombre"+j).removeClass("hide");
		} 
		else {
			$("#spannombre"+j).addClass("hide");
		}
	}

});