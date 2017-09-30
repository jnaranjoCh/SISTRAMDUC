$("#guardarBeca").click(function (){
    var inputs = ["selectedDuracion", "valorOtro", "cargarDocumentos"];
	var falla = false;
	var text = "";
	
	toastr.clear();
	
	for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            falla = true;
    		$("#div"+inputs[i]).addClass("has-error");
            text = "Dato inválido u obligatorio.";
        }else{
    		$("#div"+inputs[i]).removeClass("has-error");
        }
    }

    if (falla){
		toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
        $("#myModal").modal("show");
        document.getElementById("becaForm").submit();
    }
    
});

$("#guardarPrima").click(function (){
    var inputs = ["selectedDuracion", "valorOtro", "cargarDocumentos"];
	var falla = false;
	var text = "";
	
	toastr.clear();
	
	for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            falla = true;
    		$("#div"+inputs[i]).addClass("has-error");
            text = "Dato inválido u obligatorio.";
        }else{
    		$("#div"+inputs[i]).removeClass("has-error");
        }
    }

    if (falla){
		toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
        $("#myModal").modal("show");
        document.getElementById("primaForm").submit();
    }
});

$("#guardarDiscapacidad").click(function (){
    var inputs = ["cargarDocumentos"];
	var falla = false;
	var text = "";
	
	toastr.clear();
	
	for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            falla = true;
    		$("#div"+inputs[i]).addClass("has-error");
            text = "Dato inválido u obligatorio.";
        }else{
    		$("#div"+inputs[i]).removeClass("has-error");
        }
    }

    if (falla){
		toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
        $("#myModal").modal("show");
        document.getElementById("discapacidadForm").submit();
    }

});
