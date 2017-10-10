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
    
    switch(selectedOption.val())
    {
        case "Articulo publicado":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad / Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Casa editorial");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad / Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Empresa");
            $("#InstitucionDatos").attr("placeholder", "Casa editorial");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Proyectos":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad / Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Financiamiento");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad / Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Empresa");
            $("#InstitucionDatos").attr("placeholder", "Financiamiento");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");            
            break;
        case "Tutoria de tesis":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Lugar");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Entidad Organizadora:");
            $("#InstitucionLabel").html("Entidad Organizadora:");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Lugar");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizadora");
            $("#InstitucionDatos").attr("placeholder", "Entidad Organizadora");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Tutoria de pasantias":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Lugar");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Empresa");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Lugar");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Empresa");
            $("#InstitucionDatos").attr("placeholder", "Empresa");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Tutoria de servicio comunitario":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad / Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Institución");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
                        
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Lugar");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Institución");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Asistencia a Congresos/Seminarios":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad y Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Entidad Organizacional");
            $("#InstitucionLabel").html("Entidad Organizacional");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizacional");
            $("#InstitucionDatos").attr("placeholder", "Entidad Organizacional");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Sociedad Científica y Profesionales":
            $("#DescripcionLabel").html("Titulo");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad / Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Titulo");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizacional");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Becas":
            $("#DescripcionLabel").html("Beca");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad y Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Beca");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizacional");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Premios":
            $("#DescripcionLabel").html("Premio");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad y Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Premio");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizacional");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Distinciones":
            $("#DescripcionLabel").html("Distinciones");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad y Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");

            $("#DescripcionDatos").attr("placeholder", "Distinciones");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Entidad Organizacional");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
        case "Estudio":
            $("#DescripcionLabel").html("Descripción");
            $("#AnoPublicacionLabel").html("Año");
            $("#CiudadPaisLabel").html("Ciudad y Pais");
            $("#CongresosLabel").html("Congresos");
            $("#EmpresaLabel").html("Empresa");
            $("#InstitucionLabel").html("Institución");
            $("#TituloObtenidoLabel").html("Titulo Obtenido");
            
            $("#DescripcionDatos").attr("placeholder", "Descripción");
            $("#AnoPublicacionDatos").attr("placeholder", "Año");
            $("#CiudadPaisDatos").attr("placeholder", "Ciudad y Pais");
            $("#CongresosDatos").attr("placeholder", "Congresos");
            $("#EmpresaDatos").attr("placeholder", "Empresa");
            $("#InstitucionDatos").attr("placeholder", "Institución");
            $("#TituloObtenidoDatos").attr("placeholder", "Titulo Obtenido");
            break;
    }
});

$('#checkboxHijos').click(function(){
    if( $(this).prop('checked') ) {
        $("#formHijos").removeClass("hidden");
    }else
        $("#formHijos").addClass("hidden");
});

$('#checkboxMadre').click(function(){
    if( $(this).prop('checked') ) {
        $("#nameParent2").html("del padre");
        $("#nameParent1").html("de la madre");
        $("#nameParent3").html("madre");
        $("#myModalHijosMessage").modal("show");
    }
});

$('#checkboxPadre').click(function(){
    if( $(this).prop('checked') ) {
        $("#nameParent1").html("del padre");
        $("#nameParent2").html("de la madre");
        $("#nameParent3").html("padre");
        $("#myModalHijosMessage").modal("show");
    }
});


$("#continueParent").click(function(){
    if( $('#checkboxPadre').prop('checked') ) {
        $("#checkboxMadre").prop('disabled', true);
        $("#CedulaPadreHijoDatos").prop('disabled', false);
        $("#checkboxPadre").prop('disabled', true);
        registerOtherUserPadre = true;
        registerOtherUserMadre = false;
        if($('#checkboxParent').prop('checked') && otherUsersCount>0)
            registerOtherUsers[otherUsersCount-1].register = true;
        else if(!$('#checkboxParent').prop('checked') && otherUsersCount>0)
            registerOtherUsers[otherUsersCount-1].register = false;
    }
    
    if($('#checkboxMadre').prop('checked'))
    {
        $("#checkboxMadre").prop('disabled', true);
        $("#CedulaMadreHijoDatos").prop('disabled', false);
        $("#checkboxPadre").prop('disabled', true);
        registerOtherUserMadre = true;
        registerOtherUserPadre = false;
        if($('#checkboxParent').prop('checked') && otherUsersCount>0)
            registerOtherUsers[otherUsersCount-1].register = true;
        else if(!$('#checkboxParent').prop('checked') && otherUsersCount>0)
            registerOtherUsers[otherUsersCount-1].register = false;
    }
    $("#myModalHijosMessage").modal("hide");
    
});

$("#closeParent").click(function(){
    if( $('#checkboxPadre').prop('checked') ) {
        $('#checkboxPadre').prop('checked',false);
    }
    
    if($('#checkboxMadre').prop('checked')){
        $('#checkboxMadre').prop('checked',false);
    }
});


$("#AnoPublicacionDatos").keypress(function(){
    $("#AnoPublicacionDatos").val('');
});

$("#AnoPublicacionDatos").keyup(function(){
    $("#AnoPublicacionDatos").val('');
});

$("#AnoPublicacionDatos").keydown(function(){
    $("#AnoPublicacionDatos").val('');
});

$("#AnoPublicacionDatos").change(function(){
    var date = new Date();
    if($("#AnoPublicacionDatos").val() > date.getUTCFullYear())
    {
        $("#AnoPublicacionDatos").val('');
        toastr.error("El año supera la actual.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
    }
});