var tieneArchivosPersonal = false;
var tieneArchivosHijos = false;
var referenciasParticipantes = [];
var indReferenciasParticipantes = 0;
var referenciasRevistas = [];
var archivosBienEscritos = true;
var archivosBienEscritosHijos = true;
var cargaCompletaHijos = true;
var cargaCompletaUsuario = true;

var indReferenciasRevistas = 0;
var indPersonalData = 0;
var indPersonalData2 = 0;
var indCargoData = 0;
var countRegistro = 0;
var countParticipante = 0;
var countRevista = 0;
var countHijo = 0;

var personalData = [];
var cargoData = [];
var registrosData = [];
var participantesData = [];
var revistasData = [];
var hijoData = [];
var fechasArchivos = [];



$('input[type="file"]').change(function(){
    var name;
    var band = false;
    switch(this.name)
    {
        case "input2[]":
                input2bool = true;
                i = 0;
                while(i < this.files.length && !band)
                {
                  name = this.files[i].name;
                  for (var j=0; j < this.files.length; j++) {
                      if(name.localeCompare("Acta_nacimiento_"+(j+1)+".pdf") == 0 && !band)
                      {
                          band = true;
                      }
                  }
                  if(band)
                  {
                    band = false;
                    archivosBienEscritosHijos = true;
                  }
                  else
                  {
                     band = true;
                     archivosBienEscritosHijos = false;
                  }
                  i++;
                }
            break;
        case "input3[]":
                input3bool = true;
                for (var i=0; i < this.files.length; i++) {
                  if(this.files[i].name.localeCompare("Cedula.pdf") == 0)
                  {
                    archivosBienEscritos = true;
                  }else if (this.files[i].name.localeCompare("Rif.pdf") == 0)
                  {
                    archivosBienEscritos = true;
                  }else if(this.files[i].name.localeCompare("Acta_nacimiento.pdf") == 0)
                  {
                    archivosBienEscritos = true;
                  }else
                  {
                    archivosBienEscritos = false;
                  }
                }
            break;
    }
    
});

$('#ActaNacCargaHijoDatos').on('filebatchuploadcomplete', function(event, files, extra) {
    cargaCompletaHijos = true;
    if(cargaCompletaHijos && cargaCompletaUsuario)
    {
        $("#myModal2").modal("hide");
        window.location.href = routeRegistroUnico['registro_consulta_index_success'];
    }
});

$('#CedulaRifActaCargaDatos').on('filebatchuploadcomplete', function(event, files, extra) {
    cargaCompletaUsuario = true;
    if(cargaCompletaHijos && cargaCompletaUsuario)
    {
        $("#myModal2").modal("hide");
        window.location.href = routeRegistroUnico['registro_consulta_index_success'];
    }
});

$('#ActaNacCargaHijoDatos').on('filepreajax', function(event, previewId, index) {
    cargaCompletaHijos = false;
});

$('#CedulaRifActaCargaDatos').on('filepreajax', function(event, previewId, index) {
    cargaCompletaUsuario = false;
});

