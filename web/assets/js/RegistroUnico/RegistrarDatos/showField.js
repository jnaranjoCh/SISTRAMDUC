$('#TipoDeRegistroDatos').change(function(){
    var selectedOption = $(this).find('option:selected');
        
    if(selectedOption.val().localeCompare("Tutoria de pasantias") == 0)
        $("#hiddenEmpresaDatos").removeClass("hidden");
    else{
        $("#hiddenEmpresaDatos").addClass("hidden");
        tableRevista.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }

    
    if(selectedOption.val().localeCompare("Tutoria de servicio comunitario") == 0)
        $("#hiddenInstitucionDatos").removeClass("hidden");
    else{
        $("#hiddenInstitucionDatos").addClass("hidden");
        tableRevista.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }
});

$('#checkboxHijos').click(function(){
    if( $(this).prop('checked') ) {
        $("#formHijos").removeClass("hidden");
    }else
        $("#formHijos").addClass("hidden");
});
