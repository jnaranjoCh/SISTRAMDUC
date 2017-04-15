$('#TipoDeRegistroDatos').change(function(){
    var selectedOption = $(this).find('option:selected');

    /*if(selectedOption.val().localeCompare("Articulo publicado") == 0)
        $("#revista").removeClass("hidden");
    else
    {
        $("#revista").addClass("hidden");
        table4.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }*/
        
    if(selectedOption.val().localeCompare("Tutoria de pasantias") == 0)
        $("#hiddenEmpresaDatos").removeClass("hidden");
    else
    {
        $("#hiddenEmpresaDatos").addClass("hidden");
        tableRevista.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }

    
    if(selectedOption.val().localeCompare("Tutoria de servicio comunitario") == 0)
        $("#hiddenInstitucionDatos").removeClass("hidden");
    else
    {
        $("#hiddenInstitucionDatos").addClass("hidden");
        tableRevista.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }
});