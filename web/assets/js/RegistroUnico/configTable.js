tableRegistros = $('#tableRegistros').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
    },
    columns: [
        { "data": "Id del registro" },
        { "data": "Tipo de referencia" },
        { "data": "Descripcion" },
        { "data": "Nivel" },
        { "data": "Estatus" },
        { "data": "Año de publicación y/o asistencia" },
        { "data": "Empresa y/o institución" },
        { "data": "Titulo Obtenido" },
        { "data": "Ciudad / Pais"},
        { "data": "Congreso"},
        { "data": "Archivo"}
    ]
});

tableParticipantes = $('#tableParticipantes').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
    },
    columns: [
        { "data": "Id del registro" },
        { "data": "Nombre" },
        { "data": "Cedula" }
    ]
});

tableCargo = $('#tableCargo').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
    },
    columns: [
        { "data": "Cargo" },
        { "data": "Fecha de inicio en el cargo" }
    ]
});

tableRevista = $('#tableRevista').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
    },
    columns: [
        { "data": "Id del registro" },
        { "data": "Revista" },
        { "data": "Volumen" },
        { "data": "PrimerayUltimaPagina" }
    ]
});

tableRol = $('#tableRol').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
    },
    columns: [
        { "data": "Rol" }
    ]
});

tableHijos = $('#tableHijos').DataTable({
    "language": {
            "url": tableLenguage['datatable-spanish']
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
        {"data":"F Vencimiento Acta"},
        {"data":"Nacionalidad"}
    ]
});