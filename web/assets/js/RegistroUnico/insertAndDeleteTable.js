var countRol =0;
var countCargo =0;
var countRegistro =0;

$('#agregarRegistro').click(function(){
    var band = false;
    var participantesId="<option value='0'>Seleccione una opción</option>";

    if(($('#tableRegistros td').length) > 0)
    {
         tableRegistros.column(2)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#DescripcionDatos").val())
                        band = true;
               });
   }
   
   if(($('#tableRegistros td').length)==0 && $("#DescripcionDatos").val()!="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="" && $("#NivelDeEstudioDatos").find('option:selected').val()!="" && $("#EstatusDatos").find('option:selected').val()!="" && $("#AnoPublicacionDatos").val()!="")
   {
        if($("#EmpresaDatos").val()=="" && $("#InstitucionDatos").val()=="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de servicio comunitario" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de pasantias")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": ""
            } ).draw();
            countRegistro++;
            idRegistro++;
        }else if($("#EmpresaDatos").val()!="")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": $("#EmpresaDatos").val()
            } ).draw();
            countRegistro++;
            idRegistro++;
        }else if($("#InstitucionDatos").val()!="")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": $("#InstitucionDatos").val()
            } ).draw();
            countRegistro++;
            idRegistro++;
        }
   }else if(!band && $("#DescripcionDatos").val()!="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="" && $("#NivelDeEstudioDatos").find('option:selected').val()!="" && $("#EstatusDatos").find('option:selected').val()!="" && $("#AnoPublicacionDatos").val()!="")
   {
        if($("#EmpresaDatos").val()=="" && $("#InstitucionDatos").val()=="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de servicio comunitario" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de pasantias")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": ""
            } ).draw();
            countRegistro++;
            idRegistro++;
        }else if($("#EmpresaDatos").val()!="")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": $("#EmpresaDatos").val()
            } ).draw();
            countRegistro++;
            idRegistro++;
        }else if($("#InstitucionDatos").val()!="")
        {
            tableRegistros.row.add( {
                "Id del registro": idRegistro,
                "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                "Descripcion": $("#DescripcionDatos").val(),
                "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                "Estatus": $("#EstatusDatos").find('option:selected').val(),
                "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                "Empresa y/o institución": $("#InstitucionDatos").val()
            } ).draw();
            countRegistro++;
            idRegistro++;
        }
   }
   
   tableRegistros.column(1)
                 .data()
                 .each( function ( value1,index1 ) {
                        if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario")
                        {
                           tableRegistros.column(0)
                                         .data()
                                         .each( function ( value2,index2 ) {
                                                if(index1 == index2)
                                                    participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                          });                         
                        }
                  });
    $('#IdParticipanteRegistro').html(participantesId);
                                                
  
    
});

$('#tableRegistros').on( 'click', 'tbody tr', function () {
    var participantesId="<option value='0'>Seleccione una opción</option>";

    tableRegistros.row( this ).remove().draw();
    if(countRegistro > 0)
    {
       tableRegistros.column(1)
                     .data()
                     .each( function ( value1,index1 ) {
                            if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario")
                            {
                               tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1 == index2)
                                                        participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                              });                         
                            }
                      });
        $('#IdParticipanteRegistro').html(participantesId);
   
        countRegistro--;
        idRegistro--;
    }
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