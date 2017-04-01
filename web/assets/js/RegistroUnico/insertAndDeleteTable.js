var countRol =0;
var countCargo =0;
$('#agregarRegistro').click(function(){
    if($("#EmpresaDatos").val()=="" && $("#InstitucionDatos").val()=="")
    {
        tableRegistros.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#DescripcionDatos").val(),
            "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#EstatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
            "Empresa y/o institución": ""
        } ).draw();
    }else if($("#EmpresaDatos").val()!="")
    {
        tableRegistros.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#DescripcionDatos").val(),
            "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#EstatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
            "Empresa y/o institución": $("#EmpresaDatos").val()
        } ).draw();
    }else if($("#InstitucionDatos").val()!="")
    {
        tableRegistros.row.add( {
            "Id del registro": "1",
            "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
            "Descripcion": $("#DescripcionDatos").val(),
            "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
            "Estatus": $("#EstatusDatos").find('option:selected').val(),
            "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
            "Empresa y/o institución": $("#InstitucionDatos").val()
        } ).draw();
    }
    
});

$('#tableRegistros').on( 'click', 'tbody tr', function () {
    tableRegistros.row( this ).remove().draw();
} );

$('#agregarParticipantes').click(function(){
   tableParticipantes.row.add( {
            "Id del registro": $("#IdParticipanteRegistro").val(),
            "Nombre": $("#NombreParticipanteRegistro").val(),
            "Cedula": $("#CedulaParticipanteRegistro").val()
    } ).draw();
}); 

$('#tableParticipantes').on( 'click', 'tbody tr', function () {
    tableParticipantes.row( this ).remove().draw();
} );


$('#agregarCargoDatos').click(function(){
    var band = false;
    if(($('#tableCargo td').length) > 0)
    {
         tableCargo.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#cargosDatos").find('option:selected').val())
                        band = true;
               });
   }
   
   if(($('#tableCargo td').length)==0 && $("#cargosDatos").find('option:selected').val() != "")
   {
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val()
        }).draw();
        countCargo++;
   }else if(!band && $("#cargosDatos").find('option:selected').val() != "")
   {
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val()
        }).draw();
        countCargo++;
   }
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
        countRol++;
   }else if(!band && $("#rolUser").find('option:selected').val() != "")
   {
       tableRol.row.add( {
                "Rol": $("#rolUser").find('option:selected').val()
        }).draw();
        countRol++;
   }
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