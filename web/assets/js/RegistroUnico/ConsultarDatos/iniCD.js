var miniPersonal = true;
var miniRegistros = true;
var miniCargos = true;
var miniHijos = true;

$( window ).load(function() {
    
    $("#miniPersonal").click();
    $("#miniRegistros").click();
    $("#miniCargos").click();
    $("#miniHijos").click();
    
    $('#datetimepickerFN').datetimepicker();
    $('#datetimepickerFVC').datetimepicker();
    $('#datetimepickerFVR').datetimepicker();
    $('#datetimepickerFVAN').datetimepicker();

    
    $("#tableUsers").DataTable( {
            "ajax": routeRegistroUnico['registro_consultaobteneremails_ajax'],
            "columns": [
                { "data": "Email" },
                { "data": "Estatus" }
            ],
            "language": {
                "url": tableLenguage['datatable-spanish']
            },
            "bDestroy": true
    });
    
});