$( window ).load( function(){

        toastr.clear();
        var text = "";

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoConcursosAjax",
		dataType: 'json',
        success: function(data)
        {
                if (data == "N"){

                        text = "No Hay Concursos Registrados";

                        toastr.error(text, "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });

                } else{
                var rol = "";

                for (var i = 0; i <= data["vacantes"].length-1; i++) {

                        tabla.row.add( {
                                "inicio": data["inicio"][i],
                                "vacantes": data["vacantes"][i],
                                "area": data["area"][i],
                                "doc": data["recepcion"][i],
                                "pre": data["presentacion"][i]
                        }).draw();
                        
                        /*
                        rol = rol+"<tr><td align=center>"+data["inicio"][i]+"</td>";                    

                        rol = rol+"<td align=right>"+data["vacantes"][i]+"</td>";

                        rol = rol+"<td align=left>"+data["area"][i]+"</td>"; 
                        
                        if (data["recepcion"][i] != null) 
                                rol = rol+"<td align=center>"+data["recepcion"][i]+"</td>"; 
                        else 
                                rol = rol+"<td align=center></td>"; 

                        if (data["presentacion"][i] != null) 
                                rol = rol+"<td align=center>"+data["presentacion"][i]+"</td></tr>"; 
                        else 
                                rol = rol+"<td align=center></td></tr>";
                        */                                 
                }                       

                //$("#cuerpo").html(rol);                        
                }        	
        }
        });
});