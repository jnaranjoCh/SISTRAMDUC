$('#tableUsers tbody').on( 'click', 'td', function () {
    var row = tableUsers.cell( this ).index().row;
    var column = tableUsers.cell( this ).index().column;
    var cell = new Object();
    
    cell.row = row; cell.column = column; cell.columnVisible = "0";
    var id = tableUsers.cell(cell).data().split('id="')[1].split('"')[0].split('_')[1];
    var email = $("#email_"+id).text();
    if(copiar == 0){
        $('#mail').val(email);
        $('#myModal').modal("hide");
    }
    else{
        $('#gemail').val(email);
        $('#myModal2').modal("hide");
    }

    if(copiar == 0){
        $('#mail').val(email);
        $('#registrosBusqueda').modal("hide");
    }
});