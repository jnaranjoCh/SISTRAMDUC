$('#tableRegistros tbody').on( 'click', 'td', function () {
    var row = tableRegistros.cell( this ).index().row;
    var cellRegAux =  new Object();
    cellRegAux.row = row;
    cellRegAux.column = "1";
    cellRegAux.columnVisible = "0";
        
    var iid = tableRegistros.cell(cellRegAux).data();
    
    if(tableRegistros.cell( this ).index().column == 0)
    {
        if(tableRegistros.page.info().recordsTotal > 1)
        {
            updateReferencesDelete(tableParticipantes,iid);
            updateReferencesDelete(tableRevista,iid);
            tableRegistros.row(tableRegistros.cell( this ).index().row).remove().draw();
        }
        else
        {
            var cell = new Object();
            cell.row = "0"; cell.column = "2"; cell.columnVisible = "0";
            var tipo = tableRegistros.cell(cell).data().replace("Tipo"+tableRegistros.cell(cell).data().split("Tipo")[1][0], "Tipo0").replace("selected='selected'", "");
            cell.row = "0"; cell.column = "4"; cell.columnVisible = "0";
            var nivel = tableRegistros.cell(cell).data().replace("Nivel"+tableRegistros.cell(cell).data().split("Nivel")[1][0], "Nivel0").replace("selected='selected'", "");
            cell.row = "0"; cell.column = "5"; cell.columnVisible = "0";
            var estatus = tableRegistros.cell(cell).data().replace("Estatus"+tableRegistros.cell(cell).data().split("Estatus")[1][0], "Estatus0").replace("selected='selected'", "");
            updateReferencesDelete(tableParticipantes,iid);
            updateReferencesDelete(tableRevista,iid);
            tableRegistros.row(tableRegistros.cell( this ).index().row).remove().draw();
            tableRegistros.row.add( {
                "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
                "Id":0,
                "TipoDeReferencia":tipo,
                "Descripcion":'<input id="Descripcion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Descripción">',
                "Nivel":nivel,
                "Estatus":estatus,
                "AnoDePublicacionAsistencia": '<input id="AnoDePublicacionAsistencia'+tableRegistros.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Año de publicación y/o asistencia">',
                "EmpresaInstitucion": '<input id="EmpresaInstitucion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Empresa y/o institución" readonly>'
            }).draw();
            $('#tableRegistros_last').click();
        }
    }
    else
    {
        var object = tableRegistros.cell( this ).data();
        var column = tableRegistros.cell( this ).index().column;
        var cell = new Object();
        var id = object.split('id="')[1];
        
        id = id.split('"')[0];
        cell.row = row;
        cell.column = "7";
        cell.columnVisible = "0";

        
        $("#"+id).change(function(e){
            if(column == 2 && ($("#"+id).val() == "Tutoria de pasantias" || $("#"+id).val() == "Tutoria de servicio comunitario"))
            {
                updateReferencesAdd(tableParticipantes,iid);
                updateReferencesDelete(tableRevista,iid);
                tableRegistros.cell(cell).data('<input id="EmpresaInstitucion'+row+'" value="" type="text" class="form-control" placeholder="Empresa y/o institución">').draw();
            }
            else if(column == 2 && $("#"+id).val() == "Articulo publicado")
            {
                updateReferencesAdd(tableRevista,iid);
                updateReferencesDelete(tableParticipantes,iid);
            }
            else if(column == 2  && ($("#"+id).val() == "Estudio" || $("#"+id).val() == "Asistencia a congresos"))
            {
                updateReferencesDelete(tableParticipantes,iid);
                updateReferencesDelete(tableRevista,iid);
                tableRegistros.cell(cell).data('<input id="EmpresaInstitucion'+row+'" value="" type="text" class="form-control" placeholder="Empresa y/o institución" readonly>').draw();
            }
        });
    }
});

function updateReferencesAdd(table,iid)
{
    if(table.page.info().recordsTotal > 0)
    {
        var cellPar = new Object();
        for(var i = 0; i < table.page.info().recordsTotal; i++)
        {
            cellPar.row = i;
            cellPar.column = "1";
            cellPar.columnVisible = "0";
            if(table.cell(cellPar).data().indexOf(iid+"</option>") == -1)
            {
                table.cell(cellPar).data(table.cell(cellPar).data().replace("</select>","<option value='"+iid+"'>"+iid+"</option></select>"));
            }
        }
    }
    else
    {
        if(table.table().node().id == "tableParticipantes")
            table.row.add( {
                "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
                "IdDelRegistro":'<select id="IdDelRegistro0" class="form-control select2" style="width: 240px;"><option value="'+iid+'">'+iid+'</option></select>',
                "Nombre":'<input id="Nombre'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Nombre">',
                "Cedula": '<input id="Cedula'+table.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula">',
            }).draw();            
        else if(table.table().node().id == "tableRevista")
            tableRevista.row.add( {
                "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
                "IdDelRegistro":'<select id="IdDelRegistro0" class="form-control select2" style="width: 240px;"><option value="'+iid+'">'+iid+'</option></select>',
                "Revista":'<input id="Revista'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Revista">'
            }).draw();
    }
}

