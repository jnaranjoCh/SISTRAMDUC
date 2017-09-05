var id = getParameterByName('id');

aspirantes = $('#aspirantes').DataTable({
"language": {
        "url": "/web/assets/js/datatable-spanish.json"
  },
  columns: [
  	{ "data": "id" },
	{ "data": "nombreCompleto" },
	{ "data": "correo" },
	{ "data": "telefono" },
	{ "data": "acciones" }
  ]
});

$('#aspirantes tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('bg-info') ) {
        $(this).removeClass('bg-info');
    }else {
        aspirantes.$('tr.bg-info').removeClass('bg-info');
        $(this).addClass('bg-info');
    }
} );

$(window).load( function(){
    toastr.clear();
    var text = "";
    $("#dateTime1").datetimepicker();
    $.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_concurso",
		dataType: 'json',
		data: {"id": id},
        success: function(respuesta){
			if (respuesta == "FallaConsultaDetalleConcurso"){
                text = "Falla al consultar el detalle de este concurso.";
                toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
            	var i = 0;
            	contenidoHTML =
					'<dl class="col-md-offset-2 dl-horizontal">'+
						'<dt>Asignatura</dt>'+
						'<dd>'+respuesta["asignatura"][i]+'</dd>'+
						
						'<dt>Número de Vacantes</dt>'+
						'<dd>'+respuesta["vacantes"][i]+'</dd>'+
						
						'<dt>Número de Aspirantes</dt>'+
						'<dd>'+respuesta["aspirantes"][i]+'</dd>'+
						
						'<dt>Tema Examen Oral</dt>'+
						'<dd>'+respuesta["exOral"][i]+'</dd>'+
						
						'<dt>Tema Examen Escrito</dt>'+
						'<dd>'+respuesta["exEscrito"][i]+'</dd>'+
					'</dl>'+ 
						'<div class="text-center">'+
							'<u><b>Jurados</b></u>'+
						'</div>'+
					'<dl class="col-md-offset-2 dl-horizontal">'+
						'<dt>Coordinador</dt>'+
						'<dd>'+respuesta["Coordinador"][i]+'</dd>'+
						'<dt>Principales</dt>';
						
				if(respuesta["Principal"].length > 1){
					for (var p = respuesta["Principal"].length-1; p >=0; p--) {
						contenidoHTML = contenidoHTML+
							'<dd>'+respuesta["Principal"][p]+'</dd>';
					}
				}else{
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["Principal"][i]+'</dd>'+
						'<dd>'+respuesta["Principal"][i]+'</dd>';
				}
				
				contenidoHTML = contenidoHTML+ '<dt>Suplentes</dt>';
				
				if(respuesta["Suplente"].length > 1){
					for (var s = respuesta["Suplente"].length-1; s >=0; s--) {
						contenidoHTML = contenidoHTML+
							'<dd>'+respuesta["Suplente"][s]+'</dd>';
					}
				}else{
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["Suplente"][i]+'</dd>'+
						'<dd>'+respuesta["Suplente"][i]+'</dd>';
				}
				var stringify = '['+JSON.stringify(respuesta["fechaRecepDoc"][i])+']';
				var parse = JSON.parse(stringify);
				var date = parse[i].date;
				var withOutTime = date.split(" ");
				var insteadMonthDay = withOutTime[0].split("-");
				var fechaRecepDoc = insteadMonthDay[1] + "/"+ insteadMonthDay[2] + "/"+ insteadMonthDay[0];
				
				
				contenidoHTML = contenidoHTML+
					'</dl>'+
						'<div class="text-center">'+
							'<u><b>Fechas Importantes</b></u>'+
						'</div>'+
					'<dl class="col-md-offset-2 dl-horizontal">';
				
				contenidoHTML = contenidoHTML+
						'<dt>Recepción de Doc.</dt>'+
						'<dd>'+fechaRecepDoc+'</dd>'
					+'</dl>';
					
				$('#detallesSolicitudSeleccionada').append(contenidoHTML);
            }
		}
    });
    
 //   var recaudos = ["AprobarPresupuesto","AprobarJurado","Veredicto","FechaRecepDoc","CambioJurado"];
	// $.ajax({
 //       method: "POST",
 //       data: {"idTramite":id, "nombresRecaudos":recaudos},
 //       url: "/web/app_dev.php/preparadores/validar_accion_solicitud",
 //       dataType: 'json',
 //       beforeSend: function(){
 //           $("#cargandoAcciones").modal("show");
 //       },
 //       success: function(respuesta){
 //       	$("#cargandoAcciones").modal("hide");
 //       	if (respuesta != "") {
 //   		    $(respuesta).removeClass("hide");
 //   		}else{
 //   			text = "No hay acciones permitidas para esta solicitud.";
 //       		toastr.warning(text, "Alerta!", {"timeOut": "0","extendedTImeout": "0"});
 //   		}
 //   }});
});

$('#cargarConcursoDesierto').click(function (){ 
	var text = "";
	toastr.clear();
	$('#datepicker').datetimepicker();
	
	if($("#causalDesierto").val() == ""){
		$("#divCausalDesierto").addClass("has-error");
		text = "Debe ingresar la causa para Concurso Desierto.";
    	toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
	}else{
		$("#divCausalDesierto").removeClass("has-error");
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"CausalDesierto", "valorRecaudo":$("#causalDesierto").val()},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_concurso",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divConcursoDesierto').addClass("hide");
        		}
        }});
	}
});

$('#enviarFechaEvaluacion').click(function (){ 
	var falla = false;
	var text = "";
	
	toastr.clear();

	if($("#fecha").val() == ""){
		$("#divFecha").addClass("has-error");
        falla = true;
        text = "Dato inválido o faltante.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
    	$("#divFecha").removeClass("has-error");
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"FechaEvaluacion", "valorRecaudo":$("#fecha").val()},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_concurso",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divFechaEvaluacion').addClass("hide");
        		}
        }});
    }
});

$('#cargarConcursesierto').click(function (){ 
	
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