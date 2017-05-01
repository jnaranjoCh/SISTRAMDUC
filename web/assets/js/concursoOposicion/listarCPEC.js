$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoCPECAjax",
		dataType: 'json',
        success: function(data)
        {
                if (data == "N"){

                        text = "No Hay Jurados Registrados";

                        toastr.error(text, "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                } else{

                    for (var i = 0; i <= data["nombre"].length-1; i++) {

                        tabla.row.add( {
                                "nombre": data["nombre"][i],
                                "apellido": data["apellido"][i],
                                "areainvestigacion": data["areainvestigacion"][i],
                                "facultad": data["facultad"][i],
                                "universidad": data["universidad"][i]
                        }).draw();                               
                    }                                           
                }        	
        }
        });
});