function updateReferencesDelete(table,iid)
{
    if(table.page.info().recordsTotal > 0)
    {
        var cellPar = new Object();
        for(var i = 0; i < table.page.info().recordsTotal; i++)
        {
            cellPar.row = i;
            cellPar.column = "1";
            cellPar.columnVisible = "0";
            if(table.cell(cellPar).data().indexOf(iid+"</option>") != -1)
            {
                if((table.cell(cellPar).data().split("</option>").length-1) > 1)
                {
                    table.cell(cellPar).data(table.cell(cellPar).data().replace("<option value='"+iid+"'>"+iid+"</option>",""));
                    table.cell(cellPar).data(table.cell(cellPar).data().replace("<option value='"+iid+"'  selected='selected'>"+iid+"</option>",""));
                }else
                    table.clear().draw();
            }
        }
    }
}

$('#restablecer').click(function(){
    $("#ActaNacCargaHijoDatos").fileinput('refresh');
    $.ajax({
        method: "POST",
        data: {"email":$('#gemail').val()},
        url:   routeRegistroUnico['registro_consultardocumentshijos_ajax'],
        dataType: 'json',
        success: function(data){
            if(data != null)
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
                    paths[i] = data[i]['path'].split("..")[1];
                }
                $("#ActaNacCargaHijoDatos").fileinput({
                    //language: "es",
                    overwriteInitial: true,
                    initialPreview: paths,
                    initialPreviewAsData: true,
                    initialPreviewFileType: 'pdf',
                    initialPreviewConfig: config
                });
                $("#formHijos").removeClass("hidden");
                $('#checkboxHijos').prop('checked', true);
            }else
                $('#checkboxHijos').prop('checked', false);
        }
    });
    
    tableHijos = $('#tableHijos').DataTable({
                "ajax":{
                    "url": routeRegistroUnico['registro_consultarhijos_ajax'],
                    "type": 'POST',
                    "data": {"email":$('#gemail').val(), "assets":$("#url").val().split('/')[1]}
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
    refreshIntervalIdFour = setInterval(initDatePickerHijo, 2500);
    
    $("#CedulaRifActaCargaDatos").fileinput('refresh');
    $.ajax({
        method: "POST",
        data: {"email":$('#gemail').val()},
        url:   routeRegistroUnico['registro_consultardatospersonales_ajax'],
        dataType: 'json',
        success: function(data){
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
            
            $("#CedulaRifActaCargaDatos").fileinput({
                //language: "es",
                minFileCount: 1,
                maxFileCount: 3,
                overwriteInitial: true,
                initialPreview: [
                    data.Files[2].path.split("..")[1],
                    data.Files[0].path.split("..")[1],
                    data.Files[1].path.split("..")[1]
                ],
                initialPreviewAsData: true,
                initialPreviewFileType: 'pdf',
                initialPreviewConfig: [
                    {caption: "Cedula<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 1},
                    {caption: "Acta nacimiento<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 2},
                    {caption: "Rif<br/>"+data.PrimerNombre+" "+data.PrimerApellido, width: "120px", key: 2}
                ]
            });
        }
    });
    
    $('#tableCargo').dataTable().fnDestroy();
    tableCargo = $('#tableCargo').DataTable({
                    "ajax":{
                        "url": routeRegistroUnico['registro_consultarcargos_ajax'],
                        "type": 'POST',
                        "data": {"email":$('#gemail').val(), "assets":$("#url").val().split('/')[1]}
                    },
                    "pagingType": "full_numbers",
                    "bDestroy": true,
                    "language": {
                            "url": tableLenguage['datatable-spanish']
                    },
                    columns: [
                        {"data": "Delete"},
                        { "data": "Cargo" },
                        { "data": "FechaDeInicioEnElCargo" }
                    ]
                });
    refreshIntervalIdTwo = setInterval(initDatePicker, 2500);
    
    $('#tableRegistros').dataTable().fnDestroy();
    tableRegistros = $('#tableRegistros').DataTable({
                            "ajax":{
                                "url": routeRegistroUnico['registro_consultarregistros_ajax'],
                                "type": 'POST',
                                "data": {"email":$('#gemail').val(), "assets":$("#url").val().split('/')[1]}
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
                        
    $('#tableParticipantes').dataTable().fnDestroy();  
    tableParticipantes = $('#tableParticipantes').DataTable({
                            "ajax":{
                                "url": routeRegistroUnico['registro_consultarparticipantes_ajax'],
                                "type": 'POST',
                                "data": {"email":$('#gemail').val(), "assets":$("#url").val().split('/')[1]}
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
                        
    $('#tableRevista').dataTable().fnDestroy();
    tableRevista = $('#tableRevista').DataTable({
                            "ajax":{
                                "url": routeRegistroUnico['registro_consultarrevistas_ajax'],
                                "type": 'POST',
                                "data": {"email":$('#gemail').val(), "assets":$("#url").val().split('/')[1]}
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
});

$('#agregarRegistros').click(function(){
    var cell = new Object();
    cell.row = "0"; cell.column = "2"; cell.columnVisible = "0";
    var tipo = tableRegistros.cell(cell).data().replace("Tipo0", "Tipo"+tableRegistros.page.info().recordsTotal).replace("selected='selected'", "");
    cell.row = "0"; cell.column = "4"; cell.columnVisible = "0";
    var nivel = tableRegistros.cell(cell).data().replace("Nivel0", "Nivel"+tableRegistros.page.info().recordsTotal).replace("selected='selected'", "");
    cell.row = "0"; cell.column = "5"; cell.columnVisible = "0";
    var estatus = tableRegistros.cell(cell).data().replace("Estatus0", "Estatus"+tableRegistros.page.info().recordsTotal).replace("selected='selected'", "");
    cell.row = tableRegistros.page.info().recordsTotal-1; cell.column = "1"; cell.columnVisible = "0";
    var id = tableRegistros.cell(cell).data();
    
    tableRegistros.row.add( {
        "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
        "Id":id+1,
        "TipoDeReferencia":tipo,
        "Descripcion":'<input id="Descripcion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Descripción">',
        "Nivel":nivel,
        "Estatus":estatus,
        "AnoDePublicacionAsistencia": '<input id="AnoDePublicacionAsistencia'+tableRegistros.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Año de publicación y/o asistencia">',
        "EmpresaInstitucion": '<input id="EmpresaInstitucion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Empresa y/o institución" readonly>'
    }).draw();
    $('#tableRegistros_last').click();
});


$('#agregarParticipantes').click(function(){
    var cell = new Object();
    cell.row = "0"; cell.column = "1"; cell.columnVisible = "0";
    var idDelRegistro = tableParticipantes.cell(cell).data();
    idDelRegistro = idDelRegistro.replace(idDelRegistro.split('id="')[1].split('"')[0],"IdDelRegistro"+tableParticipantes.page.info().recordsTotal);  
    tableParticipantes.row.add( {
        "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
        "IdDelRegistro":idDelRegistro,
        "Nombre":'<input id="Nombre'+tableParticipantes.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Nombre">',
        "Cedula": '<input id="Cedula'+tableParticipantes.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula">',
    }).draw();
    $('#tableParticipantes_last').click();
});


$('#agregarRevistas').click(function(){
    var cell = new Object();
    cell.row = "0"; cell.column = "1"; cell.columnVisible = "0";
    var idDelRegistro = tableRevista.cell(cell).data();
    idDelRegistro = idDelRegistro.replace(idDelRegistro.split('id="')[1].split('"')[0],"IdDelRegistroRevista"+tableRevista.page.info().recordsTotal);
    tableRevista.row.add( {
        "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
        "IdDelRegistro":idDelRegistro,
        "Revista":'<input id="Revista'+tableRevista.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Revista">'
    }).draw();
    $('#tableRevista_last').click();
});

$('#agregarHijos').click(function(){
    var aux = tableHijos.page.info().recordsTotal;
    tableHijos.row.add( {
        "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
        "CIMadre" :'<input id="CIMadre'+tableHijos.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula Madre">',
        "CIPadre" :'<input id="CIPadre'+tableHijos.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula Padre">',
        "CIHijo" :'<input id="CIHijo'+tableHijos.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula Hijo">',
        "1erNombre" :'<input id="1erNombre'+tableHijos.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Primer Nombre">',
        "2doNombre" :'<input id="2doNombre'+tableHijos.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Segundo Nombre">',
        "1erApellido" :'<input id="1erApellido'+tableHijos.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Primer Apellido">',
        "2doApellido" :'<input id="2doApellido'+tableHijos.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Segundo Apellido">',
        "FNacimiento" :'<div class="row">'+
                                '<div class="col-xs-12">'+
                                    '<div class="form-group has-feedback">'+
                                        '<div class="input-group date">'+
                                            '<input id="datepickerHijo1'+tableHijos.page.info().recordsTotal+'" value="" name="FNacimiento'+tableHijos.page.info().recordsTotal+'" type="text" class="form-control" style="width: 240px;"/>'+
                                            '<span class="input-group-addon">'+
                                                '<span class="glyphicon glyphicon-calendar"></span>'+
                                            '</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '</div>'+
                                '</div>',
        "FVencimientoActa" :'<div class="row">'+
                                '<div class="col-xs-12">'+
                                    '<div class="form-group has-feedback">'+
                                        '<div class="input-group date">'+
                                            '<input id="datepickerHijo2'+tableHijos.page.info().recordsTotal+'" value="" name="FVencimientoActa'+tableHijos.page.info().recordsTotal+'" type="text" class="form-control" style="width: 200px;"/>'+
                                            '<span class="input-group-addon">'+
                                                '<span class="glyphicon glyphicon-calendar"></span>'+
                                            '</span>'+
                                        '</div>'+
                                    '</div>'+
                                    '</div>'+
                                '</div>',
        "Nacionalidad" :'<input id="Nacionalidad'+tableHijos.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Nacionalidad">'
    }).draw();
    $('#tableHijos_last').click();
    $('#datepickerHijo1'+aux).datepicker({
        autoclose: true
    });
    $('#datepickerHijo2'+aux).datepicker({
        autoclose: true
    });
});

$('#agregarCargo').click(function(){
    toastr.clear();
    var cell = new Object();
    cell.row = "0"; cell.column = "1"; cell.columnVisible = "0";
    var cargos = tableCargo.cell(cell).data().replace("Cargos0", "Cargos"+tableRegistros.page.info().recordsTotal).replace("selected='selected'", "");
    var aux = tableCargo.page.info().recordsTotal;
    if(aux < cargos.split("</option>").length-2)
    {
        tableCargo.row.add( {
            "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
            "Cargo":cargos,
            "FechaDeInicioEnElCargo":'<div class="row">'+
                                        '<div class="col-xs-12">'+
                                            '<div class="form-group has-feedback">'+
                                                '<div class="input-group date">'+
                                                    '<input id="datepicker'+tableCargo.page.info().recordsTotal+'" name="FechaInicioCargoDatos'+tableCargo.page.info().recordsTotal+'" type="text" class="form-control"/>'+
                                                    '<span class="input-group-addon">'+
                                                        '<span class="glyphicon glyphicon-calendar"></span>'+
                                                    '</span>'+
                                                '</div>'+
                                            '</div>'+
                                            '</div>'+
                                        '</div>'
        }).draw();
        $('#tableCargo_last').click();
        $('#datepicker'+aux).datepicker({
            autoclose: true
        });
    }else
        toastr.error("Error no se pueden agregar mas cargos!", "Error", {
            "timeOut": "0",
            "extendedTImeout": "0"
            });
});

$('#tableRevista').on( 'click', 'td', function () {
    if(tableRevista.cell( this ).index().column == 0)
        tableRevista.row(tableRevista.cell( this ).index().row).remove().draw();
});

$('#tableParticipantes').on( 'click', 'td', function () {
    if(tableParticipantes.cell( this ).index().column == 0)
        tableParticipantes.row(tableParticipantes.cell( this ).index().row).remove().draw();
});

$('#tableHijos').on( 'click', 'td', function () {
    if(tableHijos.cell( this ).index().column == 0)
        tableHijos.row(tableHijos.cell( this ).index().row).remove().draw();
});

$('#tableCargo').on( 'click', 'td', function () {
    if(tableCargo.cell( this ).index().column == 0)
    {
        if(tableCargo.page.info().recordsTotal > 1)
            tableCargo.row(tableCargo.cell( this ).index().row).remove().draw();
        else
        { 
            var cell = new Object();
            cell.row = "0"; cell.column = "1"; cell.columnVisible = "0";
            var cargos = tableCargo.cell(cell).data().replace("Cargos"+tableCargo.cell(cell).data().split("Cargos")[1][0], "Cargos0").replace("selected='selected'", "");
            tableCargo.row(tableCargo.cell( this ).index().row).remove().draw();
            tableCargo.row.add( {
                "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
                "Cargo":cargos,
                "FechaDeInicioEnElCargo":'<div class="row">'+
                                            '<div class="col-xs-12">'+
                                                '<div class="form-group has-feedback">'+
                                                    '<div class="input-group date">'+
                                                        '<input id="datepicker0" name="FechaInicioCargoDatos0" type="text" class="form-control"/>'+
                                                        '<span class="input-group-addon">'+
                                                            '<span class="glyphicon glyphicon-calendar"></span>'+
                                                        '</span>'+
                                                    '</div>'+
                                                '</div>'+
                                                '</div>'+
                                            '</div>'
            }).draw();
            $('#tableCargo_last').click();
            $('#datepicker0').datepicker({
                autoclose: true
            });
        }
    }
});
