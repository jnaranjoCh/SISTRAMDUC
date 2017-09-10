tabla = $('#tabla').DataTable({
"language": {
        "url": "/web/assets/js/datatable-spanish.json"
  },
  columns: [
  	{ "data": "nro" },
	{ "data": "asignatura" },
	{ "data": "status" },
	{ "data": "acciones" }
  ]
});
	
var ident;
var posicion;

$(window).load( function(){
    toastr.clear();
    var text = "";

	$.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/listado_concurso",
		dataType: 'json',
        success: function(respuesta){
			if (respuesta == "NoHayConcursos"){
                text = "No Hay Concursos Registrados";
                toastr.info(text, "Información!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
                for (var i = 0; i <= respuesta["id"].length-1; i++) {
                    var id = respuesta["id"][i];
                    var classSpan="";
                    var classGestionar="";
                    if(respuesta["estado"][i]=="Enviada"){
                    	classSpan= "label-primary";
                    }else if(respuesta["estado"][i]=="En Proceso"){
						classSpan= "label-warning";
                    }else if(respuesta["estado"][i]=="Aprobada"){
						classSpan= "label-success";
                    }else if(respuesta["estado"][i]=="Negada"){
						classSpan= "label-danger";
						classGestionar= "hide";
                    }else if(respuesta["estado"][i]=="Finalizada"){
                    	classSpan= "label-success";
						classGestionar= "hide";
                    }
                    tabla.row.add( {
                    	"nro": respuesta["id"][i],
                        "asignatura": respuesta["asignatura"][i],
						"status": '<span class="label '+classSpan+'">'+respuesta["estado"][i]+'</span>',
                        "acciones": '<button type="button" data-target="#detalles" data-toggle="modal" data-tooltip="tooltip" class="btn btn-xs btn-primary" title="Ver Detalles" onclick="javascript:verDetalleConcurso('+id+')"><i class="fa fa-search"></i></button>'+
                        			'<button type="button" data-tooltip="tooltip" class="btn btn-xs btn-primary '+classGestionar+'" title="Gestionar" onclick="javascript:gestionarConcurso('+id+')"><i class="fa fa-cogs"></i></button>'
                    }).draw();                          
                }
				$('#accionesHead').removeClass("hide");   
				$('#accionesFoot').removeClass("hide");   
			}                       	
		}
    });
});

function verDetalleConcurso(id){
	var contenidoHTML; 
	$('#detalles').addClass("hide");
	$("#detallesModalBody").empty();
	$.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_concurso",
		dataType: 'json',
		data: {"id": id},
		beforeSend: function(){
            $("#cargando").modal("show");
        },
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
						
						'<dt>Número de Aspirantes</dt>';
						
				if(respuesta["idAspirante"].length == 1 && respuesta["idAspirante"][i] == 0){
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["idAspirante"][i]+'</dd>';
				}else{
					contenidoHTML = contenidoHTML+
						'<dd>'+respuesta["idAspirante"].length+'</dd>';
				}
				
				contenidoHTML = contenidoHTML+
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
				
				$('#detallesModalBody').append(contenidoHTML);
				$("#cargando").modal("hide");
				$('#detalles').removeClass("hide");	
            }
		}
    });
}

function gestionarConcurso(id){
	window.location.replace("/web/app_dev.php/preparadores/concurso/gestionar?id="+id);
}