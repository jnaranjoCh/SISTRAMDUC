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

    
    if(selectedOption.val().localeCompare("Tutoria de pasantias") != 0)
        $("#hiddenInstitucionDatos").removeClass("hidden");
    else{
        $("#hiddenInstitucionDatos").addClass("hidden");
        tableRevista.clear().draw();
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
    }
    
    if(selectedOption.val().localeCompare("Tutoria de tesis") == 0)
        $("#hiddenTituloObtenidoDatos").removeClass("hidden");
    else{
        $("#hiddenTituloObtenidoDatos").addClass("hidden");
    }
});

$('#checkboxHijos').click(function(){
    if( $(this).prop('checked') ) {
        $("#formHijos").removeClass("hidden");
    }else
        $("#formHijos").addClass("hidden");
});
