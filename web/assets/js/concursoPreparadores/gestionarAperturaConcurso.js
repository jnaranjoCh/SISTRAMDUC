var id = getParameterByName('id');

$(window).load( function(){
    toastr.clear();
    var text = "";
    $("#dateTime2").datetimepicker();
    $.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_solicitud_concurso",
		dataType: 'json',
		data: {"id": id},
        success: function(respuesta){
			if (respuesta == "FallaConsultaDetalleSolicitud"){
                text = "Falla al consultar el detalle de esta solicitud.";
                toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
            	var i = 0;
            	var contenidoHTML =
					'<dl class="dl-horizontal">'+
						'<dt>Asignatura Solicitante</dt>'+
						'<dd>'+respuesta["AsigSol"][i]+'</dd>'+
						
						'<dt>Número de Plazas</dt>'+
						'<dd>'+respuesta["NroPlz"][i]+'</dd>'+
						
						'<dt>Tema Examen Oral</dt>'+
						'<dd>'+respuesta["ExOral"][i]+'</dd>'+
						
						'<dt>Tema Examen Escrito</dt>'+
						'<dd>'+respuesta["ExEsc"][i]+'</dd>'+
						
						'<dt>Jurado</dt>'+
						'<dd>'+respuesta["Coord"][i]+'</dd>'+
						'<dd>'+respuesta["Ppal1"][i]+'</dd>'+
						'<dd>'+respuesta["Ppal2"][i]+'</dd>'+
						'<dd>'+respuesta["Supl1"][i]+'</dd>'+
						'<dd>'+respuesta["Supl2"][i]+'</dd>'+
					'</dl>';
					
				document.getElementById("juradoCoord").value = respuesta["Coord"][i]
				document.getElementById("juradoPpal1").value = respuesta["Ppal1"][i];
				document.getElementById("juradoPpal2").value = respuesta["Ppal2"][i];
				document.getElementById("juradoSpl1").value = respuesta["Supl1"][i];
				document.getElementById("juradoSpl2").value = respuesta["Supl2"][i];
					
				$('#detallesSolicitudSeleccionada').append(contenidoHTML);
            }
		}
    });
    
    var recaudos = ["AprobarPresupuesto","AprobarJurado","Veredicto","FechaRecepDoc","CambioJurado"];
	$.ajax({
        method: "POST",
        data: {"idTramite":id, "nombresRecaudos":recaudos},
        url: "/web/app_dev.php/preparadores/validar_accion_solicitud",
        dataType: 'json',
        beforeSend: function(){
            $("#cargandoAcciones").modal("show");
        },
        success: function(respuesta){
        	$("#cargandoAcciones").modal("hide");
        	if (respuesta != "") {
    		    $(respuesta).removeClass("hide");
    		}else{
    			text = "No hay acciones permitidas para esta solicitud.";
        		toastr.warning(text, "Alerta!", {"timeOut": "0","extendedTImeout": "0"});
    		}
    }});
});

$('#enviarAprobarPresupuesto').click(function (){ 
	
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsPresupuesto");
	
	toastr.clear();

    for(var i=0;i<optionsRadios.length;i++)
    {
        if(optionsRadios[i].checked)
            optionSelected = optionsRadios[i].value;
    }
	
	if(optionSelected == ""){
        falla = true;
        text = "Debe seleccionar una opción.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"AprobarPresupuesto", "valorRecaudo":optionSelected},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divAprobarPresupuesto').addClass("hide");
        		}
        }});
    }
});

$('#enviarAprobarJurado').click(function (){ 
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsJurado");
	
	toastr.clear();

    for(var i=0;i<optionsRadios.length;i++)
    {
        if(optionsRadios[i].checked)
            optionSelected = optionsRadios[i].value;
    }
	
	if(optionSelected == ""){
        falla = true;
        text = "Debe seleccionar una opción.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"AprobarJurado", "valorRecaudo":optionSelected},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divAprobarJurado').addClass("hide");
    				if (optionSelected == "No") {
    					$('#divCambiarJurado').removeClass("hide");
            		}else if (optionSelected == "Si") {
            			$('#divVeredicto').removeClass("hide");
            		}
        		}
        }});
    }
});

