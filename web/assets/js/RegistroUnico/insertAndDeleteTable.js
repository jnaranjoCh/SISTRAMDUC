var countRol = 0;
var countCargo = 0;
var countRegistro = 0;
$('#agregarRegistro').click(function(){
    toastr.clear();
    var band = false,bandConcatRevist = true,bandConcatPart = true;
    var participantesId="<option value='-1'>No existen registro</option>";
    var revistasId="<option value='-1'>No existen registro</option>";

    if(($('#tableRegistros td').length) > 0)
    {
         tableRegistros.column(2)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#DescripcionDatos").val())
                    { 
                        band = true;
                        toastr.error("Error el registro ya se encuentra en la tabla.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                         });
                    }
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
                           if(bandConcatPart)
                           {
                                participantesId="<option value='-1'>Seleccione una opción</option>";
                                bandConcatPart = false;
                           }
                           
                           tableRegistros.column(0)
                                         .data()
                                         .each( function ( value2,index2 ) {
                                                if(index1 == index2)
                                                    participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                          });     
                        }else if(value1=="Articulo publicado")
                        {
                            if(bandConcatRevist)
                            {
                                revistasId="<option value='-1'>Seleccione una opción</option>";
                                bandConcatRevist = false;
                            }
                            tableRegistros.column(0)
                                         .data()
                                         .each( function ( value2,index2 ) {
                                                if(index1 == index2)
                                                    revistasId = revistasId+"<option value='"+value2+"'>"+value2+"</option>";
                                          });
                        }
                  });
    $('#IdParticipanteRegistro').html(participantesId);
    $('#idRevistaRegistro').html(revistasId);
                                                
  
    
});

$('#tableRegistros').on( 'click', 'tbody tr', function () {
    var bandConcatRevist = true,bandConcatPart = true;
    var participantesId="<option value='-1'>No existen registro</option>";
    var revistasId="<option value='-1'>No existen registro</option>";

    tableRegistros.row( this ).remove().draw();
    if(countRegistro > 0)
    {
       tableRegistros.column(1)
                     .data()
                     .each( function ( value1,index1 ) {
                            if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario")
                            {
                               if(bandConcatPart)
                               {
                                    participantesId="<option value='-1'>Seleccione una opción</option>";
                                    bandConcatPart = false;
                               }
                               tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1 == index2)
                                                        participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                              });                         
                            }else if(value1=="Articulo publicado")
                            {
                                if(bandConcatRevist)
                                {
                                    revistasId="<option value='-1'>Seleccione una opción</option>";
                                    bandConcatRevist = false;
                                }
                                tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1 == index2)
                                                        revistasId = revistasId+"<option value='"+value2+"'>"+value2+"</option>";
                                              });
                            }
                      });
        $('#IdParticipanteRegistro').html(participantesId);
        $('#idRevistaRegistro').html(revistasId);
        countRegistro--;
    }
} );

$('#agregarParticipantes').click(function(){
    toastr.clear();
    var band = false;
    if(($('#tableParticipantes td').length) > 0)
    {
         tableParticipantes.column(0)
              .data()
              .each( function ( value1,index1 ) {
                  tableParticipantes.column(2)
                      .data()
                      .each( function ( value2,index2 ) {
                            if(index1 == index2 && value1 == $("#IdParticipanteRegistro").val() && value2 == $("#CedulaParticipanteRegistro").val())
                            { 
                                band = true;
                                toastr.error("Error el usuario ya esta registrado para el registro especificado.", "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                            }
                       });
               });
   }
  
   if(!band && $("#IdParticipanteRegistro").val() != -1 && $("#NombreParticipanteRegistro").val() != "" && $("#CedulaParticipanteRegistro").val() != "")
   {
       tableParticipantes.row.add( {
                "Id del registro": $("#IdParticipanteRegistro").val(),
                "Nombre": $("#NombreParticipanteRegistro").val(),
                "Cedula": $("#CedulaParticipanteRegistro").val()
        } ).draw();
   }
}); 

$('#tableParticipantes').on( 'click', 'tbody tr', function () {
    tableParticipantes.row( this ).remove().draw();
} );


$('#agregarCargoDatos').click(function(){
    toastr.clear();
    var band = false;
    if(($('#tableCargo td').length) > 0)
    {
         tableCargo.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#cargosDatos").find('option:selected').val())
                    {
                        band = true;
                        toastr.error("Error el cargo ya se encuentra en la tabla.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                         });
                    }
               });
   }
   
   $("#headerCargos").css({ 'color': "black" });
   $("#spanCargosDatos").removeClass("glyphicon-remove");
   $("#divCargosDatos").removeClass("has-error");
   
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
   }else if(!band)
   {
        toastr.error("Error debe seleccionar un cargo.", "Error", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                     });
        $("#headerCargos").css({ 'color': "red" });
        $("#spanCargosDatos").addClass("glyphicon-remove");
        $("#divCargosDatos").addClass("has-error");
   }
});

$('#rolUser').change(function(){
    toastr.clear();
    var band = false;
    if(($('#tableRol td').length) > 0)
    {
         tableRol.column(0)
              .data()
              .each( function ( value,index ) {
                    //alert($("#rolUser").find('option:selected').val());
                    if(value == $("#rolUser").find('option:selected').val())
                    {
                        band = true;
                        toastr.error("Error el rol ya se encuentra en la tabla.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                        });
                    }
               });
   }
   
   if(($('#tableRol td').length)==0 && $("#rolUser").find('option:selected').val() != "")
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

$('#agregarRevista').click(function(){
    toastr.clear();
    var band = false;
    if(($('#tableRevista td').length) > 0)
    {
         tableRevista.column(0)
              .data()
              .each( function ( value1,index1 ) {
                  tableRevista.column(1)
                      .data()
                      .each( function ( value2,index2 ) {
                            if(index1 == index2 && value1 == $("#idRevistaRegistro").val() && value2 ==  $("#descrpcionRevistaRegistro").val())
                            { 
                                band = true;
                                toastr.error("Error la revista ya esta registrada para el registro especificado.", "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                            }
                       });
               });
    }
   
   if(!band && $("#idRevistaRegistro").val() != -1 && $("#descrpcionRevistaRegistro").val() != "")
   {
       tableRevista.row.add( {
                "Id del registro": $("#idRevistaRegistro").val(),
                "Revista": $("#descrpcionRevistaRegistro").val()
        } ).draw();
   }
});

$('#tableRevista').on( 'click', 'tbody tr', function () {
    tableRevista.row( this ).remove().draw();
} );