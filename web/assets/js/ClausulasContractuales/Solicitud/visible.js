$("#selectedHijo").change(function (){
    if ($("#selectedHijo").val() != ""){
        $("#miniRecaudo").removeClass("hidden");
        if($('#boxBodyRecaudos').css('display') == 'none'){
            $("#miniRecaudo").click();
        } 
    }else{
        $("#miniRecaudo").addClass("hidden");
        if($('#boxBodyRecaudos').css('display') == 'block'){
            $("#miniRecaudo").addClass("hidden");
            $("#miniRecaudo").click();
        } 
    }
    $("#selectedDuracion option:selected").prop("selected", false);
    $('#valorOtro').val('');
    $('#divvalorOtro').addClass("hidden");
    $('.fileinput-remove-button').click();
});

$("#selectedDuracion").change(function (){
    if ($("#selectedDuracion").val() == "Definido por el administrador"){
        $("#divvalorOtro").removeClass("hidden");
    }else{
        $("#divvalorOtro").addClass("hidden");
    }
});