$("#guardar").click(function(){
    var can_update = false;
    var text = "";

    toastr.clear();
    $("#modalLabel").html("Actualizando datos...");
    personalData = new Array();
    cargoData = new Array();
    registrosData = new Array();
    participantesData = new Array();
    participantesData.splice(0,participantesData.length);
    countParticipante=0;
    revistasData = new Array();
    hijoData = new Array();
    fechasArchivos = new Array();
    
    if(archivosBienEscritosHijos)
    {
        if(archivosBienEscritos)
        {
            if(validarDatosPersonales())
            {
                if(!validarCargos())
                    text = "Campos mal introducido u obligatorio en la sección de cargos.";
                else if(!validarNombresRegistros() || !validarRegistros())
                    text = "Campos mal introducido, obligatorio en la sección de registros o existen registros repetidos.";
                else if(!validarParticipantes())
                    text = "Campos mal introducido, obligatorio, registro no asociados o existen participantes repetidos para un registro en la sección de participantes.";
                else if(!validarRevistas())
                    text = "Campos mal introducido, obligatorio, registro no asociados o existen revistas repetidas para un registro en la sección de revistas.";        
                else if($("#checkboxHijos").prop('checked'))
                {
                    if(!validarHijos())
                        text = "Campos mal introducido, datos sin introducir, faltan o sobran documentos en la sección de hijos, solamente se permiten 6 hijos maximo.";        
                    else
                    {
                        participantesData = burbuja(participantesData);
                        revistasData = burbuja(revistasData);
                        fechasArchivos[0] = $("#FechaVencimientoCedulaDatos").val();
                        fechasArchivos[1] = $("#FechaVencimientoRifDatos").val();
                        fechasArchivos[2] = $("#FechaVencimientoActaNacimientoDatos").val();
                        $.ajax({
                            method: "POST",
                            data: {"hijoData":hijoData,"personalData":personalData,"cargoData":cargoData,"registrosData":registrosData,"participantesData":participantesData,"revistasData":revistasData, "fechasArchivos":fechasArchivos, "input2bool":input2bool, "input3bool":input3bool},
                            url:  routeRegistroUnico['registro_editardatos_ajax'],
                            dataType: 'json',
                            beforeSend: function(){
                               $("#myModal2").modal("show");
                            },
                            success: function(data){
                                $("#modalLabel").html("Actualizando archivos del usuario...");
                                if(input2bool)
                                    $("#ActaNacCargaHijoDatos").fileinput("upload");
                                if(input3bool)
                                    $("#CedulaRifActaCargaDatos").fileinput("upload");
                                if(!input2bool && !input3bool)
                                {
                                    $("#myModal2").modal("hide");
                                    window.location.href = routeRegistroUnico['registro_consulta_index_success'];
                                }
                                
                            }
                        });
                        can_update = true;
                    }
                }
                else
                {   
                    participantesData = burbuja(participantesData);
                    revistasData = burbuja(revistasData);
                    fechasArchivos[0] = $("#FechaVencimientoCedulaDatos").val();
                    fechasArchivos[1] = $("#FechaVencimientoRifDatos").val();
                    fechasArchivos[2] = $("#FechaVencimientoActaNacimientoDatos").val();
                    $.ajax({
                        method: "POST",
                        data: {"hijoData":null,"personalData":personalData,"cargoData":cargoData,"registrosData":registrosData,"participantesData":participantesData,"revistasData":revistasData, "fechasArchivos":fechasArchivos, "input2bool":false, "input3bool":input3bool, "tipoDedicacion": $("#tipoDedicacionDatos").find('option:selected').val()},
                        url:  routeRegistroUnico['registro_editardatos_ajax'],
                        dataType: 'json',
                        beforeSend: function(){
                            $("#myModal2").modal("show");
                        },
                        success: function(data){
                            $("#modalLabel").html("Actualizando archivos del usuario...");
                            if(input3bool)
                                $("#CedulaRifActaCargaDatos").fileinput("upload");
                            if(!input3bool)
                            {
                                $("#myModal2").modal("hide");
                                window.location.href = routeRegistroUnico['registro_consulta_index_success'];
                            }
                        }
                    });
                    can_update = true;
                }
            }else
                can_update = true;
        }else
            text = "Los archivos deben de tener los nombre bien especificados (Cedula.pdf,RIF.pdf,Acta_nacimiento.pdf) respectivamente.";        
    }else
        text = "Los archivos deben de tener los nombre bien especificados (Acta_nacimiento_1.pdf,Acta_nacimiento_2.pdf,Acta_nacimiento_3.pdf,......,Acta_nacimiento_n.pdf) respectivamente.";
    if(!can_update)
        toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                    });
});

