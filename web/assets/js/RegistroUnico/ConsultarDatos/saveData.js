var tieneArchivosPersonal = false;
var tieneArchivosHijos = false;
var referenciasParticipantes = [];
var indReferenciasParticipantes = 0;
var referenciasRevistas = [];
var archivosBienEscritos = true;
var archivosBienEscritosHijos = true;

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
        
$("#guardar").click(function(){
    var can_update = false;
    var text = "";

    toastr.clear();
    
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
                    text = "Error campos mal introducido o obligatorio en la sección de cargos.";
                else if(!validarNombresRegistros() || !validarRegistros())
                    text = "Error campos mal introducido, obligatorio en la sección de registros o existen registros repetidos.";
                else if(!validarParticipantes())
                    text = "Error campos mal introducido, obligatorio, registro no asociados o existen participantes repetidos para un registro en la sección de participantes.";
                else if(!validarRevistas())
                    text = "Error campos mal introducido, obligatorio, registro no asociados o existen revistas repetidas para un registro en la sección de revistas.";        
                else if($("#checkboxHijos").prop('checked'))
                {
                    if(!validarHijos())
                        text = "Error campos mal introducido, datos sin introducir, faltan o sobran documentos en la sección de hijos.";        
                    else
                    {
                        fechasArchivos[0] = $("#FechaVencimientoCedulaDatos").val();
                        fechasArchivos[1] = $("#FechaVencimientoRifDatos").val();
                        fechasArchivos[2] = $("#FechaVencimientoActaNacimientoDatos").val();
                        $.ajax({
                            method: "POST",
                            data: {"hijoData":hijoData,"personalData":personalData,"cargoData":cargoData,"registrosData":registrosData,"participantesData":participantesData,"revistasData":revistasData, "fechasArchivos":fechasArchivos, "input2bool":input2bool, "input3bool":input3bool},
                            url:  routeRegistroUnico['registro_editardatos_ajax'],
                            dataType: 'json',
                            beforeSend: function(){
                               // $("#myModal2").modal("show");
                            },
                            success: function(data){
                                alert(data);
                                if(input2bool)
                                    $("#ActaNacCargaHijoDatos").fileinput("upload");
                                if(input3bool)
                                    $("#CedulaRifActaCargaDatos").fileinput("upload");
                                //$("#modalLabel").html("Subiendo archivos del usuario...");
                                //document.getElementById("completeForm").submit();
                            }
                        });
                        can_update = true;
                    }
                }
                else
                {   
                    fechasArchivos[0] = $("#FechaVencimientoCedulaDatos").val();
                    fechasArchivos[1] = $("#FechaVencimientoRifDatos").val();
                    fechasArchivos[2] = $("#FechaVencimientoActaNacimientoDatos").val();
                    $.ajax({
                        method: "POST",
                        data: {"hijoData":null,"personalData":personalData,"cargoData":cargoData,"registrosData":registrosData,"participantesData":participantesData,"revistasData":revistasData, "fechasArchivos":fechasArchivos, "input2bool":false, "input3bool":input3bool},
                        url:  routeRegistroUnico['registro_editardatos_ajax'],
                        dataType: 'json',
                        beforeSend: function(){
                            //$("#myModal2").modal("show");
                        },
                        success: function(data){
                            alert(data);
                            if(input3bool)
                                $("#CedulaRifActaCargaDatos").fileinput("upload");
                            //$("#modalLabel").html("Subiendo archivos del usuario...");
                            //document.getElementById("completeForm").submit();
                        }
                    });
                    can_update = true;
                }
            }else
                can_update = true;
        }else
            text = "Error los archivos deben de tener los nombre bien especificados (Cedula.pdf,RIF.pdf,Acta_nacimiento.pdf) respectivamente.";        
    }else
        text = "Error los archivos deben de tener los nombre bien especificados (Acta_nacimiento_1.pdf,Acta_nacimiento_2.pdf,Acta_nacimiento_3.pdf,......,Acta_nacimiento_n.pdf) respectivamente.";
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
            text = "Error campo mal introducido o obligatorio.";
        }else{
            if(inputsO[i] == "FechaNacimientoDatos")
                anio = parseInt($("#"+inputsO[i]).val()[6]+$("#"+inputsO[i]).val()[7]+$("#"+inputsO[i]).val()[8]+$("#"+inputsO[i]).val()[9]); 
                
            if(inputsO[i] == "CedulaRifActaCargaDatos" && (countFiles > 3 || countFiles < 3)){
                valido = false;
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error son solo tres archivos en el orden especificado (Cedula,RIF,Acta de nacimiento).";
            }
            
            if(inputsO[i] == "EdadDatos" && (parseInt($("#"+inputsO[i]).val()) > 80 || parseInt($("#"+inputsO[i]).val()) < 18)){
                valido = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatos" &&  (parseInt($("#"+inputsO[i]).val()) > 999 || parseInt($("#"+inputsO[i]).val()) < 100)){
                valido = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatosII" &&   (parseInt($("#"+inputsO[i]).val()) > 9999999 || parseInt($("#"+inputsO[i]).val()) < 1000000)){
                valido = false;
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
        text = "Error la edad no coincide con la fecha de nacimiento.";
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
            if($("#"+tableCargo.cell(cellsCargos).data().split('id="')[1].split('"')[0]).val() == "")
                valido = false;
            else
            {
                switch(j)
                {
                    case 1:
                        cargo.nombre = $("#"+tableCargo.cell(cellsCargos).data().split('id="')[1].split('"')[0]).val();
                    break;
                    case 2:
                        cargo.fechaInicio = $("#"+tableCargo.cell(cellsCargos).data().split('id="')[1].split('"')[0]).val();
                    break;
                }
            }
        }
        cargoData[indCargoData] = cargo;
        indCargoData++;
    }
    return valido;
}

