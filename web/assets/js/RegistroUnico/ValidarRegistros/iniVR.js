var tableUsers;
var copiar = 0;

$( window ).load(function() {
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
