$( window ).load(function() {
    $.ajax({
        method: "POST",
        url: routeRegistroUnico['registro_obtener_ajax'],
        dataType: 'json',
        success: function(data){
            var rol ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["rol"].length; i++)
                rol = rol+"<option value='"+data["rol"][i]+"'>"+data["rol"][i]+"</option>";
            $("#rolUser").html(rol);

        }
    });
    
    $.ajax({
        method: "POST",
        url: routeDescargaHoraria['oficio_solicitud_data_ajax'],
        dataType: 'json',
        success: function(data){
            var facul ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["facul"].length; i++)
                facul = facul+"<option value='"+data["facul"][i]+"'>"+data["facul"][i]+"</option>";
            $("#facultad").html(facul);

        }
    });
});