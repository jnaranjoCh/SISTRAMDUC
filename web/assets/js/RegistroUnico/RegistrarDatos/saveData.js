$('#submitData').click(function(){
    toastr.clear();
    var inputsO = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos","NacionalidadDatos","FechaNacimientoDatos","EdadDatos","SexoDatos","RifDatos", "NumeroDatos", "NumeroDatosII","CedulaCargaDatos","RifCargaDatos","ActaNacCargaDatos"];
    var inputsW = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos"];
    var inputsR = ["EstatusDatos","NivelDeEstudioDatos","TipoDeRegistroDatos","DescripcionDatos","AnoPublicacionDatos","EmpresaDatos","InstitucionDatos"];
    var inputsH = ["PrimerNombreHijoDatos","SegundoNombreHijoDatos","PrimerApellidoHijoDatos","SegundoApellidoHijoDatos","NacionalidadHijoDatos","FechaNacimientoHijoDatos","CedulaMadreHijoDatos","CedulaPadreHijoDatos","CedulaHijoDatos","ActaNacCargaHijoDatos"];
    var inputsHW = ["PrimerNombreHijoDatos","SegundoNombreHijoDatos","PrimerApellidoHijoDatos","SegundoApellidoHijoDatos"];
    var idRegistrosParticipantes = [];
    var idParticipantes = [];
    var idRegistrosRevistas = [];
    var idRevistas = [];
    var can_register = true;
    var date = new Date();
    var anio;
    var text = "";
    var indRegistroParticipantes = 0;
    var indParticipantes = 0;
    var indRegistroRevistas = 0;
    var indRevistas = 0;
    var personalData = [];
    var cargoData = [];
    
    var indPersonalData = 0;
    var indPersonalData2 = 0;
    var indCargoData = 0;
 
    
    for(var i = 0; i < inputsO.length; i++){
        if($("#"+inputsO[i]).val() == ""){
            if(inputsO[i] != "NumeroDatosII"){
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
            }else{
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#div"+inputsO[i-1]).addClass("has-error");
            }
            
            text = "Error campo mal introducido o obligatorio.";
        }else{
            if(inputsO[i] == "FechaNacimientoDatos")
              anio = parseInt($("#"+inputsO[i]).val()[6]+$("#"+inputsO[i]).val()[7]+$("#"+inputsO[i]).val()[8]+$("#"+inputsO[i]).val()[9]); 
              
            if(inputsO[i] == "EdadDatos" && (parseInt($("#"+inputsO[i]).val()) > 80 || parseInt($("#"+inputsO[i]).val()) < 18)){
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatos" &&  (parseInt($("#"+inputsO[i]).val()) > 999 || parseInt($("#"+inputsO[i]).val()) < 100)){
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatosII" &&   (parseInt($("#"+inputsO[i]).val()) > 9999999 || parseInt($("#"+inputsO[i]).val()) < 1000000)){
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i-1]).addClass("glyphicon-remove");
                $("#div"+inputsO[i-1]).addClass("has-error");
                text = "Error dato invalida.";
            }else{
                personalData[indPersonalData] = $("#"+inputsO[i]).val();
                indPersonalData++;
                $("#headerPersonal").css({ 'color': "black" });
                $("#span"+inputsO[i]).removeClass("glyphicon-remove");
                $("#div"+inputsO[i]).removeClass("has-error");   
            }
        }
    }
    
    for(var i = 0; i < inputsW.length; i++){
        if(!(/^[a-zA-Z]*$/).test($("#"+inputsW[i]).val())){
            can_register = false;
            $("#headerPersonal").css({ 'color': "red" });
            $("#span"+inputsW[i]).addClass("glyphicon-remove");
            $("#div"+inputsW[i]).addClass("has-error");
            text = "Campo mal introducido.";
            
        }else if($("#"+inputsW[i]).val() != ""){
            personalData[indPersonalData2] = $("#"+inputsW[i]).val();
            indPersonalData2++;
            $("#headerPersonal").css({ 'color': "black" });
            $("#span"+inputsW[i]).removeClass("glyphicon-remove");
            $("#div"+inputsW[i]).removeClass("has-error");
        }
    
    }
    personalData[indPersonalData] = $("#DireccionDatos").val();
    indPersonalData++;
    personalData[indPersonalData] = $("#gemail").val();
    indPersonalData++;
    
    if(can_register && (((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) < -1) || ((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) > 0))){
        can_register = false;
        $("#headerPersonal").css({ 'color': "red" });
        text = "Error la edad no coincide con la fecha de nacimiento.";
    }

    if(can_register && countCargo < 1){
         can_register = false;
         $("#spanCargosDatos").addClass("glyphicon-remove");
         $("#divCargosDatos").addClass("has-error");
         $("#headerCargos").css({ 'color': "red" });
         text = "Error debe seleccionar los cargos.";
    }else{
        tableCargo.column(0)
                  .data()
                  .each( function ( value,index ) {
                    cargoData[indCargoData] = value;
                    indCargoData++;
                  });
        $("#spanCargosDatos").removeClass("glyphicon-remove");
        $("#divCargosDatos").removeClass("has-error");
        $("#headerCargos").css({ 'color': "black" });
    }
    
    if(can_register && countRegistro < 1){
         can_register = false;
         for(var i = 0; i < inputsR.length; i++){
            if(inputsR[i] == "InstitucionDatos"){    
                if($("#TipoDeRegistroDatos").find('option:selected').val() == "Tutoria de servicio comunitario" && $("#"+inputsR[i]).val() == ""){    
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else{
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }else if(inputsR[i] == "EmpresaDatos"){   
                if($("#TipoDeRegistroDatos").find('option:selected').val() == "Tutoria de pasantias" && $("#"+inputsR[i]).val() == ""){    
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else{
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }else{
                if($("#"+inputsR[i]).val() == ""){
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else{
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }
         }
    }else{
        for(var i = 0; i < inputsR.length; i++){    
            $("#headerRegistros").css({ 'color': "black" });
            $("#span"+inputsR[i]).removeClass("glyphicon-remove");
            $("#div"+inputsR[i]).removeClass("has-error");
        }
    }

    tableRegistros.column(1)
                     .data()
                     .each( function ( value1,index1 ) {
                            if(value1=="Tutoria de pasantias" || value1=="Tutoria de servicio comunitario"){
                                tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1==index2){
                                                        idRegistrosParticipantes[indRegistroParticipantes] = value2;
                                                        indRegistroParticipantes++;
                                                    }
                                              });                                   
                            }else if(value1=="Articulo publicado"){
                                tableRegistros.column(0)
                                             .data()
                                             .each( function ( value2,index2 ) {
                                                    if(index1==index2){
                                                        idRegistrosRevistas[indRegistroRevistas] = value2;
                                                        indRegistroRevistas++;
                                                    }
                                              });
                            }
                      });
                      
    if(can_register &&  indRegistroParticipantes>0){
        tableParticipantes.column(0)
                         .data()
                         .each( function ( value,index ) {
                                idParticipantes[indParticipantes]=value;
                                indParticipantes++;
                          });
        idParticipantes = idParticipantes.unique();
        
        if(idParticipantes.length != idRegistrosParticipantes.length){
            can_register = false;
            $("#headerRegistros").css({ 'color': "red" });
            $("#spanNombreParticipanteRegistro").addClass("glyphicon-remove");
            $("#divNombreParticipanteRegistro").addClass("has-error");
            $("#spanCedulaParticipanteRegistro").addClass("glyphicon-remove");
            $("#divCedulaParticipanteRegistro").addClass("has-error");
            $("#spanIdParticipanteRegistro").addClass("glyphicon-remove");
            $("#divIdParticipanteRegistro").addClass("has-error");
            text = "Error existen tipos de registros sin participantes asociados.";
        }else{
            $("#headerRegistros").css({ 'color': "black" });
            $("#spanNombreParticipanteRegistro").removeClass("glyphicon-remove");
            $("#divNombreParticipanteRegistro").removeClass("has-error");
            $("#spanCedulaParticipanteRegistro").removeClass("glyphicon-remove");
            $("#divCedulaParticipanteRegistro").removeClass("has-error");
            $("#spanIdParticipanteRegistro").removeClass("glyphicon-remove");
            $("#divIdParticipanteRegistro").removeClass("has-error");
        }
    }
                      
    if(can_register &&  indRegistroRevistas>0){
        tableRevista.column(0)
                         .data()
                         .each( function ( value,index ) {
                                idRevistas[indRevistas]=value;
                                indRevistas++;
                          });
        idRevistas = idRevistas.unique();
        
        if(idRevistas.length != idRegistrosRevistas.length){
            can_register = false;
            $("#headerRegistros").css({ 'color': "red" });
            $("#spanDescrpcionRevistaRegistro").addClass("glyphicon-remove");
            $("#divDescrpcionRevistaRegistro").addClass("has-error");
            $("#spanIdRevistaRegistro").addClass("glyphicon-remove");
            $("#divIdRevistaRegistro").addClass("has-error");
            text = "Error existen tipos de registros sin revistas asociados.";
        }else{
            $("#headerRegistros").css({ 'color': "black" });
            $("#spanDescrpcionRevistaRegistro").removeClass("glyphicon-remove");
            $("#divDescrpcionRevistaRegistro").removeClass("has-error");
            $("#spanIdRevistaRegistro").removeClass("glyphicon-remove");
            $("#divIdRevistaRegistro").removeClass("has-error");
        }
    }
    
    if($('#checkboxHijos').prop('checked')){
          if(can_register && countHijo < 1){
              can_register = false;
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
              $("#spanFechaNacimientoHijoDatos").addClass("glyphicon-remove");
              $("#divFechaNacimientoHijoDatos").addClass("has-error");
              $("#spanActaNacCargaHijoDatos").addClass("glyphicon-remove");
              $("#divActaNacCargaHijoDatos").addClass("has-error");
              text = "Error no ha ingresado ningun hijo.";
          }else{
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
            $("#spanFechaNacimientoHijoDatos").removeClass("glyphicon-remove");
            $("#divFechaNacimientoHijoDatos").removeClass("has-error");
            $("#spanActaNacCargaHijoDatos").removeClass("glyphicon-remove");
            $("#divActaNacCargaHijoDatos").removeClass("has-error");
        }
    }
    alert(JSON.stringify(revistasData));
    if(can_register){
        $.ajax({
            method: "POST",
            data: {"hijoData":hijoData,"personalData":personalData,"cargoData":cargoData,"registrosData":registrosData,"participantesData":participantesData,"revistasData":revistasData},
            url:  "/web/app_dev.php/registro/guardar-datos",
            dataType: 'json',
            success: function(data)
            {
                    alert(data);
            }
        });
    }
    
    if(!can_register)
        toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });
    else
       toastr.clear();
});

Array.prototype.unique=function(a){
  return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});