function validarDatosPersonales()
{
    var inputsO = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos","NacionalidadDatos","FechaNacimientoDatos","EdadDatos","SexoDatos","RifDatos", "NumeroDatos", "NumeroDatosII","CedulaRifActaCargaDatos","FechaVencimientoCedulaDatos","FechaVencimientoRifDatos","FechaVencimientoActaNacimientoDatos"];
    var inputsW = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos"];
    var valido = true;
    var date = new Date();
    var text = "";
    var countFiles; 
    var anio;
    
    indPersonalData = 0;
    indPersonalData2 = 0;


    if($("#CedulaRifActaCargaDatos").val() == "" && !tieneArchivosPersonal)
        countFiles = countFilesPersonal;
    else
    {
        tieneArchivosPersonal = true;
        countFiles = $("#CedulaRifActaCargaDatos").fileinput("getFilesCount");
    }

    for(var i = 0; i < inputsO.length; i++){
        if($("#"+inputsO[i]).val() == "" && inputsO[i] != "CedulaRifActaCargaDatos"){
            if(inputsO[i] != "NumeroDatosII"){
                valido = false;
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
            }else{
                valido = false;
                $("#div"+inputsO[i-1]).addClass("has-error");
            }
            $("#headerPersonal").css({ 'color': "red" });
            text = "Campo mal introducido u obligatorio.";
        }else{
            if(inputsO[i] == "FechaNacimientoDatos")
                anio = parseInt($("#"+inputsO[i]).val()[6]+$("#"+inputsO[i]).val()[7]+$("#"+inputsO[i]).val()[8]+$("#"+inputsO[i]).val()[9]); 
                
            if(inputsO[i] == "CedulaRifActaCargaDatos" && (countFiles > 3 || countFiles < 3)){
                valido = false;
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Son sólo tres archivos en el orden especificado (Cédula, RIF, Acta de nacimiento).";
            }
            
            if(inputsO[i] == "EdadDatos" && (parseInt($("#"+inputsO[i]).val()) > 80 || parseInt($("#"+inputsO[i]).val()) < 18)){
                valido = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Dato inválido.";
            }else if(inputsO[i] == "NumeroDatos" &&  (parseInt($("#"+inputsO[i]).val()) > 999 || parseInt($("#"+inputsO[i]).val()) < 100)){
                valido = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Dato inválido.";
            }else if(inputsO[i] == "NumeroDatosII" &&   (parseInt($("#"+inputsO[i]).val()) > 9999999 || parseInt($("#"+inputsO[i]).val()) < 1000000)){
                valido = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i-1]).addClass("glyphicon-remove");
                $("#div"+inputsO[i-1]).addClass("has-error");
                text = "Dato inválido.";
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
            valido = false;
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

    if(valido && (((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) < -1) || ((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) > 0))){
        valido = false;
        $("#headerPersonal").css({ 'color': "red" });
        text = "La edad no coincide con la fecha de nacimiento.";
    }
    
    personalData[indPersonalData] = $("#DireccionDatos").val();
    indPersonalData++;
    personalData[indPersonalData] = $("#mail").val();
    indPersonalData++;

    if(!valido)
        toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                    });
    else
        toastr.clear();
    return valido;
}

function validarCargos()
{
    var cellsCargos;
    var valido = true;
    var numColumn = obtenerColumnas(tableCargo);
    var numFilas = obtenerFilas(tableCargo);
    
    indCargoData = 0;
    
    for(var i = 0; i < numFilas; i++)
    {
        var cargo = new Object();
        for(var j = 1; j < numColumn-1; j++)
        {
            cellsCargos = new Object();
            cellsCargos.row = i;
            cellsCargos.column = j;
            cellsCargos.columnVisible = "0";
            
            if(findValue(tableCargo, cellsCargos) == "")
                valido = false;
            else
            {
                switch(j)
                {
                    case 1:
                        cargo.nombre = findValue(tableCargo, cellsCargos);
                    break;
                    case 2:
                        cargo.fechaInicio = findValue(tableCargo, cellsCargos);
                    break;
                }
            }
        }
        cargoData[indCargoData] = cargo;
        indCargoData++;
    }
    
    if($("#tipoDedicacionDatos").find('option:selected').val() == "")
    {
        valido = false;
    }
    
    return valido;
}

