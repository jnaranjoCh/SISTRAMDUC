$(function () {
    table1 = $('#table-1').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { title: "IdRegistro" },
            { title: "TipoReferencia" },
            { title: "Descripcion" },
            { title: "Nivel" },
            { title: "Estatus" },
            { title: "Año" }
        ]
    });
    
    table2 = $('#table-2').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { title: "IdRegistro" },
            { title: "Nombre" },
            { title: "Cedula" }
        ]
    });
});