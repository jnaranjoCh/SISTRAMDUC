var refreshIntervalId;
var tableRegistros;
var tableParticipantes;
var tableRevista;
var countFilesPersonal;
var countFilesHijos;
var input2bool = false;
var input3bool = false;

$('#mail').on('input',function(e){
    $("#formRegistros").addClass("hidden");
    $("#formPersonal").addClass("hidden");
    $("#formCargos").addClass("hidden");
    $("#formHijos").addClass("hidden");
    $("#divCheckbox").addClass("hidden");
});

$('#generate').click(function(){
    toastr.clear();
    
    $.ajax({
        method: "POST",
        data: {"Email":$('#mail').val()},
        url:   routeRegistroUnico['registro_consultarbuscaremail_ajax'],
        dataType: 'json',
        success: function(data){
            if(data){
               $("#load").val("true");
            }else{
               $("#load").val("false");
               toastr.error("El usuario no se encuentra registrado, esta inactivo o no a realizado el registro de datos.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
            }
        }
    });
    refreshIntervalId = setInterval(initTableConsultar, 2500);
    refreshIntervalIdTwo = setInterval(initDatePicker, 2500);
    refreshIntervalIdFour = setInterval(initDatePickerHijo, 2500);
});

$('#checkboxHijos').click(function(){
    if( $(this).prop('checked') ) {
        $("#formHijos").removeClass("hidden");
    }else
        $("#formHijos").addClass("hidden");
});

function initDatePicker(){
    if(tableCargo.page.info().recordsTotal>0)
    {
        for(var i = 0; i < tableCargo.page.info().recordsTotal; i++)
        {
            aux = $('#datepicker'+i).val();
            $('#datepicker'+i).datepicker({
              autoclose: true
            });
            $('#datepicker'+i).val(aux);
        }
        clearInterval(refreshIntervalIdTwo);
    }
}

function initDatePickerHijo(){
    if(tableHijos.page.info().recordsTotal>0)
    {
        for(var i = 0; i < tableHijos.page.info().recordsTotal; i++)
        {
            aux = $('#datepickerHijo1'+i).val();
            $('#datepickerHijo1'+i).datepicker({
              autoclose: true
            });
            $('#datepickerHijo1'+i).val(aux);
            
            aux = $('#datepickerHijo2'+i).val();
            $('#datepickerHijo2'+i).datepicker({
              autoclose: true
            });
            $('#datepickerHijo2'+i).val(aux);
        }
        clearInterval(refreshIntervalIdFour);
    }
}

function initTableConsultar(){
    if($("#load").val() == "true")
    {
        $.ajax({
            method: "POST",
            data: {"email":$('#mail').val()},
            url:   routeRegistroUnico['registro_consultardocumentshijos_ajax'],
            dataType: 'json',
            success: function(data){
                if(data.length > 0)
                {
                    var paths = [];
                    var config = [];
                    var j = 0;
                    for(var i = data.length-1; i >= 0 ; i--)
                    {
                        var hijo = new Object();
                        hijo.caption = "Acta de nacimiento<br/>"+data[i]['path'].split("acta_nacimiento_")[1].split(".pdf")[0];
                        hijo.width = "120px";
                        hijo.key = j+1;
                        hijo.showDelete = false;
                        config[i] = hijo;
                        if($("#url").val().split('/')[1] == "assets")
                            paths[i] = data[i]['path'].split("../web")[1];
                        else
                            paths[i] = data[i]['path'].split("..")[1];
                    }
                    $("#ActaNacCargaHijoDatos").fileinput('destroy');
                    $("#ActaNacCargaHijoDatos").fileinput({
                        language: "es",
                        overwriteInitial: true,
                        filesCount: paths.length,
                        uploadUrl: routeRegistroUnico['registro_guardararchivosconsulta_ajax'].split('/%20/%20')[0]+"/"+$('#mail').val()+"/"+true, // server upload action
                        initialPreview: paths,
                        uploadAsync: false,
                        initialPreviewAsData: true,
                        initialPreviewFileType: 'pdf',
                        initialPreviewConfig: config
                    });
                    countFilesHijos = paths.length;
                    $("#formHijos").removeClass("hidden");
                    $('#checkboxHijos').prop('checked', true);
                }else
                {
                    $("#ActaNacCargaHijoDatos").fileinput('destroy');
                    $("#ActaNacCargaHijoDatos").fileinput({
                        language: "es",
                        overwriteInitial: true,
                        uploadAsync: false,
                        uploadUrl: routeRegistroUnico['registro_guardararchivosconsulta_ajax'].split('/%20/%20')[0]+"/"+$('#mail').val()+"/"+true // server upload action
                    });
                    $('#checkboxHijos').prop('checked', false);
                    $("#formHijos").addClass("hidden");
                }
            }
        });
        
        tableHijos = $('#tableHijos').DataTable({
                    "ajax":{
                       "url": routeRegistroUnico['registro_consultarhijos_ajax'],
                       "type": 'POST',
                       "data": {"email":$('#mail').val(), "assets":$("#url").val().split('/')[1]}
                    },
                    "pagingType": "full_numbers",
                    "bDestroy": true,
            	    "language": {
                        	"url": tableLenguage['datatable-spanish']
                    },
                    columns: [
                        {"data":"Delete"},
                        {"data":"CIMadre"},
                        {"data":"CIPadre"},
                        {"data":"CIHijo"},
                        {"data":"1erNombre"},
                        {"data":"2doNombre"},
                        {"data":"1erApellido"},
                        {"data":"2doApellido"},
                        {"data":"FNacimiento"},
                        {"data":"FVencimientoActa"},
                        {"data":"Nacionalidad"}
                    ]
                });
                
        $.ajax({
            method: "POST",
            data: {"email":$('#mail').val()},
            url:  routeRegistroUnico['registro_consultardatospersonales_ajax'],
            dataType: 'json',
            success: function(data){
                var paths = [];
                $("#PrimerNombreDatos").val(data.PrimerNombre);
                $("#SegundoNombreDatos").val(data.SegundoNombre);
                $("#PrimerApellidoDatos").val(data.PrimerApellido);
                $("#SegundoApellidoDatos").val(data.SegundoApellido);
                $("#NacionalidadDatos").val(data.Nacionalidad);
                $("#FechaNacimientoDatos").val(DateFormat(data.FechaNacimiento.date));
                $("#EdadDatos").val(data.Edad);
                $("#SexoDatos").val(data.Sexo);
                $("#RifDatos").val(data.Rif.split("J-")[1]);
                $("#NumeroDatos").val(data.Telefono.split("-")[0]);
                $("#NumeroDatosII").val(data.Telefono.split("-")[1]);
                $("#DireccionDatos").val(data.Direccion);
                $("#FechaVencimientoCedulaDatos").val(DateFormat(data.Files[2].fecha_vencimiento.date));
                $("#FechaVencimientoActaNacimientoDatos").val(DateFormat(data.Files[0].fecha_vencimiento.date));
                $("#FechaVencimientoRifDatos").val(DateFormat(data.Files[1].fecha_vencimiento.date));
                data.Files = burbuja(data.Files);
                if($("#url").val().split('/')[1] == "assets")
                {
                    paths[0] = data.Files[2].path.split("../web")[1];
                    paths[1] = data.Files[0].path.split("../web")[1];    
                    paths[2] = data.Files[1].path.split("../web")[1];    
                }
                else
                {
                    paths[0] = data.Files[2].path.split("..")[1];
                    paths[1] = data.Files[0].path.split("..")[1];
                    paths[2] = data.Files[1].path.split("..")[1];
                }
                $("#CedulaRifActaCargaDatos").fileinput({
                    language: "es",
                    minFileCount: 1,
                    maxFileCount: 3,
                    overwriteInitial: true,
                    uploadUrl: routeRegistroUnico['registro_guardararchivosconsulta_ajax'].split('/%20/%20')[0]+"/"+$('#mail').val()+"/"+true, // server upload action
                    initialPreview: paths,
                    uploadAsync: false,
                    initialPreviewAsData: true,
                    initialPreviewFileType: 'pdf',
                    initialPreviewConfig: [
                        {caption: "Cedula<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 1, showDelete: false},
                        {caption: "Acta nacimiento<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 2, showDelete: false},
                        {caption: "Rif<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 3, showDelete: false}
                    ]
                });
                countFilesPersonal = paths.length;
            }
        });
        
        tableCargo = $('#tableCargo').DataTable({
                                "ajax":{
                                   "url": routeRegistroUnico['registro_consultarcargos_ajax'],
                                   "type": 'POST',
                                   "data": {"email":$('#mail').val(), "assets":$("#url").val().split('/')[1]}
                                },
                                "pagingType": "full_numbers",
                                "bDestroy": true,
                        	    "language": {
                                    	"url": tableLenguage['datatable-spanish']
                                },
                                columns: [
                                    { "data": "Delete"},
                                    { "data": "Cargo" },
                                    { "data": "FechaDeInicioEnElCargo" }
                                ]
                            });
        
        tableRegistros = $('#tableRegistros').DataTable({
                    	    "ajax":{
                               "url": routeRegistroUnico['registro_consultarregistros_ajax'],
                               "type": 'POST',
                               "data": {"email":$('#mail').val(), "assets":$("#url").val().split('/')[1]}
                            },
                            "pagingType": "full_numbers",
                            "bDestroy": true,
                    	    "language": {
                                	"url": tableLenguage['datatable-spanish']
                            },
                            columns: [
                               {"data": "Delete"},
                               {"data": "Id"},
                               {"data":"TipoDeReferencia"},
                               {"data":"Descripcion"},
                               {"data":"Nivel"},
                               {"data":"Estatus"},
                               {"data":"AnoDePublicacionAsistencia"},
                               {"data":"EmpresaInstitucion"}
                            ]
                        });
        
        tableParticipantes = $('#tableParticipantes').DataTable({
                        	    "ajax":{
                                   "url": routeRegistroUnico['registro_consultarparticipantes_ajax'],
                                   "type": 'POST',
                                   "data": {"email":$('#mail').val(), "assets":$("#url").val().split('/')[1]}
                                },
                                "pagingType": "full_numbers",
                                "bDestroy": true,
                        	    "language": {
                                    	"url": tableLenguage['datatable-spanish']
                                },
                                columns: [
                                    {"data": "Delete"},
                                    { "data": "IdDelRegistro"},
                                    { "data": "Nombre"},
                                    { "data": "Cedula"}
                                ]
                            });
                          
        tableRevista = $('#tableRevista').DataTable({
                        	    "ajax":{
                                   "url": routeRegistroUnico['registro_consultarrevistas_ajax'],
                                   "type": 'POST',
                                   "data": {"email":$('#mail').val(), "assets":$("#url").val().split('/')[1]}
                                },
                                "pagingType": "full_numbers",
                                "bDestroy": true,
                        	    "language": {
                                    	"url": tableLenguage['datatable-spanish']
                                },
                                columns: [
                                    {"data": "Delete"},
                                    { "data": "IdDelRegistro" },
                                    { "data": "Revista" }
                                ]
                            });
        
        $("#load").val("false");
        $("#divCheckbox").removeClass("hidden");
        $("#formRegistros").removeClass("hidden");
        $("#formPersonal").removeClass("hidden");
        $("#formCargos").removeClass("hidden");
        clearInterval(refreshIntervalId);
    }
}

function DateFormat(date)
{
    hrs = date.replace("-","/").replace("-","/").replace(":00.000000","").split(" ")[1];
    ddmmyy = date.replace("-","/").replace("-","/").replace(":00.000000","").split(" ")[0].split("/");
    return ddmmyy[2]+"/"+ddmmyy[1]+"/"+ddmmyy[0]+" "+hrs;
}

function burbuja(array)
{
    for(var i=1;i<array.length;i++)
    {
        for(var j=0;j<array.length-i;j++)
        {
            if(array[j]>array[j+1])
            {
                k=array[j+1];
                array[j+1]=array[j];
                array[j]=k;
            }
        }
    }
 
    return array;
}