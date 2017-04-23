$(function () {
    tableRegistros = $('#tableRegistros').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Id del registro" },
            { "data": "Tipo de referencia" },
            { "data": "Descripcion" },
            { "data": "Nivel" },
            { "data": "Estatus" },
            { "data": "Año de publicación y/o asistencia" },
            { "data": "Empresa y/o institución" }
        ]
    });
    
    tableParticipantes = $('#tableParticipantes').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Id del registro" },
            { "data": "Nombre" },
            { "data": "Cedula" }
        ]
    });
    
    tableCargo = $('#tableCargo').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Cargo" }
        ]
    });
    
    tableRevista = $('#tableRevista').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Id del registro" },
            { "data": "Revista" }
        ]
    });
    
    tableRol = $('#tableRol').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            { "data": "Rol" }
        ]
    });
    
    tableHijos = $('#tableHijos').DataTable({
	    "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        columns: [
            {"data":"CI Madre"},
            {"data":"CI Padre"},
            {"data":"CI Hijo"},
            {"data":"1er Nombre"},
            {"data":"2do Nombre"},
            {"data":"1er Apellido"},
            {"data":"2do Apellido"},
            {"data":"F Nacimiento"},
            {"data":"Nacionalidad"}
        ]
    });
});