function validarRegistros()
{
    var cellsRegistro;
    var valido = true;
    var emIns = false;
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
            if(j == 2 && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "Articulo publicado")
            {
                cellsRegistro.column = 1;
                referenciasRevistas[indReferenciasRevistas] = tableRegistros.cell(cellsRegistro).data();
                indReferenciasRevistas++;
                cellsRegistro.column = j;
            }

            if(j == 2 && ($("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "Tutoria de pasantias" || $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "Tutoria de servicio comunitario"))
            {
                cellsRegistro.column = 1;
                referenciasParticipantes[indReferenciasParticipantes] = tableRegistros.cell(cellsRegistro).data();
                indReferenciasParticipantes++;
                cellsRegistro.column = j;
                emIns = true;
            }
            else if(j == 2 && ($("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() != "Tutoria de pasantias" && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() != "Tutoria de servicio comunitario" && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() != ""))
                emIns = false;
            
            if(emIns && j == 7 && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "")
                valido = false;
            else if(!emIns && j == 7 && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "" && valido)
                valido = true;

            if(j != 7 && $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val() == "")
                valido = false;
                
            if(valido == true)
            {
                switch(j)
                {
                    case 2:
                        registro.tipoDeReferencia = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
                        cellsRegistro = new Object();
                        cellsRegistro.row = i;
                        cellsRegistro.column = 1;
                        cellsRegistro.columnVisible = "0";
                        registro.idRegistro = tableRegistros.cell(cellsRegistro).data();
                    break;
                    case 3:
                        registro.descripcion = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
                    break;
                    case 4:
                        registro.nivel = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
                    break;
                    case 5:
                        registro.estatus = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
                    break;
                    case 6:
                        registro.anio = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
                    break;
                    case 7:
                        registro.empresaInstitucion = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
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
    
    for(var i = 0; i < numFilas; i++)
    {
        cellsRegistro = new Object();
        cellsRegistro.row = i;
        cellsRegistro.column = 3;
        cellsRegistro.columnVisible = "0";
        arrayRegistro[i] = $("#"+tableRegistros.cell(cellsRegistro).data().split('id="')[1].split('"')[0]).val();
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
        referenciasParticipantesAux[indReferenciasParticipantesAux] = $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
        indReferenciasParticipantesAux++;
    }

    for(var i = 0; i < numFilas; i++)
    {
        cellsParticipantes = new Object();
        cellsParticipantes.row = i;
        cellsParticipantes.column = 1;
        cellsParticipantes.columnVisible = "0";
        referenciasParticipantesAux2[indReferenciasParticipantesAux2] = $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
        cellsParticipantes.column = 3;
        referenciasParticipantesAux2[indReferenciasParticipantesAux2] = referenciasParticipantesAux2[indReferenciasParticipantesAux2] + $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
        indReferenciasParticipantesAux2++;
    }
    if((referenciasParticipantesAux.unique().length != referenciasParticipantes.length) || (referenciasParticipantesAux2.length != referenciasParticipantesAux2.unique().length))
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
                if($("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val() == "")
                {
                    referenciasRevistas.splice(0,referenciasRevistas.length);
                    referenciasRevistas = new Array();
                    indReferenciasRevistas=0;
                    valido = false;
                }else
                {
                    switch (j) {
                        case 2:
                            if(!(/^[a-zA-Z]*$/).test($("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val()))
                            {
                                referenciasRevistas.splice(0,referenciasRevistas.length);
                                referenciasRevistas = new Array();
                                indReferenciasRevistas=0;
                                valido = false;
                            }
                            participante.nombre = $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
                            cellsParticipantes = new Object();
                            cellsParticipantes.row = i;
                            cellsParticipantes.column = j-1;
                            cellsParticipantes.columnVisible = "0";
                            participante.idRegistro = $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
                            break;
                        case 3:
                            participante.cedula = $("#"+tableParticipantes.cell(cellsParticipantes).data().split('id="')[1].split('"')[0]).val();
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
        referenciasRevistasAux[indReferenciasRevistasAux] = $("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val();
        indReferenciasRevistasAux++;
    }

    for(var i = 0; i < numFilas; i++)
    {
        cellsRevistas = new Object();
        cellsRevistas.row = i;
        cellsRevistas.column = 2;
        cellsRevistas.columnVisible = "0";
        referenciasRevistasAux2[indReferenciasRevistasAux2] = $("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val();
        indReferenciasRevistasAux2++;
    }
    if((referenciasRevistasAux.unique().length != referenciasRevistas.length) || (referenciasRevistasAux2.length != referenciasRevistasAux2.unique().length))
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
                if($("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val() == "")
                {
                    valido = false;
                }
                else
                {
                    switch (j) {
                        case 2:
                            revista.revista = $("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val();
                            cellsRevistas = new Object();
                            cellsRevistas.row = i;
                            cellsRevistas.column = j-1;
                            cellsRevistas.columnVisible = "0";
                            revista.idRegistro = $("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val();
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
        for(var i = 0; i < numFilas; i++)
        {
            cellsHijos = new Object();
            cellsHijos.row = i;
            cellsHijos.column = 3;
            cellsHijos.columnVisible = "0";
            cedulasHijos[indCedulasHijos] = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
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
                    if(j != 3 && $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val() == "")
                        valido = false;
                        
                    if(valido)
                    {
                        switch (j) {
                            case 1:
                                    hijo.ciMadre = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 2:
                                    hijo.ciPadre = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 3:
                                    hijo.ciHijo = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 4:
                                    if(!(/^[a-zA-Z]*$/).test($("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val()))
                                    {
                                        valido = false;
                                    }
                                    hijo.primerNombre = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 5:
                                    if(!(/^[a-zA-Z]*$/).test($("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val()))
                                    {
                                        valido = false;
                                    }
                                    hijo.segundoNombre = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 6:
                                    if(!(/^[a-zA-Z]*$/).test($("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val()))
                                    {
                                        valido = false;
                                    }
                                    hijo.primerApellido = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 7:
                                    if(!(/^[a-zA-Z]*$/).test($("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val()))
                                    {
                                        valido = false;
                                    }
                                    hijo.segundoApellido = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 8:
                                    hijo.fechaNacimiento = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 9:
                                    hijo.fechaVencimiento = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            case 10:
                                    hijo.nacionalidad = $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val();
                                break;
                            
                        }
                    }
                }
                hijoData[countHijo] = hijo;
                countHijo++;
            }
        }
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
