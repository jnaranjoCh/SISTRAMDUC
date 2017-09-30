var copiar = 0;
var getValueInterval;

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
        "ajax": routeRegistroUnico['registro_consultaobtenercedulas_ajax'],
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

    getValueInterval = setInterval(changeEmptyMessage, 2500);

    $("#miniPersonal").click();
    $("#miniRecaudo").click();
    
    $("#cargarDocumentos").fileinput({
        language: "es"
    });
});

function changeEmptyMessage()
{
    if(tableUsers.page.info().recordsTotal <= 0){
        $("td.dataTables_empty").html("No hay usuarios con hijos registrados.");
        clearInterval(getValueInterval);
    }
}

