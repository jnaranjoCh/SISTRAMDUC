$('#submitData').click(function(){
    toastr.clear();
    $.ajax({
        method: "POST",
        data: {"Registros":getData()},
        url:    routeRegistroUnico['registro_validaractualizarregistros_ajax'],
        dataType: 'json',
        beforeSend: function() {
            $("#myModal2").modal("show");
        },
        success: function(data){
            if(!data.localeCompare("Actualizado"))
                toastr.success("Datos actualizados exitosamente!.", "Exito!", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                               });
            else
                toastr.error("Error al actualizar los registros", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
            $("#myModal2").modal("hide");
        }
    });
});

function getData()
{
    var numFilas = obtenerFilas(tableRegistros);
    var registros = [];
    
    for(var i = 0; i < numFilas; i++)
    {
        var validar = new Object();
        var cellsRegistros = new Object();
        cellsRegistros.row = i;
        cellsRegistros.column = 10;
        cellsRegistros.columnVisible = "0";
        idCheck = findId(tableRegistros, cellsRegistros);
        if(findProp(tableRegistros, cellsRegistros))
            validar.validado = true;
        else
            validar.validado = false;
        cellsRegistros.column = 0;
        validar.idRegistro = tableRegistros.cell(cellsRegistros).data();
        registros[i] = validar;
    }
    
    return registros;
}

function obtenerFilas(table)
{
    return table.page.info().recordsTotal;
}

function findId(table, cellTable)
{
    var valor;
    if(table.cell(cellTable).nodes().to$().find('input').val() != null)
        valor = table.cell(cellTable).nodes().to$().find('input')[0].id;
    else
        valor = table.cell(cellTable).nodes().to$().find('select')[0].id;
    return valor;
}

function findProp(table, cellTable)
{
    var valor;
    if(table.cell(cellTable).nodes().to$().find('input').val() != null)
        valor = table.cell(cellTable).nodes().to$().find('input')[0].checked;
    else
        valor = table.cell(cellTable).nodes().to$().find('select')[0].checked;
    return valor;
}