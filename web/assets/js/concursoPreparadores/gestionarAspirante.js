var id = getParameterByName('id');
var idConcurso = getParameterByName('idC');
var comunicacionEscritaUrl;
var cartaConductaUrl;
var reporteNotaUrl;

$(window).load( function(){
    toastr.clear();
    var text = "";
    $("#dateTime2").datetimepicker();
    $.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_aspirante",
		dataType: 'json',
		data: {"id": id},
		beforeSend: function(){
            $("#cargando").modal("show");
        },
        success: function(respuesta){
			if (respuesta == "FallaConsultaDetalleAspirante"){
                text = "Falla al consultar el detalle de este aspirante.";
                toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
            	var i = 0;
            	var contenidoHTML =
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
				
				$('#detalleAspiranteSeleccionado').append(contenidoHTML);
				
                comunicacionEscritaUrl=respuesta["comunicacionEscritaUrl"][i].split("..")[1];
                var comunicacionEscritaNameFile=respuesta["comunicacionEscritaUrl"][i].split("comunicacionEscrita/")[1];
                cartaConductaUrl=respuesta["cartaConductaUrl"][i].split("..")[1];
                var cartaConductaNameFile=respuesta["cartaConductaUrl"][i].split("cartaConducta/")[1];
                reporteNotaUrl=respuesta["reporteNotaUrl"][i].split("..")[1];
                var reporteNotaNameFile=respuesta["reporteNotaUrl"][i].split("reporteNota/")[1];
                
                document.getElementById("captionComunicacionEscrita").value = comunicacionEscritaNameFile;
                $("#ComunicacionEscritaDownload").attr('href',comunicacionEscritaUrl);
                
                document.getElementById("captionCartaConducta").value = cartaConductaNameFile;
                $("#CartaConductaDownload").attr('href',cartaConductaUrl);
                
                document.getElementById("captionReporteNota").value = reporteNotaNameFile;
                $("#ReporteNotaDownload").attr('href',reporteNotaUrl);
                
				$("#archivosAspiranteSeleccionado").removeClass("hide");
				$("#cargando").modal("hide");
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

$('#ComunicacionEscritaView').click(function (){
	$("#iframeViewer").attr('src',comunicacionEscritaUrl);
	$("#viewer").modal("show");
});

$('#CartaConductaView').click(function (){
	$("#iframeViewer").  attr('src',cartaConductaUrl);
	$("#viewer").modal("show");
});

$('#ReporteNotaView').click(function (){
	$("#iframeViewer").attr('src',reporteNotaUrl);
	$("#viewer").modal("show");
});

$('#enviarEvaluarRequisitosAspirante').click(function (){ 
	
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsRequisitos");
	
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
            data: {"idTramite":idConcurso, "idAspirante":id, "nombreDato":"CumpleRequisitos", "valorDato":optionSelected+"CumpleRequisitos"},
            url: "/web/app_dev.php/preparadores/agregar_datos_aspirante",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divEvaluarRequisitosAspirante').addClass("hide");
        		}
        }});
    }
});

$('#enviarExoneracionAspirante').click(function (){ 
	
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsExoneracion");
	
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
            data: {"idTramite":idConcurso, "idAspirante":id, "nombreDato":"Exoneracion", "valorDato":optionSelected+"SolicitaExoneracion"},
            url: "/web/app_dev.php/preparadores/agregar_datos_aspirante",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divExoneracionAspirante').addClass("hide");
        		}
        }});
    }
});

$('#cargarMotivosExoneracion').click(function (){ 
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsMotivosExoneracion");
	
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
    	if($("#expMotivosExoneracion").val() == ""){
    		text = "Debe escribir la exposición de motivos sobre la exoneración.";
        	toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    	}else{
    	    alert("HOlis!!!");
    	    $.ajax({
                method: "POST",
                url: "/web/app_dev.php/preparadores/generar_pdf",
                dataType: 'json',
                beforeSend: function(){
                    $("#cargando").modal("show");
                },
    	        success: function(respuesta){
        		    $("#cargando").modal("hide");
            }});
    // 		$.ajax({
	   //         method: "POST",
	   //         data: {"idTramite":idConcurso, "idAspirante":id, "nombreDato":"MotivosExoneracion", "valorDato":optionSelected+"AprobóExoneracion-"+$("#expMotivosExoneracion").val()},
	   //         url: "/web/app_dev.php/preparadores/agregar_datos_aspirante",
	   //         dataType: 'json',
	   //         beforeSend: function(){
	   //             $("#cargando").modal("show");
	   //         },
		  //      success: function(respuesta){
	   //     		if (respuesta.estado == "Insertado") {
	   //     		    $("#cargando").modal("hide");
	   //     		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
	   // 				$('#divMotivosExoneracion').addClass("hide");
	   //     		}
	   //     }});
    	}
    }
});

$('#registrarCalificacion').click(function (){ 
	
	var inputs = ["NotaExEscrito","NotaExOral"];
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
        var datos = [$("#NotaExEscrito").val(),$("#NotaExOral").val()];
		$.ajax({
            method: "POST",
            data: {"idTramite":idConcurso, "idAspirante":id, "nombreDato":"Notas", "valorDato":datos},
            url: "/web/app_dev.php/preparadores/agregar_datos_aspirante",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divCalificacion').addClass("hide");
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
