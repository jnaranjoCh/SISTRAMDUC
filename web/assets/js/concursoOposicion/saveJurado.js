$( window ).load(function (){

	$.ajax({
        method: "POST",
        url:  "/concursoOposicion/listadoConcursosAjax",
        dataType: 'json',
        success: function(data)
        {
        	var opcion = "<option id='sel' selected='selected'>...</option>";
 
        	for (var i = 0; i < data["id"].length; i++) {
        		
        		var num = data["id"][i];

        		opcion = opcion+"<option value="+num+"><b>Area:</b> "+data["area"][i]+
        		"   -   <b>Vacantes:</b> "+data["vacantes"][i]
        		+"   -   <b>Fecha Inicio:</b> "+data["inicio"][i]
        		+"</option>";   		
        	}

        	$("#lista").html(opcion);
        }
    });	
});


$('#registrarJurado').click(function (){ 

	toastr.clear();
	var text = "";
	
	var inputs = ["cedula", "nombre", "apellido", "facultad", "universidad", "area"];
	var continua = true;

	if ($("#lista").val() == null || 
		$("#lista").val() == "..." || 
		$("#lista").val() == "") {

		continua = false;
		text = "Concurso vacío";
	}

	for (var i = 1; i < 4; i++) {
		
		for (var j = 0; j < inputs.length; j++) {
			
			if ($("#"+inputs[j]+i).val() == ""){

				$("#span"+inputs[j]+i).removeClass("hide");
				continua = false;
				text = "Campo vacío";
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

				$.ajax({
		            method: "POST",
		            data: {"cedula":$("#cedula"+i).val(), 
		            "nombre":$("#nombre"+i).val(),
		            "tipo":"Oposicion", 
		            "apellido":$("#apellido"+i).val(), 
		            "facultad":$("#facultad"+i).val(), 
		            "universidad":$("#universidad"+i).val(), 
		            "area":$("#area"+i).val(),
		        	"concurso": $("#lista").val()},
		            url:  "/concursoOposicion/registroJuradosAjax",
		            dataType: 'json',
		            success: function(data)
		            {
		                if (data == "S"){

							document.getElementById('juradosSave').reset();				

							toastr.clear();

							text = "Jurados Insertados";

							toastr.success(text, "Exito", {
			                    "timeOut": "0",
			                    "extendedTImeout": "0"
			                 });
		                } 
		                else{

		                	toastr.clear();

		                	if (data == "N")		                		
		                		text = "Usted no tiene permiso";		                	
		                	else
			                	text = "Error al Registrar Jurados";			                		                	
		                	
		                	toastr.error(text, "Error", {
					            "timeOut": "0",
					            "extendedTImeout": "0"
				         	});
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
			toastr.error("Hay Cédulas Repetidas", "Error", {
	            "timeOut": "0",
	            "extendedTImeout": "0"
         	});
		}		
	}
	else {

		toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
         });
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

	toastr.clear();
});