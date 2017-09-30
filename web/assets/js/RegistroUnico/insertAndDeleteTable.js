var countRol = 0;
var countHijo = 0;
var countCargo = 0;
var countRegistro = 0;
var countParticipante = 0;
var countRevista = 0;

var registrosData = [];
var participantesData = [];
var revistasData = [];
var hijoData = [];
var cargoData = [];

$('#agregarRegistro').click(function(){
    var inputsR = ["EstatusDatos","NivelDeEstudioDatos","TipoDeRegistroDatos","DescripcionDatos","AnoPublicacionDatos","EmpresaDatos","InstitucionDatos","TituloObtenidoDatos","CiudadPaisDatos","CongresosDatos"];
    toastr.clear();
    var band = false,bandConcatRevist = true,bandConcatPart = true;
    var participantesId="<option value='-1'>No existen registros</option>";
    var revistasId="<option value='-1'>No existen registros</option>";
    var registro = new Object();

    if(($('#tableRegistros td').length) > 0){
         tableRegistros.column(2)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#DescripcionDatos").val()){ 
                        band = true;
                        toastr.error("El registro ya se encuentra en la tabla.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                         });
                    }
               });
   }
   
   if((($("#TipoDeRegistroDatos").find('option:selected').val()=="Asistencia a Congresos/Seminarios" &&  ($("#CongresosDatos").val()=="" || $("#CiudadPaisDatos").val()==""))) || ($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de pasantias" && $("#EmpresaDatos").val()=="") || ($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de tesis" && ($("#TituloObtenidoDatos").val()=="" || $("#InstitucionDatos").val()=="" )))
   {
        $("#headerRegistros").css({ 'color': "red" });
        if($("#TipoDeRegistroDatos").find('option:selected').val()=="Asistencia a Congresos/Seminarios"){
            if($("#CongresosDatos").val()=="" && $("#CiudadPaisDatos").val()=="")
            {
                $("#spanCongresosDatos").addClass("glyphicon-remove");
                $("#divCongresosDatos").addClass("has-error");
                $("#spanCiudadPaisDatos").addClass("glyphicon-remove");
                $("#divCiudadPaisDatos").addClass("has-error");
            }else if($("#CongresosDatos").val()==""){
                $("#spanCongresosDatos").addClass("glyphicon-remove");
                $("#divCongresosDatos").addClass("has-error");
                $("#spanCiudadPaisDatos").removeClass("glyphicon-remove");
                $("#divCiudadPaisDatos").removeClass("has-error");
            }else if($("#CiudadPaisDatos").val()=="")
            {
                $("#spanCiudadPaisDatos").addClass("glyphicon-remove");
                $("#divCiudadPaisDatos").addClass("has-error");
                $("#spanCongresosDatos").removeClass("glyphicon-remove");
                $("#divCongresosDatos").removeClass("has-error");
            }
        }else if($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de pasantias")
        {
            if($("#EmpresaDatos").val()==""){
                $("#spanEmpresaDatos").addClass("glyphicon-remove");
                $("#divEmpresaDatos").addClass("has-error");
            }
        }else if($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de tesis")
        {
            if($("#TituloObtenidoDatos").val()=="" && $("#InstitucionDatos").val()=="")
            {
                $("#spanTituloObtenidoDatos").addClass("glyphicon-remove");
                $("#divTituloObtenidoDatos").addClass("has-error");
                $("#spanInstitucionDatos").addClass("glyphicon-remove");
                $("#divInstitucionDatos").addClass("has-error");
            }else if($("#InstitucionDatos").val()==""){
                $("#spanInstitucionDatos").addClass("glyphicon-remove");
                $("#divInstitucionDatos").addClass("has-error");
                $("#spanTituloObtenidoDatos").removeClass("glyphicon-remove");
                $("#divTituloObtenidoDatos").removeClass("has-error");
            }else if($("#TituloObtenidoDatos").val()=="")
            {
                $("#spanTituloObtenidoDatos").addClass("glyphicon-remove");
                $("#divTituloObtenidoDatos").addClass("has-error");
                $("#spanInstitucionDatos").removeClass("glyphicon-remove");
                $("#divInstitucionDatos").removeClass("has-error");
            }
        }
         toastr.error("Error faltan datos.", "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });
   }
   else{
       if(($('#tableRegistros td').length)==0 && $("#DescripcionDatos").val()!="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="" && $("#NivelDeEstudioDatos").find('option:selected').val()!="" && $("#EstatusDatos").find('option:selected').val()!="" && $("#AnoPublicacionDatos").val()!=""){
            if($("#EmpresaDatos").val()=="" && $("#InstitucionDatos").val()=="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de servicio comunitario" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de pasantias" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de tesis"){
                tableRegistros.row.add( {
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": "",
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                } ).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = "";
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }else if($("#EmpresaDatos").val()!=""){
                tableRegistros.row.add( {
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": $("#EmpresaDatos").val(),
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                } ).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = $("#EmpresaDatos").val();
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }else if($("#InstitucionDatos").val()!=""){
                tableRegistros.row.add( {
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": $("#InstitucionDatos").val(),
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                } ).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = $("#InstitucionDatos").val();
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }
            
            for(var i = 0; i < inputsR.length; i++){
                $("#headerRegistros").css({ 'color': "black" });
                $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                $("#div"+inputsR[i]).removeClass("has-error");
            }
       }else if(!band && $("#DescripcionDatos").val()!="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="" && $("#NivelDeEstudioDatos").find('option:selected').val()!="" && $("#EstatusDatos").find('option:selected').val()!="" && $("#AnoPublicacionDatos").val()!=""){
            if($("#EmpresaDatos").val()=="" && $("#InstitucionDatos").val()=="" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de servicio comunitario" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de pasantias" && $("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de tesis"){
                tableRegistros.row.add({
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": "",
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                }).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = "";
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }else if($("#EmpresaDatos").val()!=""){
                tableRegistros.row.add( {
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": $("#EmpresaDatos").val(),
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                } ).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = $("#EmpresaDatos").val();
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }else if($("#InstitucionDatos").val()!=""){
                tableRegistros.row.add( {
                    "Id del registro": idRegistro,
                    "Tipo de referencia": $("#TipoDeRegistroDatos").find('option:selected').val(),
                    "Descripcion": $("#DescripcionDatos").val(),
                    "Nivel": $("#NivelDeEstudioDatos").find('option:selected').val(),
                    "Estatus": $("#EstatusDatos").find('option:selected').val(),
                    "Año de publicación y/o asistencia": $("#AnoPublicacionDatos").val(),
                    "Empresa y/o institución": $("#InstitucionDatos").val(),
                    "Titulo Obtenido":$("#TituloObtenidoDatos").val(),
                    "Ciudad / Pais":$("#CiudadPaisDatos").val(),
                    "Congreso":$("#CongresosDatos").val(),
                    "Archivo":'<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
                } ).draw();
                registro.idRegistro = idRegistro;
                registro.tipoDeReferencia = $("#TipoDeRegistroDatos").find('option:selected').val();
                registro.descripcion = $("#DescripcionDatos").val();
                registro.nivel = $("#NivelDeEstudioDatos").find('option:selected').val();
                registro.estatus = $("#EstatusDatos").find('option:selected').val();
                registro.anio = $("#AnoPublicacionDatos").val();
                registro.empresaInstitucion = $("#InstitucionDatos").val();
                registro.tituloObtenido = $("#TituloObtenidoDatos").val();
                registro.ciudadPais = $("#CiudadPaisDatos").val();
                registro.congreso = $("#CongresosDatos").val();
                registro.url = "";
                registrosData[countRegistro] = registro;
                countRegistro++;
                idRegistro++;
            }
            
            for(var i = 0; i < inputsR.length; i++){
                $("#headerRegistros").css({ 'color': "black" });
                $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                $("#div"+inputsR[i]).removeClass("has-error");
            }
       }else if(!band){
            toastr.error("Error faltan datos.", "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });
            
            if($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de servicio comunitario"){
                for(var i = 0; i < inputsR.length; i++){
                    if(inputsR[i] != "EmpresaDatos"){
                        $("#headerRegistros").css({ 'color': "red" });
                        $("#span"+inputsR[i]).addClass("glyphicon-remove");
                        $("#div"+inputsR[i]).addClass("has-error");
                    }
                }
            }else if($("#TipoDeRegistroDatos").find('option:selected').val()!="Tutoria de servicio comunitario"){
                for(var i = 0; i < inputsR.length; i++){
                    if(inputsR[i] != "InstitucionDatos"){
                        $("#headerRegistros").css({ 'color': "red" });
                        $("#span"+inputsR[i]).addClass("glyphicon-remove");
                        $("#div"+inputsR[i]).addClass("has-error");
                    }
                }
            }else if($("#TipoDeRegistroDatos").find('option:selected').val()=="Tutoria de tesis"){
                for(var i = 0; i < inputsR.length; i++){
                    if(inputsR[i] != "TituloObtenidoDatos"){
                        $("#headerRegistros").css({ 'color': "red" });
                        $("#span"+inputsR[i]).addClass("glyphicon-remove");
                        $("#div"+inputsR[i]).addClass("has-error");
                    }
                }
            }else{
                for(var i = 0; i < inputsR.length; i++){
                    if(inputsR[i] != "InstitucionDatos" && inputsR[i] != "EmpresaDatos"){
                        $("#headerRegistros").css({ 'color': "red" });
                        $("#span"+inputsR[i]).addClass("glyphicon-remove");
                        $("#div"+inputsR[i]).addClass("has-error");
                    }
                }
            }
       }
   }
   
   tableRegistros.column(1)
                 .data()
                 .each( function ( value1,index1 ) {
                        if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario" || value1=="Tutoria de tesis" || value1=="Articulo publicado"){
                           if(bandConcatPart){
                                participantesId="<option value='-1'>Seleccione una opción</option>";
                                bandConcatPart = false;
                           }
                           
                           tableRegistros.column(0)
                                         .data()
                                         .each( function ( value2,index2 ) {
                                                if(index1 == index2)
                                                    participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                          });     
                        }
                        if(value1=="Articulo publicado"){
                            if(bandConcatRevist){
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
    if($('#tableRevista td').length == 1){
        revistasData.splice(0,revistasData.length);
        revistasData = new Array();
    }
                                                
});

$('#tableRegistros').on( 'click', 'tbody tr', function () {
    var bandConcatRevist = true,bandConcatPart = true;
    var participantesId="<option value='-1'>No existen registros</option>";
    var revistasId="<option value='-1'>No existen registros</option>";

    tableRegistros.row( this ).remove().draw();
    if(countRegistro > 0){
       tableRegistros.column(1)
                     .data()
                     .each( function ( value1,index1 ) {
                            if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario" || value1=="Tutoria de tesis" || value1=="Articulo publicado"){
                               if(bandConcatPart){
                                    participantesId="<option value='-1'>Seleccione una opción</option>";
                                    bandConcatPart = false;
                               }
                               tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1 == index2)
                                                        participantesId = participantesId+"<option value='"+value2+"'>"+value2+"</option>";
                                              });                         
                            }
                            if(value1=="Articulo publicado"){
                                if(bandConcatRevist){
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
        registrosData.splice(this._DT_RowIndex,1);
        countRegistro--;
    }
} );

$('#agregarParticipantes').click(function(){
    toastr.clear();
    var band = false;
    var participante = new Object();
    if(($('#tableParticipantes td').length) > 0){
         tableParticipantes.column(0)
              .data()
              .each( function ( value1,index1 ) {
                  tableParticipantes.column(2)
                      .data()
                      .each( function ( value2,index2 ) {
                            if(index1 == index2 && value1 == $("#IdParticipanteRegistro").val() && value2 == $("#CedulaParticipanteRegistro").val()){ 
                                band = true;
                                toastr.error("Error el usuario ya esta registrado para el registro especificado.", "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                            }
                       });
               });
   }
  
   if(!band && !(/^[a-zA-Z]*$/).test($("#NombreParticipanteRegistro").val()))
   {
        $("#spanNombreParticipanteRegistro").addClass("glyphicon-remove");
        $("#divNombreParticipanteRegistro").addClass("has-error");
        toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerRegistros").css({ 'color': "red" });
       band = true;
   }
   
   if(!band && $("#IdParticipanteRegistro").val() != -1 && $("#NombreParticipanteRegistro").val() != "" && $("#CedulaParticipanteRegistro").val() != ""){
       tableParticipantes.row.add( {
                "Id del registro": $("#IdParticipanteRegistro").val(),
                "Nombre": $("#NombreParticipanteRegistro").val(),
                "Cedula": $("#CedulaParticipanteRegistro").val()
        } ).draw();
        participante.idRegistro = $("#IdParticipanteRegistro").val();
        participante.nombre = $("#NombreParticipanteRegistro").val();
        participante.cedula = $("#CedulaParticipanteRegistro").val();
        participantesData[countParticipante] = participante;
        countParticipante++;
        $("#headerRegistros").css({ 'color': "black" });
        $("#spanIdParticipanteRegistro").removeClass("glyphicon-remove");
        $("#divIdParticipanteRegistro").removeClass("has-error");
        $("#spanNombreParticipanteRegistro").removeClass("glyphicon-remove");
        $("#divNombreParticipanteRegistro").removeClass("has-error");
        $("#spanCedulaParticipanteRegistro").removeClass("glyphicon-remove");
        $("#divCedulaParticipanteRegistro").removeClass("has-error");
   }else if(!band){
       toastr.error("Error faltan datos.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });

        $("#headerRegistros").css({ 'color': "red" });
        $("#spanIdParticipanteRegistro").addClass("glyphicon-remove");
        $("#divIdParticipanteRegistro").addClass("has-error");
        $("#spanNombreParticipanteRegistro").addClass("glyphicon-remove");
        $("#divNombreParticipanteRegistro").addClass("has-error");
        $("#spanCedulaParticipanteRegistro").addClass("glyphicon-remove");
        $("#divCedulaParticipanteRegistro").addClass("has-error");
   }
}); 

$('#tableParticipantes').on( 'click', 'tbody tr', function () {
    tableParticipantes.row( this ).remove().draw();
    participantesData.splice(this._DT_RowIndex,1);
    if(countParticipante > 0)
        countParticipante--;
} );


$('#agregarCargoDatos').click(function(){
    var cargo = new Object();
    toastr.clear();
    var band = false;
    if(($('#tableCargo td').length) > 0){
         tableCargo.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#cargosDatos").find('option:selected').val()){
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
   $("#divFechaInicioCargoDatos").removeClass("has-error");
   
   if(($('#tableCargo td').length)==0 && $("#cargosDatos").find('option:selected').val() != "" && $("#FechaInicioCargoDatos").val() != ""){
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val(),
                "Fecha de inicio en el cargo": $("#FechaInicioCargoDatos").val()
       }).draw();
       cargo.nombre = $("#cargosDatos").find('option:selected').val();
       cargo.fechaInicio = $("#FechaInicioCargoDatos").val();
       cargoData[countCargo] = cargo;
       countCargo++;
   }else if(!band && $("#cargosDatos").find('option:selected').val() != "" && $("#FechaInicioCargoDatos").val() != ""){
       tableCargo.row.add( {
                "Cargo": $("#cargosDatos").find('option:selected').val(),
                "Fecha de inicio en el cargo": $("#FechaInicioCargoDatos").val()
        }).draw();
       cargo.nombre = $("#cargosDatos").find('option:selected').val();
       cargo.fechaInicio = $("#FechaInicioCargoDatos").val();
       cargoData[countCargo] = cargo;
       countCargo++;
   }else if(!band){
        toastr.error("Error debe seleccionar un cargo y la fecha de inicio.", "Error", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                     });
        $("#headerCargos").css({ 'color': "red" });
        $("#spanCargosDatos").addClass("glyphicon-remove");
        $("#divCargosDatos").addClass("has-error");
        $("#divFechaInicioCargoDatos").addClass("has-error");
   }
});

$('#rolUser').change(function(){
    toastr.clear();
    var band = false;
    if(($('#tableRol td').length) > 0){
         tableRol.column(0)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#rolUser").find('option:selected').val()){
                        band = true;
                        toastr.error("Error el rol ya se encuentra en la tabla.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                        });
                    }
               });
   }
   
   if(($('#tableRol td').length)==0 && $("#rolUser").find('option:selected').val() != ""){
       tableRol.row.add( {
                "Rol": $("#rolUser").find('option:selected').val()
        }).draw();
        countRol++;
   }else if(!band && $("#rolUser").find('option:selected').val() != ""){
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
});

$('#tableCargo').on( 'click', 'tbody tr', function () {
    tableCargo.row( this ).remove().draw();
    if(countCargo > 0)
        countCargo--;
} );

$('#agregarRevista').click(function(){
    var revista = new Object();
    toastr.clear();
    var band = false;
    if(($('#tableRevista td').length) > 0){
         tableRevista.column(0)
              .data()
              .each( function ( value1,index1 ) {
                  tableRevista.column(1)
                      .data()
                      .each( function ( value2,index2 ) {
                            if(index1 == index2 && value1 == $("#idRevistaRegistro").val() && value2 ==  $("#descrpcionRevistaRegistro").val()){ 
                                band = true;
                                toastr.error("Error la revista ya esta registrada para el registro especificado.", "Error", {
                                    "timeOut": "0",
                                    "extendedTImeout": "0"
                                 });
                            }
                       });
               });
    }
   
   if(!band && $("#idRevistaRegistro").val() != -1 && $("#descrpcionRevistaRegistro").val() != ""){
       tableRevista.row.add( {
                "Id del registro": $("#idRevistaRegistro").val(),
                "Revista": $("#descrpcionRevistaRegistro").val(),
                "Volumen": $("#volumenRevistaRegistro").val(),
                "PrimerayUltimaPagina": $("#primerayUltimaPaginaRevistaRegistro").val()
        } ).draw();
        revista.idRegistro = $("#idRevistaRegistro").val();
        revista.revista = $("#descrpcionRevistaRegistro").val();
        revista.volumen = $("#volumenRevistaRegistro").val();
        revista.primerayUltimaPagina = $("#primerayUltimaPaginaRevistaRegistro").val();
        revistasData[countRevista] = revista;
        countRevista++;
        $("#headerRegistros").css({ 'color': "black" });
        $("#spanIdRevistaRegistro").removeClass("glyphicon-remove");
        $("#divIdRevistaRegistro").removeClass("has-error");
        $("#spanDescrpcionRevistaRegistro").removeClass("glyphicon-remove");
        $("#divDescrpcionRevistaRegistro").removeClass("has-error");
        $("#spanVolumenRevistaRegistro").removeClass("glyphicon-remove");
        $("#divVolumenRevistaRegistro").removeClass("has-error");
        $("#spanPrimerayUltimaPaginaRevistaRegistro").removeClass("glyphicon-remove");
        $("#divPrimerayUltimaPaginaRevistaRegistro").removeClass("has-error");
   }else if(!band){
       toastr.error("Error faltan datos.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });

        $("#headerRegistros").css({ 'color': "red" });
        $("#spanIdRevistaRegistro").addClass("glyphicon-remove");
        $("#divIdRevistaRegistro").addClass("has-error");
        $("#spanDescrpcionRevistaRegistro").addClass("glyphicon-remove");
        $("#divDescrpcionRevistaRegistro").addClass("has-error");
        $("#spanVolumenRevistaRegistro").addClass("glyphicon-remove");
        $("#divVolumenRevistaRegistro").addClass("has-error");
        $("#spanPrimerayUltimaPaginaRevistaRegistro").addClass("glyphicon-remove");
        $("#divPrimerayUltimaPaginaRevistaRegistro").addClass("has-error");
   }
});

$('#tableRevista').on( 'click', 'tbody tr', function () {
    tableRevista.row( this ).remove().draw();
    revistasData.splice(this._DT_RowIndex,1);
    if(countRevista > 0)
        countRevista--;
} );

$('#agregarHijo').click(function(){
    var hijo = new Object();
    toastr.clear();
    var band = false;
    if(($('#tableHijos td').length) > 0){
         tableHijos.column(2)
              .data()
              .each( function ( value,index ) {
                    if(value == $("#CedulaHijoDatos").val() && $("#CedulaHijoDatos").val() != 0){ 
                        band = true;
                        toastr.error("Error el hijo ya se encuentra registrado.", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                         });
                    }
               });
    }
    
   if(!band && !(/^\d*$/).test($("#CedulaMadreHijoDatos").val()))
   {
       $("#spanCedulaMadreHijoDatos").addClass("glyphicon-remove");
       $("#divCedulaMadreHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!band && !(/^\d*$/).test($("#CedulaPadreHijoDatos").val()))
   {
       $("#spanCedulaPadreHijoDatos").addClass("glyphicon-remove");
       $("#divCedulaPadreHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!(/^\d*$/).test($("#CedulaHijoDatos").val()))
   {
       $("#spanCedulaHijoDatos").addClass("glyphicon-remove");
       $("#divCedulaHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!band && !(/^[a-zA-Z]*$/).test($("#PrimerNombreHijoDatos").val()))
   {
       $("#spanPrimerNombreHijoDatos").addClass("glyphicon-remove");
       $("#divPrimerNombreHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!band && !(/^[a-zA-Z]*$/).test($("#SegundoNombreHijoDatos").val()))
   {
       $("#spanSegundoNombreHijoDatos").addClass("glyphicon-remove");
       $("#divSegundoNombreHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!band && !(/^[a-zA-Z]*$/).test($("#PrimerApellidoHijoDatos").val()))
   {
       $("#spanPrimerApellidoHijoDatos").addClass("glyphicon-remove");
       $("#divPrimerApellidoHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }else if(!band && !(/^[a-zA-Z]*$/).test($("#SegundoApellidoHijoDatos").val()))
   {
       $("#spanSegundoApellidoHijoDatos").addClass("glyphicon-remove");
       $("#divSegundoApellidoHijoDatos").addClass("has-error");
       toastr.error("Error campo mal introducido.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });
       $("#headerHijos").css({ 'color': "red" });
       band = true;
   }
       
   
   if(!band && $("#CedulaMadreHijoDatos").val() != "" && $("#CedulaPadreHijoDatos").val() != "" && $("#PrimerNombreHijoDatos").val() != "" && $("#SegundoNombreHijoDatos").val() != "" && $("#PrimerApellidoHijoDatos").val() != "" && $("#SegundoApellidoHijoDatos").val() != "" && $("#FechaNacimientoHijoDatos").val() != "" && $("#NacionalidadHijoDatos").val() != "" && $("#input-2").val() != ""  && $("#FechaVencimientoActaNacimientoHijoDatos").val() != ""){
        if($("#CedulaHijoDatos").val() != "")
           tableHijos.row.add( {
                "CI Madre":$("#CedulaMadreHijoDatos").val(),
                "CI Padre":$("#CedulaPadreHijoDatos").val(),
                "CI Hijo":$("#CedulaHijoDatos").val(),
                "1er Nombre":$("#PrimerNombreHijoDatos").val(),
                "2do Nombre":$("#SegundoNombreHijoDatos").val(),
                "1er Apellido":$("#PrimerApellidoHijoDatos").val(),
                "2do Apellido":$("#SegundoApellidoHijoDatos").val(),
                "F Nacimiento":$("#FechaNacimientoHijoDatos").val(),
                "F Vencimiento Acta":$("#FechaVencimientoActaNacimientoHijoDatos").val(),
                "Nacionalidad":$("#NacionalidadHijoDatos").val()
            } ).draw();
        else
            tableHijos.row.add( {
                "CI Madre":$("#CedulaMadreHijoDatos").val(),
                "CI Padre":$("#CedulaPadreHijoDatos").val(),
                "CI Hijo":0,
                "1er Nombre":$("#PrimerNombreHijoDatos").val(),
                "2do Nombre":$("#SegundoNombreHijoDatos").val(),
                "1er Apellido":$("#PrimerApellidoHijoDatos").val(),
                "2do Apellido":$("#SegundoApellidoHijoDatos").val(),
                "F Nacimiento":$("#FechaNacimientoHijoDatos").val(),
                "F Vencimiento Acta":$("#FechaVencimientoActaNacimientoHijoDatos").val(),
                "Nacionalidad":$("#NacionalidadHijoDatos").val()
            } ).draw();
        hijo.ciMadre = $("#CedulaMadreHijoDatos").val();
        hijo.ciPadre = $("#CedulaPadreHijoDatos").val();
        hijo.ciHijo = $("#CedulaHijoDatos").val();
        hijo.primerNombre = $("#PrimerNombreHijoDatos").val();
        hijo.segundoNombre = $("#SegundoNombreHijoDatos").val();
        hijo.primerApellido = $("#PrimerApellidoHijoDatos").val();
        hijo.segundoApellido = $("#SegundoApellidoHijoDatos").val();
        hijo.fechaNacimiento = $("#FechaNacimientoHijoDatos").val();
        hijo.fechaVencimiento = $("#FechaVencimientoActaNacimientoHijoDatos").val();
        hijo.nacionalidad = $("#NacionalidadHijoDatos").val();
        hijoData[countHijo] = hijo;
        countHijo++;
        $("#headerHijos").css({ 'color': "black" });
        $("#spanCedulaMadreHijoDatos").removeClass("glyphicon-remove");
        $("#divCedulaMadreHijoDatos").removeClass("has-error");
        $("#spanCedulaPadreHijoDatos").removeClass("glyphicon-remove");
        $("#divCedulaPadreHijoDatos").removeClass("has-error");
        $("#spanCedulaHijoDatos").removeClass("glyphicon-remove");
        $("#divCedulaHijoDatos").removeClass("has-error");
        $("#spanPrimerNombreHijoDatos").removeClass("glyphicon-remove");
        $("#divPrimerNombreHijoDatos").removeClass("has-error");
        $("#spanSegundoNombreHijoDatos").removeClass("glyphicon-remove");
        $("#divSegundoNombreHijoDatos").removeClass("has-error");
        $("#spanPrimerApellidoHijoDatos").removeClass("glyphicon-remove");
        $("#divPrimerApellidoHijoDatos").removeClass("has-error");
        $("#spanSegundoApellidoHijoDatos").removeClass("glyphicon-remove");
        $("#divSegundoApellidoHijoDatos").removeClass("has-error");
        $("#spanNacionalidadHijoDatos").removeClass("glyphicon-remove");
        $("#divNacionalidadHijoDatos").removeClass("has-error");
        $("#divFechaVencimientoActaNacimientoHijoDatos").removeClass("has-error");
        $("#spanFechaNacimientoHijoDatos").removeClass("glyphicon-remove");
        $("#divFechaNacimientoHijoDatos").removeClass("has-error");
        $("#spanActaNacCargaHijoDatos").removeClass("glyphicon-remove");
        $("#divActaNacCargaHijoDatos").removeClass("has-error");
   }else if(!band){
       toastr.error("Error faltan datos.", "Error", {
                "timeOut": "0",
                "extendedTImeout": "0"
             });

        $("#headerHijos").css({ 'color': "red" });
        $("#spanCedulaMadreHijoDatos").addClass("glyphicon-remove");
        $("#divCedulaMadreHijoDatos").addClass("has-error");
        $("#spanCedulaPadreHijoDatos").addClass("glyphicon-remove");
        $("#divCedulaPadreHijoDatos").addClass("has-error");
        $("#spanCedulaHijoDatos").addClass("glyphicon-remove");
        $("#divCedulaHijoDatos").addClass("has-error");
        $("#spanPrimerNombreHijoDatos").addClass("glyphicon-remove");
        $("#divPrimerNombreHijoDatos").addClass("has-error");
        $("#spanSegundoNombreHijoDatos").addClass("glyphicon-remove");
        $("#divSegundoNombreHijoDatos").addClass("has-error");
        $("#spanPrimerApellidoHijoDatos").addClass("glyphicon-remove");
        $("#divPrimerApellidoHijoDatos").addClass("has-error");
        $("#spanSegundoApellidoHijoDatos").addClass("glyphicon-remove");
        $("#divSegundoApellidoHijoDatos").addClass("has-error");
        $("#spanNacionalidadHijoDatos").addClass("glyphicon-remove");
        $("#divNacionalidadHijoDatos").addClass("has-error");
        $("#divFechaVencimientoActaNacimientoHijoDatos").addClass("has-error");
        $("#spanFechaNacimientoHijoDatos").addClass("glyphicon-remove");
        $("#divFechaNacimientoHijoDatos").addClass("has-error");
        $("#spanActaNacCargaHijoDatos").addClass("glyphicon-remove");
        $("#divActaNacCargaHijoDatos").addClass("has-error");
   }
});

$('#tableHijos').on( 'click', 'tbody tr', function () {
    tableHijos.row( this ).remove().draw();
    hijoData.splice(this._DT_RowIndex,1);
    if(countHijo > 0)
        countHijo--;
});
