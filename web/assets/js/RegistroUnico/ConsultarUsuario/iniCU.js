var copiar = 1;
var tableUsers;
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

    tableUsers = $("#tableUsers").DataTable( {
            "ajax": routeRegistroUnico['registro_obteneremails_ajax'],
            "columns": [
                { "data": "Email" },
                { "data": "Estatus" },
                { "data": "Registro Completo" },
                { "data": "Copiar" }
            ],
            "bDestroy": true,
            "language": {
                "url": tableLenguage['datatable-spanish']
            }
    });
   
});