function validarRegistros()
{
    var cellsRegistro;
    var valido = true;
    var emIns = false;
    var emIns2 = false;
    var emIns3 = false;
    var numColumn = obtenerColumnas(tableRegistros);
    var numFilas = obtenerFilas(tableRegistros);
    countRegistro = 0;
    
    for(var i = 0; i < numFilas; i++)
    {
        var registro = new Object();
        for(var j = 2; j < numColumn-1; j++)
        {
            cellsRegistro = new Object();
            cellsRegistro.row = i;
            cellsRegistro.column = j;
            cellsRegistro.columnVisible = "0";
            if(j != 11)
            {       
                if(j == 2 && findValue(tableRegistros, cellsRegistro) == "Articulo publicado")
                {
                    cellsRegistro.column = 1;
                    referenciasRevistas[indReferenciasRevistas] = tableRegistros.cell(cellsRegistro).data();
                    indReferenciasRevistas++;
                    cellsRegistro.column = j;
                }
                if(j == 2 && (findValue(tableRegistros, cellsRegistro) == "Tutoria de pasantias" || findValue(tableRegistros, cellsRegistro) == "Tutoria de servicio comunitario"  ||  findValue(tableRegistros, cellsRegistro) == "Tutoria de tesis" || findValue(tableRegistros, cellsRegistro) == "Articulo publicado"))
                {
                    cellsRegistro.column = 1;
                    referenciasParticipantes[indReferenciasParticipantes] = tableRegistros.cell(cellsRegistro).data();
                    indReferenciasParticipantes++;
                    cellsRegistro.column = j;
                    emIns = true;
                }
                else if(j == 2 && (findValue(tableRegistros, cellsRegistro) != "Tutoria de pasantias" && findValue(tableRegistros, cellsRegistro) != "Tutoria de servicio comunitario" && findValue(tableRegistros, cellsRegistro) != "Tutoria de tesis" && findValue(tableRegistros, cellsRegistro) != "Articulo publicado" && findValue(tableRegistros, cellsRegistro) != ""))
                    emIns = false;
        
                if(j == 2 && findValue(tableRegistros, cellsRegistro) == "Tutoria de tesis")
                {
                    emIns2 = true;
                }
                else if(j == 2 && findValue(tableRegistros, cellsRegistro) != "Tutoria de tesis" && findValue(tableRegistros, cellsRegistro) != "")
                    emIns2 = false;
                    
                if(j == 2 && findValue(tableRegistros, cellsRegistro) == "Asistencia a Congresos/Seminarios")
                {
                    emIns3 = true;
                }
                else if(j == 2 && findValue(tableRegistros, cellsRegistro) != "Asistencia a Congresos/Seminarios" && findValue(tableRegistros, cellsRegistro) != "")
                    emIns3 = false;
                    
                if(emIns && j == 7 && findValue(tableRegistros, cellsRegistro) == "")
                {
                    valido = false;
                }
                else if(!emIns && j == 7 && findValue(tableRegistros, cellsRegistro) == "" && valido)
                    valido = true;
                    
                if(emIns2 && j == 8 && findValue(tableRegistros, cellsRegistro) == "")
                {
                    valido = false;
                }
                else if(!emIns2 && j == 8 && findValue(tableRegistros, cellsRegistro) == "" && valido)
                    valido = true;
                
                if(emIns3 && (j == 9 || j == 10) && findValue(tableRegistros, cellsRegistro) == "")
                {
                    valido = false;
                }
                else if(!emIns3 && (j == 9 || j == 10)  && findValue(tableRegistros, cellsRegistro) == "" && valido)
                    valido = true;
    
                if(j != 7 && j != 8 && j != 9 && j != 10 && findValue(tableRegistros, cellsRegistro) == "")
                {
                    valido = false;
                }
            }else
                valido = true;
                
            if(valido == true)
            {
                switch(j)
                {
                    case 2:
                        registro.tipoDeReferencia = findValue(tableRegistros, cellsRegistro);
                        cellsRegistro = new Object();
                        cellsRegistro.row = i;
                        cellsRegistro.column = 1;
                        cellsRegistro.columnVisible = "0";
                        registro.idRegistro = tableRegistros.cell(cellsRegistro).data();
                    break;
                    case 3:
                        registro.descripcion = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 4:
                        registro.nivel = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 5:
                        registro.estatus = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 6:
                        registro.anio = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 7:
                        registro.empresaInstitucion = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 8:
                        registro.tituloObtenido = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 9:
                        registro.ciudadPais = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 10:
                        registro.congreso = findValue(tableRegistros, cellsRegistro);
                    break;
                    case 11:
                        registro.url = findValue(tableRegistros, cellsRegistro);
                    break;
                }
                
            }
        }
        registrosData[countRegistro] = registro;
        countRegistro++;
    }
    return valido;
}

