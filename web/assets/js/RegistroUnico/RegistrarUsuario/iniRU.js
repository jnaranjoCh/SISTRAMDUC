$( window ).load(function() {
    $.ajax({
        method: "POST",
        url:  routes["registro_obtener_ajax"],
        dataType: 'json',
        success: function(data){
            var rol ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["rol"].length; i++)
                rol = rol+"<option value='"+data["rol"][i]+"'>"+data["rol"][i]+"</option>";
            $("#rolUser").html(rol);

        }
    });
});