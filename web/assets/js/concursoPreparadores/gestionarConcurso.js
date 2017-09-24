var id = getParameterByName('id');
var nroAspirantes;

aspirantes = $('#aspirantes').DataTable({
"language": {
        "url": "/web/assets/js/datatable-spanish.json"
  },
  columns: [
  	{ "data": "cedula" },
	{ "data": "nombreCompleto" },
	{ "data": "correo" },
	{ "data": "telefono" },
	{ "data": "acciones" }
  ]
});

/**ESTO ES PARA MARCAR FILASELECCIONADA**/
// $('#aspirantes tbody').on( 'click', 'tr', function () {
//     if ( $(this).hasClass('bg-info') ) {
//         $(this).removeClass('bg-info');
//     }else {
//         aspirantes.$('tr.bg-info').removeClass('bg-info');
//         $(this).addClass('bg-info');
//     }
// } );

$(window).load( function(){
    toastr.clear();
    var status = getParameterByName('state');
    if(status == "success")
        toastr.success("Datos registrados exitosamente!.", "Éxito!", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    else if(status == "error")
        toastr.error("Hubo problemas al subir los archivos!", "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    var text = "";
    $("#dateTime1").datetimepicker();
    $("#EscritaConductaReporteCargaDatos").fileinput({
        language: "es"
    });
    document.getElementById("IdConcurso").value = id;
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
					'<dl class="dl-horizontal">'+
						'<dt>Asignatura:</dt>'+
						'<dd>'+respuesta["asignatura"][i]+'</dd>'+
						
						'<dt>Número de Vacantes:</dt>'+
						'<dd>'+respuesta["vacantes"][i]+'</dd>'+
						
						'<dt>Número de Aspirantes:</dt>';
						
				if(respuesta["idAspirante"].length == 1 && respuesta["idAspirante"][i] == 0){
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["idAspirante"][i]+'</dd>';
					nroAspirantes = respuesta["idAspirante"][i];
				}else{
					$('#accionesHead').removeClass("hide");   
					$('#accionesFoot').removeClass("hide");   
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["idAspirante"].length+'</dd>';
					nroAspirantes = respuesta["idAspirante"].length;
				}
				
				contenidoHTML = contenidoHTML+
						'<dt>Tema Examen Oral:</dt>'+
						'<dd>'+respuesta["exOral"][i]+'</dd>'+
						
						'<dt>Tema Examen Escrito:</dt>'+
						'<dd>'+respuesta["exEscrito"][i]+'</dd>'+
						
						'<dt><u><b>Jurados</b></u></dt>'+
						'<dd></dd>'+
						'<dt>Coordinador:</dt>'+
						'<dd>'+respuesta["Coordinador"][i]+'</dd>'+
						'<dt>Principales:</dt>';
						
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
				
				contenidoHTML = contenidoHTML+ '<dt>Suplentes:</dt>';
				
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
						'<dt><u><b>Fechas Importantes</b></u></dt>'+
						'<dd></dd>'+
						'<dt>Recepción de Doc.:</dt>'+
						'<dd>'+fechaRecepDoc+'</dd>'+
					'</dl>';
					
				$('#detalleConcursoSeleccionado').append(contenidoHTML);
				
				for (var i = 0; i <= respuesta["idAspirante"].length-1; i++) {
                    var idAspirante = respuesta["idAspirante"][i];
                    aspirantes.row.add( {
                    	"cedula": respuesta["cedula"][i],
                        "nombreCompleto": respuesta["nombreCompleto"][i],
                        "correo": respuesta["correo"][i],
                        "telefono": respuesta["telefono"][i],
                        "acciones": '<button type="button" data-target="#detalles" data-toggle="modal" data-tooltip="tooltip" class="btn btn-xs btn-primary" title="Ver Detalles" onclick="javascript:verDetalleAspirante('+idAspirante+')"><i class="fa fa-search"></i></button>'+
                        			'<button type="button" data-tooltip="tooltip" class="btn btn-xs btn-primary" title="Gestionar" onclick="javascript:gestionarAspirante('+idAspirante+')"><i class="fa fa-cogs"></i></button>'
                    }).draw();                          
                }
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

$('#closeAspirantes').click(function (){ 
	if($('#iconCloseAspirantes').hasClass("fa-plus")){
		if(nroAspirantes == 0){
			text = "No hay Aspirantes Registrados en este Concurso";
	        toastr.info(text, "Información!", {"timeOut": "0","extendedTImeout": "0"});
		}
	}
});

$('#cargarConcursoDesierto').click(function (){ 
	var text = "";
	toastr.clear();
	
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
                $("#guardandoDatos").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#guardandoDatos").modal("hide");
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
                $("#guardandoDatos").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#guardandoDatos").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divFechaEvaluacion').addClass("hide");
        		}
        }});
    }
});