function validarNombresRegistros()
{
    var cellsRegistro;
    var valido = true;
    var numColumn = obtenerColumnas(tableRegistros);
    var numFilas = obtenerFilas(tableRegistros);
    var arrayRegistro = [];
    
    cellsRegistro = new Object();
    for(var i = 0; i<numFilas; i++)
    {
        cellsRegistro.row = i;
        cellsRegistro.column = 3;
        cellsRegistro.columnVisible = "0";
        arrayRegistro[i] = findValue( tableRegistros, cellsRegistro);
    }
    
    if(arrayRegistro.length != arrayRegistro.unique().length)
    {
        valido = false;
    }

    return valido;
}


function validarParticipantes()
{
    var cellsParticipantes;
    var valido = true;
    var numColumn = obtenerColumnas(tableParticipantes);
    var numFilas = obtenerFilas(tableParticipantes);
    var referenciasParticipantesAux = [];
    var referenciasParticipantesAux2 = [];
    var indReferenciasParticipantesAux = 0;
    var indReferenciasParticipantesAux2 = 0;
    
    countParticipante = 0;
    
    for(var i = 0; i < numFilas; i++)
    {
        cellsParticipantes = new Object();
        cellsParticipantes.row = i;
        cellsParticipantes.column = 1;
        cellsParticipantes.columnVisible = "0";
        referenciasParticipantesAux[indReferenciasParticipantesAux] = findValue(tableParticipantes, cellsParticipantes);
        indReferenciasParticipantesAux++;
    }

    for(var i = 0; i < numFilas; i++)
    {
        cellsParticipantes = new Object();
        cellsParticipantes.row = i;
        cellsParticipantes.column = 1;
        cellsParticipantes.columnVisible = "0";
        referenciasParticipantesAux2[indReferenciasParticipantesAux2] = findValue(tableParticipantes, cellsParticipantes);
        cellsParticipantes.column = 3;
        referenciasParticipantesAux2[indReferenciasParticipantesAux2] = referenciasParticipantesAux2[indReferenciasParticipantesAux2] + findValue(tableParticipantes, cellsParticipantes);
        indReferenciasParticipantesAux2++;
    }
    if((referenciasParticipantesAux.unique().filter(Boolean).length != referenciasParticipantes.filter(Boolean).length) || (referenciasParticipantesAux2.filter(Boolean).length != referenciasParticipantesAux2.unique().filter(Boolean).length))
    {
        referenciasRevistas.splice(0,referenciasRevistas.length);
        referenciasRevistas = new Array();
        indReferenciasRevistas=0;
        valido = false;
    }
    else
    {
        for(var i = 0; i < numFilas; i++)
        {
            var participante = new Object();
            for(var j = 2; j < numColumn-1; j++)
            {
                cellsParticipantes = new Object();
                cellsParticipantes.row = i;
                cellsParticipantes.column = j;
                cellsParticipantes.columnVisible = "0";
                if(findValue(tableParticipantes, cellsParticipantes) == "")
                {
                    referenciasRevistas.splice(0,referenciasRevistas.length);
                    referenciasRevistas = new Array();
                    indReferenciasRevistas=0;
                    valido = false;
                }else
                {
                    switch (j) {
                        case 2:
                            if(!(/^[a-zA-Z]*$/).test(findValue(tableParticipantes, cellsParticipantes)))
                            {
                                referenciasRevistas.splice(0,referenciasRevistas.length);
                                referenciasRevistas = new Array();
                                indReferenciasRevistas=0;
                                valido = false;
                            }
                            participante.nombre = findValue(tableParticipantes, cellsParticipantes);
                            cellsParticipantes = new Object();
                            cellsParticipantes.row = i;
                            cellsParticipantes.column = j-1;
                            cellsParticipantes.columnVisible = "0";
                            participante.idRegistro = findValue(tableParticipantes, cellsParticipantes);
                            break;
                        case 3:
                            participante.cedula = findValue(tableParticipantes, cellsParticipantes);
                            break;
                    }
                }
            }
            participantesData[countParticipante] = participante;
            countParticipante++;
        }
    }
    referenciasParticipantes.splice(0,referenciasParticipantes.length);
    referenciasParticipantes = new Array();
    indReferenciasParticipantes=0;
    return valido;
}

