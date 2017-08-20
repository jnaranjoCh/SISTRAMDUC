$('#TipoDeRegistroDatos').change(function(){
    var selectedOption = $(this).find('option:selected');
        
    if(selectedOption.val().localeCompare("Tutoria de pasantias") == 0)
        $("#hiddenEmpresaDatos").removeClass("hidden");
    else{
        $("#hiddenEmpresaDatos").addClass("hidden");
        $("#TituloObtenidoDatos").val("");
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
        $("#CiudadPaisDatos").val("");
        $("#CongresosDatos").val("");
    }

    
    if(selectedOption.val().localeCompare("Tutoria de pasantias") != 0)
        $("#hiddenInstitucionDatos").removeClass("hidden");
    else{
        $("#hiddenInstitucionDatos").addClass("hidden");
        $("#TituloObtenidoDatos").val("");
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
        $("#CiudadPaisDatos").val("");
        $("#CongresosDatos").val("");
    }
    
    if(selectedOption.val().localeCompare("Tutoria de tesis") == 0)
        $("#hiddenTituloObtenidoDatos").removeClass("hidden");
    else{
        $("#hiddenTituloObtenidoDatos").addClass("hidden");
        $("#TituloObtenidoDatos").val("");
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
        $("#CiudadPaisDatos").val("");
        $("#CongresosDatos").val("");
    }
    
    if(selectedOption.val().localeCompare("Asistencia a Congresos/Seminarios") == 0)
    {
        $("#hiddenCongresosDatos").removeClass("hidden");
        $("#hiddenCiudadPaisDatos").removeClass("hidden");
    }
    else{
        $("#hiddenCongresosDatos").addClass("hidden");
        $("#hiddenCiudadPaisDatos").addClass("hidden");
        $("#TituloObtenidoDatos").val("");
        $("#EmpresaDatos").val("");
        $("#InstitucionDatos").val("");
        $("#CiudadPaisDatos").val("");
        $("#CongresosDatos").val("");
    }
});

$('#checkboxHijos').click(function(){
    if( $(this).prop('checked') ) {
        $("#formHijos").removeClass("hidden");
    }else
        $("#formHijos").addClass("hidden");
});
