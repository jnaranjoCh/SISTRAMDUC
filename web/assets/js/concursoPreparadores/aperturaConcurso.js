tabla = $('#tabla').DataTable({
"language": {
        "url": "/web/assets/js/datatable-spanish.json"
  },
  columns: [
  	{ "data": "nro" },
	{ "data": "asignaturaSolicitante" },
	{ "data": "nroPlazas" },
	{ "data": "status" },
	{ "data": "acciones" }
	//{ "data": "gestionar" }   
  ]
});
	
var ident;
var posicion;

$(window).load( function(){
    toastr.clear();
    var text = "";

	$.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/listado_solicitud_concurso",
		dataType: 'json',
        success: function(respuesta){
			if (respuesta == "NoHayConcursos"){
                text = "No Hay Solicitudes de Apertura Registradas";
                toastr.info(text, "Información!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
                for (var i = 0; i <= respuesta["id"].length-1; i++) {
                    var id = respuesta["id"][i];
                    var label="";
                    var classGestionar="";
                    if(respuesta["estado"][i]=="Enviada"){
                    	label= "label-primary";
                    }else if(respuesta["estado"][i]=="En Proceso"){
						label= "label-warning";
                    }else if(respuesta["estado"][i]=="Negada"){
						label= "label-danger";
						classGestionar= "hide";
                    }else if(respuesta["estado"][i]=="Aprobada"){
						label= "label-success";
						classGestionar= "hide";
                    }
                    tabla.row.add( {
                    	"nro": respuesta["id"][i],
                        "asignaturaSolicitante": respuesta["asignatura"][i],
                        "nroPlazas": respuesta["plazas"][i],
						"status": '<span class="label '+label+'">'+respuesta["estado"][i]+'</span>',
                        "acciones": '<button type="button" data-target="#detalles" data-toggle="modal" data-tooltip="tooltip" class="btn btn-xs btn-primary" title="Ver Detalles" onclick="javascript:verDetalleSolicitud('+id+')"><i class="fa fa-search"></i></button>'+
                        			'<button type="button" data-tooltip="tooltip" class="btn btn-xs btn-primary '+classGestionar+'" title="Gestionar" onclick="javascript:gestionarSolicitud('+id+')"><i class="fa fa-cogs"></i></button>'
                        			//'<span class="btn btn-xs btn-primary"data-toggle="modal" data-target="#detalles" onclick=""><i class="fa fa-cogs"><a data-toggle="tooltip" data-placement="top" title="Holis!"></a></i></span'
                        			//'<span class="btn btn-xs btn-primary" data-toggle="modal" data-original-title="soy yo ps" data-target="" onclick=""><i class="fa fa-search"></i></span>'+
                        			//'<a class="btn btn-xs btn-primary" data-toggle="tooltip" data-original-title="Gestionar" href="{{ path(\'solicitar\') }}"><i class="fa fa-cogs"></i></a>'
                        //javascript:ver('+id+')
                        //"gestionar": ''
                        //javascript:modificar('+num+','+i+')
                        //data-toggle="modal" data-target="#myModal"
                        //,"elim": '<span class="glyphicon glyphicon-trash" onclick="javascript:eliminar('+num+','+i+')" data-toggle="modal" data-target="#myModal"></span>'
                    }).draw();                          
                }
				$('#accionesHead').removeClass("hide");   
				$('#accionesFoot').removeClass("hide");   
			}                       	
		}
    });
});

function verDetalleSolicitud(id){
	$('#detalles').addClass("hide");
	$("#detallesModalBody").empty();
	$.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_solicitud_concurso",
		dataType: 'json',
		data: {"id": id},
		beforeSend: function(){
            $("#cargando").modal("show");
        },
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
					
				$('#detallesModalBody').append(contenidoHTML);
				$("#cargando").modal("hide");
				$('#detalles').removeClass("hide");
            }
		}
    });
}

function gestionarSolicitud(id){
	window.location.replace("/web/app_dev.php/preparadores/apertura_concurso/gestionar?id="+id);
}