var tieneArchivosPersonal = false;
var tieneArchivosHijos = false;
var referenciasParticipantes = [];
var indReferenciasParticipantes = 0;
var referenciasRevistas = [];
var indReferenciasRevistas = 0;

$("#guardar").click(function(){
    var can_update = false;
    var text = "";

    toastr.clear();
    if(validarDatosPersonales())
    {
        if(!validarCargos())
            text = "Error campos mal introducido o obligatorio en la sección de cargos.";
        else if(!validarRegistros())
            text = "Error campos mal introducido o obligatorio en la sección de registros.";
        else if(!validarParticipantes())
            text = "Error campos mal introducido, obligatorio, registro no asociados o existen participantes repetidos para un registro en la sección de participantes.";
        else if(!validarRevistas())
            text = "Error campos mal introducido, obligatorio, registro no asociados o existen revistas repetidas para un registro en la sección de revistas.";        
        else if($("#checkboxHijos").prop('checked'))
        {
            if(!validarHijos())
                text = "Error hijos.";        
            else
                can_update = true;
        }
        else
            can_update = true;
    }else
        can_update = true;

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
    for(var i = 0; i < numFilas; i++)
    {
        for(var j = 1; j < numColumn-1; j++)
        {
            cellsCargos = new Object();
            cellsCargos.row = i;
            cellsCargos.column = j;
            cellsCargos.columnVisible = "0";
            if($("#"+tableCargo.cell(cellsCargos).data().split('id="')[1].split('"')[0]).val() == "")
                valido = false;
        }
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
    for(var i = 0; i < numFilas; i++)
    {
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
        }
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
                }
            }
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
            for(var j = 2; j < numColumn-1; j++)
            {
                cellsRevistas = new Object();
                cellsRevistas.row = i;
                cellsRevistas.column = j;
                cellsRevistas.columnVisible = "0";
                if($("#"+tableRevista.cell(cellsRevistas).data().split('id="')[1].split('"')[0]).val() == "")
                    valido = false;
            }
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
                for(var j = 1; j < numColumn-1; j++)
                {
                    cellsHijos = new Object();
                    cellsHijos.row = i;
                    cellsHijos.column = j;
                    cellsHijos.columnVisible = "0";
                    if(j != 3 && $("#"+tableHijos.cell(cellsHijos).data().split('id="')[1].split('"')[0]).val() == "")
                        valido = false;
                }
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