function validarRevistas()
{
    var cellsRevistas;
    var valido = true;
    var numColumn = obtenerColumnas(tableRevista);
    var numFilas = obtenerFilas(tableRevista);
    var referenciasRevistasAux = [];
    var referenciasRevistasAux2 = [];
    var indReferenciasRevistasAux = 0;
    var indReferenciasRevistasAux2 = 0;

    countRevista = 0;
    
    for(var i = 0; i < numFilas; i++)
    {
        cellsRevistas = new Object();
        cellsRevistas.row = i;
        cellsRevistas.column = 1;
        cellsRevistas.columnVisible = "0";
        referenciasRevistasAux[indReferenciasRevistasAux] = findValue(tableRevista, cellsRevistas);
        indReferenciasRevistasAux++;
    }

    for(var i = 0; i < numFilas; i++)
    {
        cellsRevistas = new Object();
        cellsRevistas.row = i;
        cellsRevistas.column = 2;
        cellsRevistas.columnVisible = "0";
        referenciasRevistasAux2[indReferenciasRevistasAux2] = findValue(tableRevista, cellsRevistas);
        indReferenciasRevistasAux2++;
    }
    if((referenciasRevistasAux.unique().filter(Boolean).length != referenciasRevistas.filter(Boolean).length) || (referenciasRevistasAux2.filter(Boolean).length != referenciasRevistasAux2.unique().filter(Boolean).length))
        valido = false;
    else
    {
        for(var i = 0; i < numFilas; i++)
        {
            var revista = new Object();
            for(var j = 2; j < numColumn-1; j++)
            {
                cellsRevistas = new Object();
                cellsRevistas.row = i;
                cellsRevistas.column = j;
                cellsRevistas.columnVisible = "0";
                if(findValue(tableRevista, cellsRevistas) == "" && j != 3 && j != 4)
                {
                    valido = false;
                }
                else
                {
                    switch (j) {
                        case 2:
                            revista.revista = findValue(tableRevista, cellsRevistas);
                            cellsRevistas = new Object();
                            cellsRevistas.row = i;
                            cellsRevistas.column = j-1;
                            cellsRevistas.columnVisible = "0";
                            revista.idRegistro = findValue(tableRevista, cellsRevistas);
                            break;
                         case 3:
                            revista.volumen = findValue(tableRevista, cellsRevistas);
                            break;
                         case 4:
                            revista.primerayUltimaPagina = findValue(tableRevista, cellsRevistas);
                            break;
                    }
                }
            }
            revistasData[countRevista] = revista;
            countRevista++;
        }
        
    }
    referenciasRevistas.splice(0,referenciasRevistas.length);
    referenciasRevistas = new Array();
    indReferenciasRevistas=0;
    
    referenciasParticipantes.splice(0,referenciasParticipantes.length);
    referenciasParticipantes = new Array();
    indReferenciasParticipantes=0;
    
    return valido;
}

