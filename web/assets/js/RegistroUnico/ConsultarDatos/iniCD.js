var miniPersonal = true;
var miniRegistros = true;
var miniCargos = true;
var miniHijos = true;
var copiar = 0;
var tableRelationshipList = [];
var registerOtherUserMadre = false;
var registerOtherUserPadre = false;
var registerOtherUsers = [];
var otherUsersCount = 0;


$( window ).load(function() {
    
    $("#miniPersonal").click();
    $("#miniRegistros").click();
    $("#miniCargos").click();
    $("#miniHijos").click();
    
    $('#datetimepickerFN').datetimepicker({
      maxDate: moment(), 
      format:'DD/MM/YYYY HH:mm'
    });
    $('#datetimepickerFVC').datetimepicker({
      maxDate: moment(), 
      format:'DD/MM/YYYY HH:mm'
    });
    $('#datetimepickerFVR').datetimepicker({
      maxDate: moment(), 
      format:'DD/MM/YYYY HH:mm'
    });
    $('#datetimepickerFVAN').datetimepicker({
      maxDate: moment(), 
      format:'DD/MM/YYYY HH:mm'
    });

    var status = window.location.href.split("/");
    if(status[status.length-1] == "success")
        toastr.success("Datos actualizados exitosamente!.", "Éxito!", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    else if(status[status.length-1] == "error")
        toastr.error("Hubo problemas al subir los archivos!", "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
            
    tableUsers = $("#tableUsers").DataTable( {
            "ajax": routeRegistroUnico['registro_consultaobteneremails_ajax'],
            "columns": [
                { "data": "Email" },
                { "data": "Estatus" },
                { "data": "Copiar" }
            ],
            "language": {
                "url": tableLenguage['datatable-spanish']
            },
            "bDestroy": true
    });
    
    $.ajax({
        method: "POST",
        url:  routeRegistroUnico['registro_obtener_ajax'],
        dataType: 'json',
        success: function(data){
            var tipoDedicaion ="<option value='' selected='selected'>Seleccione una opción</option>";
            for(var i = 0; i < data["tipo_dedicacion"].length; i++)
                tipoDedicaion = tipoDedicaion+"<option value='"+data["tipo_dedicacion"][i]+"'>"+data["tipo_dedicacion"][i]+"</option>";
            $("#tipoDedicacionDatos").html(tipoDedicaion);
        }
    });
    
});