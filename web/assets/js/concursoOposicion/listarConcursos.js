$( window ).load( function(){

	$.ajax({

		method:"POST",
		url: "/concursoOposicion/listadoConcursosAjax",
		dataType: 'json',
        success: function(data)
        {
        	var rol = "";

        	for (var i = 0; i <= data["vacantes"].length-1; i++) {
        		
        		if (data["inicio"][i] == null) rol = rol+"<tr><td align=center></td>";
        		else rol = rol+"<tr><td align=center>"+data["inicio"][i]+"</td>";       		

        		if (data["vacantes"][i] != null) rol = rol+"<td align=right>"+data["vacantes"][i]+"</td>";
        		else rol = rol+"<td align=center></td>";

        		if (data["area"][i] != null) rol = rol+"<td align=left>"+data["area"][i]+"</td>"; 
        		else rol = rol+"<td align=center></td>"; 
        		
        		if (data["recepcion"][i] != null) rol = rol+"<td align=center>"+data["recepcion"][i]+"</td>"; 
        		else rol = rol+"<td align=center></td>"; 

        		if (data["presentacion"][i] != null) rol = rol+"<td align=center>"+data["presentacion"][i]+"</td></tr>"; 
        		else rol = rol+"<td align=center></td></tr>"; 
        	}

        	$("#cuerpo").html(rol);
        }
	});
});