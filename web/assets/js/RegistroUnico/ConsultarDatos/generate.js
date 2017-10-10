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
    var tableRelationship;
    $.ajax({
            method: "POST",
            data: {"Email":$('#mail').val()},
            url: routeRegistroUnico['registro_buscaremail_ajax'],
            dataType: 'json',
            success: function(data){
                $("#ciAux").val(data["ci"]);        
            }
    });
    
    toastr.clear();
    $.ajax({
        method: "POST",
        data: {"Email":$('#mail').val()},
        url:   routeRegistroUnico['registro_consultarbuscaremail_ajax'],
        dataType: 'json',
        success: function(data){
            if(data){
              $.ajax({
                    method: "POST",
                    data: {"Email":$('#mail').val()},
                    url: routeRegistroUnico['registro_consultar_parentesco_ajax'],
                    dataType: 'json',
                    success: function(data){
                        if($.trim(data.length) && data.length>0)
                        {
                            $('#otherChildrens').removeClass("hidden");
                            $('#relationship').removeClass("hidden");
                            $('#relationship').html('');
                            tableRelationship = "";
                            for(var i = 0; i < data.length; i++)
                            {
                                $(tableRelationshipList[i]).dataTable().fnDestroy();
                                tableRelationship = tableRelationship + '<h4 id="infoOtherParent'+i+'"></h4><div style="overflow-x: scroll; white-space: nowrap;">';
                                tableRelationship = tableRelationship + '<table id="tableRelationship'+i+'" class="table table-bordered table-striped">'+
                                                                            '<thead>'+
                                                                            '<tr>'+
                                                                              '<th>CI Madre</th>'+
                                                                              '<th>CI Padre</th>'+
                                                                              '<th>CI Hijo</th>'+
                                                                              '<th>1er Nombre</th>'+
                                                                              '<th>2do Nombre</th>'+
                                                                              '<th>1er Apellido</th>'+
                                                                              '<th>2do Apellido</th>'+
                                                                              '<th>F Nacimiento</th>'+
                                                                              '<th>Nacionalidad</th>'+          
                                                                            '</tr>'+
                                                                            '</thead>'+
                                                                            '<tbody>'+
                                                                            '</tbody>'+
                                                                            '<tfoot>'+
                                                                            '<tr>'+
                                                                              '<th>CI Madre</th>'+
                                                                              '<th>CI Padre</th>'+
                                                                              '<th>CI Hijo</th>'+
                                                                              '<th>1er Nombre</th>'+
                                                                              '<th>2do Nombre</th>'+
                                                                              '<th>1er Apellido</th>'+
                                                                              '<th>2do Apellido</th>'+
                                                                              '<th>F Nacimiento</th>'+
                                                                              '<th>Nacionalidad</th>'+
                                                                            '</tr>'+
                                                                            '</tfoot>'+
                                                                          '</table>';
                                tableRelationship = tableRelationship+'</div>';
                                tableRelationshipList[i] = "#tableRelationship"+i;
                            }
                            $('#relationship').html(tableRelationship);
                            for(var i = 0; i < data.length; i++)
                            {
                                if(data[i].primerNombre == "" && data[i].segundoNombre == "" && data[i].primerApellido == "" && data[i].segundoApellido == "")
                                    $("#infoOtherParent"+i).html("<strong>Usuario en espera por registrar ( Cedula: "+data[i].cedula+")</strong>");
                                else
                                    $("#infoOtherParent"+i).html("<strong>Hijos de:</strong> "+data[i].primerNombre+" "+data[i].segundoNombre+" "+data[i].primerApellido+" "+data[i].segundoApellido);
                                $(tableRelationshipList[i]).DataTable({
                                            "ajax":{
                                               "url": routeRegistroUnico['registro_consultar_parentesco_hijos_ajax'],
                                               "type": 'POST',
                                               "data": {"cedula":data[i].cedula,"Email":$('#mail').val()}
                                            },
                                            "pagingType": "full_numbers",
                                    	    "language": {
                                                	"url": tableLenguage['datatable-spanish']
                                            },
                                            columns: [
                                                {"data":"CIMadre"},
                                                {"data":"CIPadre"},
                                                {"data":"CIHijo"},
                                                {"data":"1erNombre"},
                                                {"data":"2doNombre"},
                                                {"data":"1erApellido"},
                                                {"data":"2doApellido"},
                                                {"data":"FNacimiento"},
                                                {"data":"Nacionalidad"}
                                            ]
                                });
                            }
                        }else
                        {
                            $('#otherChildrens').addClass("hidden");
                            $('#relationship').addClass("hidden");
                        }
                    }
                });
               $("#load").val("true");
               
            }else{
               $("#load").val("false");
               toastr.error("El usuario no se encuentra registrado, está inactivo o no ha realizado el registro de datos.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
            }
        }
    });
    refreshIntervalId = setInterval(initTableConsultar, 2500);
    refreshIntervalIdTwo = setInterval(initDatePicker, 2500);
    refreshIntervalIdThree = setInterval(initDatePickerYear, 2500);
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
            var cell = new Object();
            cell.row = i; cell.column = "2"; cell.columnVisible = "0";
            aux = tableCargo.cell(cell).nodes().to$().find('#datepicker'+i).val();
            tableCargo.cell(cell).nodes().to$().find('#datepicker'+i).datepicker({
              autoclose: true
            });
            tableCargo.cell(cell).nodes().to$().find('#datepicker'+i).val(aux);
        }
        clearInterval(refreshIntervalIdTwo);
    }
}
function initDatePickerYear(){
    if(tableRegistros.page.info().recordsTotal>0)
    {
        for(var i = 0; i < tableRegistros.page.info().recordsTotal; i++)
        {
            var cell = new Object();
            cell.row = i; cell.column = "6"; cell.columnVisible = "0";
            aux = tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).val();
            tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).datepicker({
                format: " yyyy",
                viewMode: "years", 
                minViewMode: "years"
            });
            tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).val(aux);
            
            tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).keypress(function(){
                tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).val('');
            });
            
            tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).keyup(function(){
                tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).val('');
            });
            tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).keydown(function(){
                tableRegistros.cell(cell).nodes().to$().find('#AnoDePublicacionAsistencia'+i).val('');
            });
        }
        clearInterval(refreshIntervalIdThree);
    }
    
}
function initDatePickerHijo(){
    if(tableHijos.page.info().recordsTotal>0)
    {
        for(var i = 0; i < tableHijos.page.info().recordsTotal; i++)
        {
            var cell = new Object();
            cell.row = i; cell.column = "8"; cell.columnVisible = "0";
            aux = tableHijos.cell(cell).nodes().to$().find('#datepickerHijo1'+i).val();
            tableHijos.cell(cell).nodes().to$().find('#datepickerHijo1'+i).datepicker({
              autoclose: true
            });
            tableHijos.cell(cell).nodes().to$().find('#datepickerHijo1'+i).val(aux);
            
            var cell = new Object();
            cell.row = i; cell.column = "9"; cell.columnVisible = "0";
            aux = tableHijos.cell(cell).nodes().to$().find('#datepickerHijo2'+i).val();
            tableHijos.cell(cell).nodes().to$().find('#datepickerHijo2'+i).datepicker({
              autoclose: true
            });
            tableHijos.cell(cell).nodes().to$().find('#datepickerHijo2'+i).val(aux);
        }

        var cellMadre = new Object();
        var cellPadre = new Object();
        var cellMadreAux = new Object();
        var cellPadreAux = new Object();
        cellMadre.row = 0; cellMadre.column = "1"; cellMadre.columnVisible = "0"; 
        cellPadre.row = 0; cellPadre.column = "2"; cellPadre.columnVisible = "0"; 
        
        if(tableHijos.cell(cellMadre).nodes().to$().find("#checkboxMadre").prop('checked'))
        {
            registerOtherUserPadre = false;
            registerOtherUserMadre = true;    
        }else if(tableHijos.cell(cellPadre).nodes().to$().find("#checkboxPadre").prop('checked'))
        {
            registerOtherUserPadre = true;
            registerOtherUserMadre = false;
        }
        
        if(otherUsersCount == 0)
        {
            cellMadreAux.row = 0; cellMadreAux.column = "1"; cellMadreAux.columnVisible = "0"; 
            cellPadreAux.row = 0; cellPadreAux.column = "2"; cellPadreAux.columnVisible = "0";
            for(var i = 0; i < tableHijos.page.info().recordsTotal; i++)
            {
                cellMadre.row = i; cellMadre.column = "1"; cellMadre.columnVisible = "0"; 
                cellPadre.row = i; cellPadre.column = "2"; cellPadre.columnVisible = "0"; 
                if(tableHijos.cell(cellMadreAux).nodes().to$().find("#checkboxMadre").prop('checked'))
                {   
                    var user = new Object();
                    user.ci = tableHijos.cell(cellMadre).nodes().to$().find("#CIMadre"+i).val();
                    user.register = false;
                    if(!existe(registerOtherUsers,user))
                    {
                        registerOtherUsers[otherUsersCount] = user;
                        otherUsersCount++;
                    }
                }else if(tableHijos.cell(cellPadreAux).nodes().to$().find("#checkboxPadre").prop('checked'))
                {
                    var user = new Object();
                    user.ci = tableHijos.cell(cellPadre).nodes().to$().find("#CIPadre"+i).val();
                    user.register = false;
                    if(!existe(registerOtherUsers,user))
                    {
                        registerOtherUsers[otherUsersCount] = user;
                        otherUsersCount++;
                    }
                }
                
            }
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
                $("#tipoDedicacionDatos").val(data.TipoDedicacion);
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
                               {"data":"Delete"},
                               {"data":"Id"},
                               {"data":"TipoDeReferencia"},
                               {"data":"Descripcion"},
                               {"data":"Nivel"},
                               {"data":"Estatus"},
                               {"data":"AnoDePublicacionAsistencia"},
                               {"data":"EmpresaInstitucion"},
                               {"data":"TituloObtenido"},
                               {"data":"CiudadPais"},
                               {"data":"Congreso"},
                               {"data":"Archivo"}
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
                                    { "data": "Revista" },
                                    { "data": "Volumen" },
                                    { "data": "PrimerayUltimaPagina" }
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

function existe(users, user){
    return users.any(function(t){ return t.ci == user.ci});
}