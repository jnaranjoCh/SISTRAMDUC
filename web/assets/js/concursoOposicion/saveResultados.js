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
