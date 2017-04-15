var idRegistro = 0;
$( window ).load(function() {
    
    $("#miniPersonal").click();
    $("#miniRegistros").click();
    $("#miniCargos").click();
    
    $("#tableUsers").DataTable( {
          "ajax": "/web/app_dev.php/registro/enviar-emails",
          "columns": [
		        { "data": "Email" },
		        { "data": "Estatus" }
	       ],
	       "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
    });
    
    $.ajax({
        method: "POST",
        url:  "/web/app_dev.php/registro/obtener-datos",
        dataType: 'json',
        success: function(data)
        {
            var estatus ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["estatus"].length; i++)
                estatus = estatus+"<option value='"+data["estatus"][i]+"'>"+data["estatus"][i]+"</option>";
            var nivel ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["nivel"].length; i++)
                nivel = nivel+"<option value='"+data["nivel"][i]+"'>"+data["nivel"][i]+"</option>";
            var tipo_registro ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["tipo_registro"].length; i++)
                tipo_registro = tipo_registro+"<option value='"+data["tipo_registro"][i]+"'>"+data["tipo_registro"][i]+"</option>";
            var cargo ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["cargo"].length; i++)
                cargo = cargo+"<option value='"+data["cargo"][i]+"'>"+data["cargo"][i]+"</option>";
            $("#EstatusDatos").html(estatus);
            $("#NivelDeEstudioDatos").html(nivel);
            $("#TipoDeRegistroDatos").html(tipo_registro);
            $("#cargosDatos").html(cargo);
        }
    });
    
     $.ajax({
        method: "POST",
        url:  "/web/app_dev.php/registro/enviar-lastid",
        dataType: 'json',
        success: function(data)
        {
            if(data[0].lastId != null)
                idRegistro = data[0].lastId;
        }
    });
    $('#IdParticipanteRegistro').html("<option value='0'>No existen registro</option>");
    $('#idRevistaRegistro').html("<option value='0'>No existen registro</option>");
    $('#datetimepicker1').datetimepicker();
});

