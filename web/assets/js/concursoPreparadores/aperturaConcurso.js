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
	                    tabla.row.add( {
	                    	"nro": respuesta["id"][i],
	                        "asignaturaSolicitante": respuesta["asignatura"][i],
	                        "nroPlazas": respuesta["plazas"][i],
	                        "status": '<span class="label label-warning">'+respuesta["estado"][i]+'</span>',
	                        "acciones": '<span class="btn btn-xs btn-primary" data-toggle="modal" data-target="#detalles"><i class="fa fa-search"></i></span>'+
	                        			'<a class="btn btn-xs btn-primary" data-toggle="tooltip" data-original-title="Tooltip on left" href=""><i class="fa fa-cogs"></i></a>'
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