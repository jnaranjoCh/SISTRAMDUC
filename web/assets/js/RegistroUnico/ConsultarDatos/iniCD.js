var miniPersonal = true;
var miniRegistros = true;
var miniCargos = true;
var miniHijos = true;
var copiar = 0;

$( window ).load(function() {
    
    $("#miniPersonal").click();
    $("#miniRegistros").click();
    $("#miniCargos").click();
    $("#miniHijos").click();
    
    $('#datetimepickerFN').datetimepicker();
    $('#datetimepickerFVC').datetimepicker();
    $('#datetimepickerFVR').datetimepicker();
    $('#datetimepickerFVAN').datetimepicker();

    var status = window.location.href.split("/");
    if(status[status.length-1] == "success")
        toastr.success("Datos actualizados exitosamente!.", "Exito!", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
    else if(status[status.length-1] == "error")
        toastr.error("Error hubo problemas al subir los archivos!", "Error", {
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
    
});