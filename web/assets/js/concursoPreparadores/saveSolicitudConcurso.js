$('#cargarSolicConcurso').click(function (){ 
	
	var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
	var falla = false;
	var text = ""
	
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
		//$("#cargando").modal("show");
		$.ajax({
            method: "POST",
            data: {"AsigSol":$("#AsigSol").val(), "NroPlz":$("#NroPlz").val(), "ExOral":$("#TemExOral").val(), "ExEsc":$("#TemExEsc").val(), 
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
	document.getElementById('cargaRequisitos').reset();
	var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
	
	for(var i = 0; i < inputs.length; i++){
		$("#div"+inputs[i]).removeClass("has-error");
    }
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}
