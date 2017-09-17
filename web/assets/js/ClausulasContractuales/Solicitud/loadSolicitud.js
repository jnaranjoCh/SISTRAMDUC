var copiar = 0;

$( window ).load(function() {
    toastr.clear();
    var status = window.location.href.split("/");
    if(status[status.length-1] == "success"){
        toastr.success("Datos registrados exitosamente!.", "Exito!", {
            "timeOut": "0",
            "extendedTImeout": "0"});
        }else if(status[status.length-1] != "initial"){
            text = "Error!";
            toastr.error(text, "error", {
                "timeOut": "0",
                "extendedTImeout": "0" });
        }

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