function validarHijos()
{
    var cellsHijos;
    var valido = true;
    var numColumn = obtenerColumnas(tableHijos);
    var numFilas = obtenerFilas(tableHijos);
    var countFiles;
    var cedulasHijos = [];
    var indCedulasHijos = 0;

    countHijo = 0;
    
    if($("#ActaNacCargaHijoDatos").val() == "" && !tieneArchivosHijos)
        countFiles = countFilesHijos;
    else
    {
        tieneArchivosHijos = true;
        countFiles = $("#ActaNacCargaHijoDatos").fileinput("getFilesCount");
    }

    if(countFiles != numFilas)
        valido = false;
    else
    {
        if(numFilas <= 6)
        {
            for(var i = 0; i < numFilas; i++)
            {
                cellsHijos = new Object();
                cellsHijos.row = i;
                cellsHijos.column = 3;
                cellsHijos.columnVisible = "0";
                cedulasHijos[indCedulasHijos] = findValue(tableHijos, cellsHijos);
                indCedulasHijos++;
            }
    
            if((removeItemFromArr(cedulasHijos,"").length != removeItemFromArr(cedulasHijos,"").unique().length))
                valido = false;
            else
            {
                for(var i = 0; i < numFilas; i++)
                {
                    var hijo = new Object();
                    for(var j = 1; j < numColumn-1; j++)
                    {
                        cellsHijos = new Object();
                        cellsHijos.row = i;
                        cellsHijos.column = j;
                        cellsHijos.columnVisible = "0";
                        if(j != 3 && findValue(tableHijos, cellsHijos) == "")
                            valido = false;
                            
                        if(valido)
                        {
                            switch (j) {
                                case 1:
                                        hijo.ciMadre = findValue(tableHijos, cellsHijos);
                                    break;
                                case 2:
                                        hijo.ciPadre = findValue(tableHijos, cellsHijos);
                                    break;
                                case 3:
                                        hijo.ciHijo = findValue(tableHijos, cellsHijos);
                                    break;
                                case 4:
                                        if(!(/^[a-zA-Z]*$/).test(findValue(tableHijos, cellsHijos)))
                                        {
                                            valido = false;
                                        }
                                        hijo.primerNombre = findValue(tableHijos, cellsHijos);
                                    break;
                                case 5:
                                        if(!(/^[a-zA-Z]*$/).test(findValue(tableHijos, cellsHijos)))
                                        {
                                            valido = false;
                                        }
                                        hijo.segundoNombre = findValue(tableHijos, cellsHijos);
                                    break;
                                case 6:
                                        if(!(/^[a-zA-Z]*$/).test(findValue(tableHijos, cellsHijos)))
                                        {
                                            valido = false;
                                        }
                                        hijo.primerApellido = findValue(tableHijos, cellsHijos);
                                    break;
                                case 7:
                                        if(!(/^[a-zA-Z]*$/).test(findValue(tableHijos, cellsHijos)))
                                        {
                                            valido = false;
                                        }
                                        hijo.segundoApellido = findValue(tableHijos, cellsHijos);
                                    break;
                                case 8:
                                        hijo.fechaNacimiento = findValue(tableHijos, cellsHijos);
                                    break;
                                case 9:
                                        hijo.fechaVencimiento = findValue(tableHijos, cellsHijos);
                                    break;
                                case 10:
                                        hijo.nacionalidad = findValue(tableHijos, cellsHijos);
                                    break;
                                
                            }
                        }
                    }
                    hijoData[countHijo] = hijo;
                    countHijo++;
                }
            }
        }else
            valido = false;
    }

    return valido;
}

function obtenerColumnas(table)
{
    return $("#"+table.table().node().id+" tr th").length/2 + 1;
}

function obtenerFilas(table)
{
    return table.page.info().recordsTotal;
}

function removeItemFromArr( arr, item ) 
{
    var i = 0;
    while(i != -1)
    {
        i = arr.indexOf( item );
        if(i != -1)
            arr.splice( i, 1 );
    }
    return arr;
}

Array.prototype.unique=function(a){
    return function(){return this.filter(a)}}(function(a,b,c){return c.indexOf(a,b+1)<0
});

function burbuja(array)
{
    for(var i=1;i<array.length;i++)
    {
        for(var j=0;j<array.length-i;j++)
        {
            if(array[j].idRegistro>array[j+1].idRegistro)
            {
                k=array[j+1];
                array[j+1]=array[j];
                array[j]=k;
            }
        }
    }
 
    return array;
}

function findValue(table, cellTable)
{
    var valor;
    if(table.cell(cellTable).nodes().to$().find('a').length > 0 && table.cell(cellTable).nodes().to$().find('a')[0].href.includes("#"))
        valor = "";
    else if(table.cell(cellTable).nodes().to$().find('a').length > 0)
        valor = table.cell(cellTable).nodes().to$().find('a')[0].href.split('/')[table.cell(cellTable).nodes().to$().find('a')[0].href.split('/').length-1];
    else if(table.cell(cellTable).nodes().to$().find('input').val() != null)
        valor = table.cell(cellTable).nodes().to$().find('input').val();
    else
        valor = table.cell(cellTable).nodes().to$().find('select').val();    
    return valor;
}
