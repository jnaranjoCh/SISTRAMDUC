var id = getParameterByName('id');

$(window).load( function(){
	toastr.clear();
	
	if(id != ""){
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
	            	if(respuesta["Estado"][i] == "Enviada"){
	            		$('#titleUpdate').removeClass("hide");
	            		$('#iconUpdate').removeClass("hide");
	            		$('#closeCargaRequisitos').click();
	            		
	            		document.getElementById("AsigSol").value = respuesta["AsigSol"][i];
	            		document.getElementById("NroPlz").value = respuesta["NroPlz"][i];
	            		document.getElementById("TemExOral").value = respuesta["ExOral"][i];
	            		document.getElementById("TemExEsc").value = respuesta["ExEsc"][i];
	            		document.getElementById("JurCoord").value = respuesta["Coord"][i];
	            		document.getElementById("JurPpal1").value = respuesta["Ppal1"][i];
	            		document.getElementById("JurPpal2").value = respuesta["Ppal2"][i];
	            		document.getElementById("JurSupl1").value = respuesta["Supl1"][i];
	            		document.getElementById("JurSupl2").value = respuesta["Supl2"][i];
	            	}else{
	            		id="";
	            		$('#titleNew').removeClass("hide");
						$('#iconNew').removeClass("hide");
						text = "No se puede actualizar la solicitud seleccionada, pues ya se inició la gestion de la misma.";
						toastr.warning(text, "Advertencia!", {"timeOut": "0","extendedTImeout": "0"});
	            	}
	            }
			}
    	});
	}else{
		$('#titleNew').removeClass("hide");
		$('#iconNew').removeClass("hide");
	}
});

$('#cargarSolicConcurso').click(function (){ 
	
	var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
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
		$.ajax({
            method: "POST",
            data: {"IdTramite": id, "AsigSol":$("#AsigSol").val(), "NroPlz":$("#NroPlz").val(), "ExOral":$("#TemExOral").val(), "ExEsc":$("#TemExEsc").val(), 
    			"Coord":$("#JurCoord").val(), "Ppal1":$("#JurPpal1").val(), "Ppal2":$("#JurPpal2").val(), "Supl1":$("#JurSupl1").val(), "Supl2":$("#JurSupl2").val()},
            url: "/web/app_dev.php/preparadores/registrar_solicitud",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
        		    id="";
        		    $('#titleUpdate').addClass("hide");
            		$('#iconUpdate').addClass("hide");
            		$('#titleNew').removeClass("hide");
            		$('#iconNew').removeClass("hide");
    				$('#closeCargaRequisitos').click();
        		}else{
        		    if (respuesta.estado == "NoExisteJurado") {
        		    	$("#cargando").modal("hide");
        		        for(var i = 0; i < respuesta.jurados.length; i++){
            		        $(respuesta.jurados[i]).addClass("has-error");
                		    toastr.error(respuesta.mensaje, "Error!", {"timeOut": "0","extendedTImeout": "0"});
        		        }
            		}
        		}
        }});
	}
});

$('#closeCargaRequisitos').click(function (){
	if($('#iconCloseCargaRequisitos').hasClass("fa-minus")){
		document.getElementById('cargaRequisitos').reset();
		var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
		
		for(var i = 0; i < inputs.length; i++){
			$("#div"+inputs[i]).removeClass("has-error");
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

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}
