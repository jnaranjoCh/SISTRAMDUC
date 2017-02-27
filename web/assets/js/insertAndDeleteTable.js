$('#ar').click(function(){
    table1.row.add( {
        "Id del registro": "1",
        "Tipo de referencia": $("#tr").find('option:selected').val(),
        "Descripcion": $("#desR").val(),
        "Nivel": $("#neR").find('option:selected').val(),
        "Estatus": $("#estR").find('option:selected').val(),
        "Año de publicación y/o asistencia": $("#anoR").val()
    } ).draw();
    
});

$('#table-1').on( 'click', 'tbody tr', function () {
    table1.row( this ).remove().draw();
} );

$('#ap').click(function(){
   table2.row.add( {
            "Id del registro": $("#idP").val(),
            "Nombre": $("#nomP").val(),
            "Cedula": $("#cedP").val()
    } ).draw();
}); 

$('#table-2').on( 'click', 'tbody tr', function () {
    table2.row( this ).remove().draw();
} );



$('#ac').click(function(){
   table3.row.add( {
            "Cargos": $("#crg").find('option:selected').val()
    } ).draw();
}); 

$('#table-3').on( 'click', 'tbody tr', function () {
    table3.row( this ).remove().draw();
} );
