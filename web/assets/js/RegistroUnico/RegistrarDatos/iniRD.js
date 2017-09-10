var idRegistro = 0;
var miniPersonal = true;
var miniRegistros = true;
var miniCargos = true;
var miniHijos = true;
var copiar = 0;

$( window ).load(function() {
    
    
    $("#DescripcionLabel").html("Descripción");
    $("#AnoPublicacionLabel").html("Año");
    $("#CiudadPaisLabel").html("Ciudad / Pais");
    $("#CongresosLabel").html("Congresos");
    $("#EmpresaLabel").html("Empresa");
    $("#InstitucionLabel").html("Institución / Casa editorial / Financiamiento");
    $("#TituloObtenidoLabel").html("Titulo Obtenido");
    var status = window.location.href.split("/");
    if(status[status.length-1] == "success")
        toastr.success("Datos registrados exitosamente!.", "Exito!", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    else if(status[status.length-1] == "error")
        toastr.error("Error hubo problemas al subir los archivos!", "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    
    $("#miniPersonal").click();
    $("#miniRegistros").click();
    $("#miniCargos").click();
    $("#miniHijos").click();

    tableUsers = $("#tableUsers").DataTable( {
            "ajax": routeRegistroUnico['registro_obteneremails_ajax'],
            "columns": [
                { "data": "Email" },
                { "data": "Estatus" },
                { "data": "Registro Completo" },
                { "data": "Copiar" }
            ],
            "language": {
                "url": tableLenguage['datatable-spanish']
            }
    });
    
    $.ajax({
        method: "POST",
        url:  routeRegistroUnico['registro_obtener_ajax'],
        dataType: 'json',
        success: function(data){
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
        url:  routeRegistroUnico['registro_obtenerlastid_ajax'],
        dataType: 'json',
        success: function(data){
            if(data[0].lastId != null)
                idRegistro = data[0].lastId+1;
            else
                idRegistro = 1;
        }
    });
    $('#IdParticipanteRegistro').html("<option value='-1'>No existen registros</option>");
    $('#idRevistaRegistro').html("<option value='-1'>No existen registros</option>");
    $('#datetimepicker1').datetimepicker();
    $('#datetimepicker2').datetimepicker();
    $('#datetimepicker3').datetimepicker();
    $('#datetimepicker4').datetimepicker();
    $('#datetimepicker5').datetimepicker();
    $('#datetimepicker6').datetimepicker();
    $('#datetimepicker7').datetimepicker();
    $("#CedulaRifActaCargaDatos").fileinput({
        language: "es"
    });
    $("#ActaNacCargaHijoDatos").fileinput({
        language: "es"
    });
});