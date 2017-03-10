var countRol =0;
var countCargo =0;
$('#ar').click(function(){
    if($("#empR").val()=="" && $("#insR").val()=="")
    {
        table1.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#tipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#desR").val(),
            "Nivel": $("#nivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#estatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#anoR").val(),
            "Empresa y/o institución": ""
        } ).draw();
    }else if($("#empR").val()!="")
    {
        table1.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#tipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#desR").val(),
            "Nivel": $("#nivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#estatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#anoR").val(),
            "Empresa y/o institución": $("#empR").val()
        } ).draw();
    }else if($("#insR").val()!="")
    {
        table1.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#tipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#desR").val(),
            "Nivel": $("#nivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#estatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#anoR").val(),
            "Empresa y/o institución": $("#insR").val()
        } ).draw();
    }
    
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


$('#agregarCargoDatos').click(function(){
    var band = false;
    if(($('#tableCargo td').length-1) > 0)
    {
         tableCargo.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#cargosDatos").find('option:selected').val())
                        band = true;
               });
   }
   
   if(($('#tableCargo td').length-1)==0 && $("#cargosDatos").find('option:selected').val() != "")
   {
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val()
        }).draw();
   }else if(!band && $("#cargosDatos").find('option:selected').val() != "")
   {
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val()
        }).draw();
   }
   countCargo++;
});

$('#rolUser').change(function(){
    var band = false;
    if(($('#tableRol td').length-1) > 0)
    {
         tableRol.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#rolUser").find('option:selected').val())
                        band = true;
               });
   }
   
   if(($('#tableRol td').length-1)==0 && $("#rolUser").find('option:selected').val() != "")
   {
       tableRol.row.add( {
                "Rol": $("#rolUser").find('option:selected').val()
        }).draw();
   }else if(!band && $("#rolUser").find('option:selected').val() != "")
   {
       tableRol.row.add( {
                "Rol": $("#rolUser").find('option:selected').val()
        }).draw();
   }
   countRol++;
});

$('#tableRol').on( 'click', 'tbody tr', function () {
    tableRol.row( this ).remove().draw();
    if(countRol > 0)
        countRol--;
} );

$('#tableCargo').on( 'click', 'tbody tr', function () {
    tableCargo.row( this ).remove().draw();
    if(countCargo > 0)
        countCargo--;
} );

$('#arv').click(function(){
   table4.row.add( {
            "Id del registro": $("#idR").val(),
            "Revista": $("#nomR").val()
    } ).draw();
    
});

$('#table-4').on( 'click', 'tbody tr', function () {
    table4.row( this ).remove().draw();
} );