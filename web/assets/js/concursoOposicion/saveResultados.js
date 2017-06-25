var ident;

$( window ).load(function (){

	$.ajax({
        method: "POST",
        //url:  "{{ path('listadoConcursosAjax') }}",
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

$('#buscar').click(function(){
	
	toastr.clear();
	var text = "";
	var continua = true;
	
	if ($("#lista").val() == null || 
		$("#lista").val() == "..." || 
		$("#lista").val() == "") {

		continua = false;
		text = "Concurso vacío";
	}
	
	if (continua){
		
		$.ajax({

			method:"POST",
			//url: "{{ path('listadoAspiranteAjax') }}",
			url:  "/concursoOposicion/listadoAspiranteAjax",
			data: {"resul": "si", "concurso": $("#lista").val()},
			dataType: 'json',
	        success: function(data)
	        {
	            if (data == "N"){

	                text = "No Hay Aspirantes Registrados";

	                toastr.error(text, "Error", {
	                    "timeOut": "0",
	                    "extendedTImeout": "0"
	                 });

	            } else{                   

	                for (var i = 0; i <= data["id"].length-1; i++) {

	                    var num = data["cedula"][i];
	                    
	                    tabla.row.add( {
	                        "nombre": data["nombre1"][i]+" "+data["nombre2"][i],
	                        "apellido": data["apellido1"][i]+" "+data["apellido2"][i],
	                        "cedula": data["cedula"][i],
	                        "telefono": data["tlf1"][i],
	                        "correo": data["correo"][i],
	                        "profesion": data["titulo"][i],
	                        "modif": "<span class='glyphicon glyphicon-search' onclick='javascript:notas("+num+")'></span>"
	                    }).draw();                      
	                } 
	                
	                $('#jurado').removeClass("hide");
	            }                       	
	        }
	    });	
		
	} else {
		toastr.error(text, "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
         });
	}
});

function notas(id) {
	
	ident = id;
	
	$('#vista').removeClass("hide");
	
	$('#cedula').val(ident);
	
	$('#estado').val("No hay Notas Registradas");
}
