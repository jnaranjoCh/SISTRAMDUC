$(function () {
    table1 = $('#table-1').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Id del registro" },
            { "data": "Tipo de referencia" },
            { "data": "Descripcion" },
            { "data": "Nivel" },
            { "data": "Estatus" },
            { "data": "Año de publicación y/o asistencia" }
        ]
    });
    
    table2 = $('#table-2').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Id del registro" },
            { "data": "Nombre" },
            { "data": "Cedula" }
        ]
    });
    
    table3 = $('#table-3').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Cargos" }
        ]
    });
});