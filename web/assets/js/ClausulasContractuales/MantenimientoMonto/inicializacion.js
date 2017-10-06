var countAmounts = 0;
var amounts = [];
var tableMontos;
var state = 0;

$( window ).load(function() {
    
    tableMontos = $("#tableMontos").DataTable( {
                            "ajax": routeClausulasContractuales['clausulas_contractuales_mantenimiento_de_montos_enviar_data'],
                            "columns": [
                                { "data": "Id", "className": "hidden" },
                                { "data": "Descripcion" },
                                { "data": "Monto" }
                            ],
                            "language": {
                                "url": tableLenguage['datatable-spanish']
                            },
                            "bDestroy": true
                    });
    $("#agregarMonto").addClass("btn-success");
    $("#agregarMonto").removeClass("btn-primary");
    $("#agregarMonto").html("Agregar&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa fa-plus-square'></i>");
});