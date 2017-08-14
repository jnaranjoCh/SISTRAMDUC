$('#tableRegistros').on( 'click', 'td', function () {
    var cell = new Object();
    cell.row = tableRegistros.cell( this ).index().row; cell.column = "7"; cell.columnVisible = "0";
    if(tableRegistros.cell( this ).index().column == 7){
        var id = tableRegistros.cell(cell).data().split('<span id="')[1].split('"')[0];
        var idCheck = tableRegistros.cell(cell).data().split('<input type="checkbox" id="')[1].split('"')[0];
        if($('#'+id).attr('class').localeCompare('label label-warning') == 0 && $("#"+idCheck).prop('checked'))
        {
            $('#'+id).removeClass('label label-warning');
            $('#'+id).addClass('label label-success');
            $('#'+id).text("Validado");
        }
        else if($('#'+id).attr('class').localeCompare('label label-success') == 0 &&  !$("#"+idCheck).prop('checked'))
        {
            $('#'+id).removeClass('label label-success');
            $('#'+id).addClass('label label-warning');
            $('#'+id).text("No validado");
        }
    }   
});

