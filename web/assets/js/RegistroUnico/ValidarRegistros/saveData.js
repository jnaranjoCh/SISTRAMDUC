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
        cellsRegistros.column = 8;
        cellsRegistros.columnVisible = "0";
        idCheck = tableRegistros.cell(cellsRegistros).data().split('<input type="checkbox" id="')[1].split('"')[0];
        cellsRegistros.column = 0;
        
        validar.idRegistro = tableRegistros.cell(cellsRegistros).data();
        if($("#"+idCheck).prop('checked'))
            validar.validado = true;
        else
            validar.validado = false;
        registros[i] = validar;
    }
    
    return registros;
}

function obtenerFilas(table)
{
    return table.page.info().recordsTotal;
}