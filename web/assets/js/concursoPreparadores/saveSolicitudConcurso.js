$('#cargarSolicConcurso').click(function (){ 
	
	var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
	var falla = false;
	var text = ""
	
	toastr.clear();
	
	for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            falla = true;
            if(i>3){
            	$("#divJurado").addClass("has-error");	
            }else{
        		$("#div"+inputs[i]).addClass("has-error");
            }
            text = "Error campo mal introducido o obligatorio.";
        }else{
        	 if(i>3){
            	$("#divJurado").removeClass("has-error");	
            }else{
        		$("#div"+inputs[i]).removeClass("has-error");
            }
        }
    }
    
	if (falla){
		toastr.error(text, "Error", {"timeOut": "0","extendedTImeout": "0"});
	}else { 
	    
		/*$.ajax({
            method: "POST",
            data: {"Asignatura":$("#AsigSol").val(), "Plazas":$("#NroPlz").val(), "ExOral":$("#TemExOral").val(), "ExEsc":$("#TemExEsc").val(), 
    			"Coord":$("#JurCoord").val(), "Ppal1":$("#JurPpal1").val(), "Ppal2":$("#JurPpal2").val(), "Supl2":$("#JurSupl2").val()},
            url:  "/web/app_dev.php/preparadores/registrar_solicitud",
            dataType: 'json',
            success: function(data){
                if(data == "insertado"){*/
                    toastr.success("Solicitud registrada exitosamente!.", "Éxito!", {
					"timeOut": "0","extendedTImeout": "0"});
                    $('#closeCargaRequisitos').click();
                /* }
            }
        });*/
		
	}
});

$('#closeCargaRequisitos').click(function (){ 
	document.getElementById('cargaRequisitos').reset();
	var inputs = ["AsigSol","NroPlz","TemExOral","TemExEsc","JurCoord","JurPpal1","JurPpal2","JurSupl1","JurSupl2"];
	
	for(var i = 0; i < inputs.length; i++){
		if(i>3){
        	$("#divJurado").removeClass("has-error");	
        }else{
    		$("#div"+inputs[i]).removeClass("has-error");
        }
    }
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}
