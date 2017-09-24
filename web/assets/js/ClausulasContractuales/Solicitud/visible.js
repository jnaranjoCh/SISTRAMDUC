$("#selectedHijo").change(function (){
    if ($("#selectedHijo").val() != ""){
        $("#recaudos").removeClass("hidden");
    }else{
        $("#recaudos").addClass("hidden");
    }
});

$("#selectedDuracion").change(function (){
    if ($("#selectedDuracion").val() == "Definido por el administrador"){
        $("#duracionInput").removeClass("hidden");
    }else{
        $("#duracionInput").addClass("hidden");
    }
});