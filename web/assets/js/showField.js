$('#tipoDeRegistroDatos').change(function(){
    var selectedOption = $(this).find('option:selected');

    if(selectedOption.val().localeCompare("Articulo publicado") == 0)
        $("#revista").removeClass("hidden");
    else
    {
        $("#revista").addClass("hidden");
        table4.clear().draw();
        $("#empR").val("");
        $("#insR").val("");
    }
        
    if(selectedOption.val().localeCompare("Tutoria de pasantias") == 0)
        $("#em").removeClass("hidden");
    else
    {
        $("#em").addClass("hidden");
        table4.clear().draw();
        $("#empR").val("");
        $("#insR").val("");
    }

    
    if(selectedOption.val().localeCompare("Tutoria de servicio comunitario") == 0)
        $("#in").removeClass("hidden");
    else
    {
        $("#in").addClass("hidden");
        table4.clear().draw();
        $("#empR").val("");
        $("#insR").val("");
    }
});