$('#siguienteCambiarJurado').click(function (){ 
	
	var inputs = ["NewJurCoord","NewJurPpal1","NewJurPpal2","NewJurSupl1","NewJurSupl2"];
	var falla = false;
	var text = "";
	
	toastr.clear();
	
	for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            falla = true;
    		$("#div"+inputs[i]).addClass("has-error");
            text = "Dato inválido o faltante.";
        }else{
    		$("#div"+inputs[i]).removeClass("has-error");
        }
    }
    
	if (falla){
		toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
	} else {
		var recaudos = [$("#NewJurCoord").val(),$("#NewJurPpal1").val(),$("#NewJurPpal2").val(),$("#NewJurSupl1").val(),$("#NewJurSupl2").val()];
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"CambioJurado", "valorRecaudo":recaudos},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
	        success: function(respuesta){
	        	if (respuesta.estado == "Insertado") {
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "15","extendedTImeout": "15"});
    				$('#divCambiarJurado').addClass("hide");
    				window.location.reload();
        		}else{
        		    if (respuesta.estado == "NoExisteJurado") {
        		        for(var i = 0; i < respuesta.jurados.length; i++){
            		        $(respuesta.jurados[i]).addClass("has-error");
                		    toastr.error(respuesta.mensaje, "Error!", {"timeOut": "0","extendedTImeout": "0"});
        		        }
            		}
        		}
        }});
    }
});

$('#cargarVeredicto').click(function (){ 
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsVeredicto");
	
	toastr.clear();

    for(var i=0;i<optionsRadios.length;i++)
    {
        if(optionsRadios[i].checked)
            optionSelected = optionsRadios[i].value;
    }
	
	if(optionSelected == ""){
        text = "Debe seleccionar una opción.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
    	if($("#obsVeredicto").val() == ""){
    		text = "Debe escribir alguna observación sobre el veredicto.";
        	toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    	}else{
			$.ajax({
	            method: "POST",
	            data: {"idTramite":id, "nombreRecaudo":"Veredicto", "valorRecaudo":optionSelected},
	            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
	            dataType: 'json',
	            beforeSend: function(){
	                $("#cargando").modal("show");
	            },
		        success: function(respuesta){
	        		if (respuesta.estado == "Insertado") {
	        			$.ajax({
				            method: "POST",
				            data: {"idTramite":id, "nombreRecaudo":"ObservacionVeredicto", "valorRecaudo":$("#obsVeredicto").val()},
				            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
				            dataType: 'json',
				            beforeSend: function(){
				                $("#cargando").modal("show");
				            },
					        success: function(respuesta){
				        		if (respuesta.estado == "Insertado") {
				        		    $("#cargando").modal("hide");
				        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
				    				$('#divVeredicto').addClass("hide");
				        		}
				        }});
	        		}
	        }});
    	}
    }
});

$('#enviarFechaRecepDoc').click(function (){ 
	var falla = false;
	var text = "";
	
	toastr.clear();

	if($("#fecha").val() == ""){
        falla = true;
        text = "Dato inválido o faltante.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"FechaRecepDoc", "valorRecaudo":$("#fecha").val()},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        			$.ajax({
			            method: "POST",
			            data: {"idTramite":id},
			            url: "/web/app_dev.php/preparadores/registrar_concurso",
			            dataType: 'json',
			            beforeSend: function(){
			                $("#cargando").modal("show");
			            },
				        success: function(respuesta){
			        		if (respuesta.estado == "Insertado") {
			        		    $("#cargando").modal("hide");
			        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
			    				$('#divFechaRecepDoc').addClass("hide");
			        		}
			        }});
        		}
        }});
    }
});

/**
 * @param String name
 * @return String
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}