$("#guardarAspirante").click(function (){
	var inputs = ["Cedula","Correo","PrimerNombre","SegundoNombre","PrimerApellido","SegundoApellido","Promedio","Nota1","Nota2"];
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
		if($("#EscritaConductaReporteCargaDatos").fileinput("getFilesCount") > 3 || $("#EscritaConductaReporteCargaDatos").fileinput("getFilesCount") < 3){
	        falla = true;
	        // $("#spanEscritaConductaReporteCargaDatos").addClass("glyphicon-remove");
	        $("#divEscritaConductaReporteCargaDatos").addClass("has-error");
	        text = "Son sólo tres archivos en el orden especificado (Comunicación Escrita, Carta de Conducta y Reporte de Notas).";
	    }
	    if (falla){
			toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
		} else {
		    $("#guardandoDatos").modal("show");
		    document.getElementById("newAspiranteForm").submit();
		}
	}
});

function verDetalleAspirante(idAspirante){
	var contenidoHTML; 
	$('#detalles').addClass("hide");
	$("#detallesModalBody").empty();
	$.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_aspirante",
		dataType: 'json',
		data: {"id": idAspirante},
		beforeSend: function(){
            $("#cargando").modal("show");
        },
        success: function(respuesta){
			if (respuesta == "FallaConsultaDetalleAspirante"){
                text = "Falla al consultar el detalle de este aspirante.";
                toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
            	var i = 0;
            	contenidoHTML =
					'<dl class="dl-horizontal">'+
						'<dt>Cédula:</dt>'+
						'<dd>'+respuesta["cedula"][i]+'</dd>'+
						
						'<dt>Nombre Completo:</dt>'+
						'<dd>'+respuesta["nombreCompleto"][i]+'</dd>'+
						
						'<dt>Correo:</dt>'+
						'<dd>'+respuesta["correo"][i]+'</dd>'+
						
						'<dt>Teléfonos:</dt>'+
						'<dd>'+respuesta["telefono"][i]+'</dd>';
						
				if(respuesta["telefonoSecundario"][i] != ""){
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["telefonoSecundario"][i]+'</dd>';
				}
					contenidoHTML = contenidoHTML+
						'<dt>Promedio Académico:</dt>'+
						'<dd>'+respuesta["promedioAcademico"][i]+'</dd>'+
						
						'<dt>Nota Intento 1:</dt>'+
						'<dd>'+respuesta["notaIntento1"][i]+'</dd>';
						
				if(respuesta["notaIntento2"][i] != ""){
					contenidoHTML = contenidoHTML+
						'<dt>Nota Intento 2:</dt>'+
						'<dd>'+respuesta["notaIntento2"][i]+'</dd>';
				}
				
				contenidoHTML = contenidoHTML+	
					'</dl>';
				
				$('#detallesModalBody').append(contenidoHTML);
				$("#cargando").modal("hide");
				$('#detalles').removeClass("hide");	
            }
		}
    });
}

function gestionarAspirante(idAspirante){
	window.location.replace("/web/app_dev.php/preparadores/concurso/aspirante/gestionar?idC="+id+"?id="+idAspirante);
}

$('#cargarProposicionNombramiento').click(function (){ 
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
			// $.ajax({
	  //          method: "POST",
	  //          data: {"idTramite":id, "nombreRecaudo":"Veredicto", "valorRecaudo":optionSelected},
	  //          url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
	  //          dataType: 'json',
	  //          beforeSend: function(){
	  //              $("#cargando").modal("show");
	  //          },
		 //       success: function(respuesta){
	  //      		if (respuesta.estado == "Insertado") {
	  //      			$.ajax({
			// 	            method: "POST",
			// 	            data: {"idTramite":id, "nombreRecaudo":"ObservacionVeredicto", "valorRecaudo":$("#obsVeredicto").val()},
			// 	            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
			// 	            dataType: 'json',
			// 	            beforeSend: function(){
			// 	                $("#cargando").modal("show");
			// 	            },
			// 		        success: function(respuesta){
			// 	        		if (respuesta.estado == "Insertado") {
			// 	        		    $("#cargando").modal("hide");
			// 	        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
			// 	    				$('#divVeredicto').addClass("hide");
			// 	        		}
			// 	        }});
	  //      		}
	  //      }});
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