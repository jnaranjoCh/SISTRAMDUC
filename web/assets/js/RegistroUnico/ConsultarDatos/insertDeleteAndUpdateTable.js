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
                "EmpresaInstitucion": '<input id="EmpresaInstitucion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">',
                "TituloObtenido": '<input id="TituloObtenido'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido">',
                "CiudadPais": '<input id="CiudadPais'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais">',
                "Congreso": '<input id="Congreso'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Congreso">',
                "Archivo": '<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="Archivo'+tableRegistros.page.info().recordsTotal+'" href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
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
            var valor = $("#"+id).val();
            if(column == 2 && (valor == "Tutoria de pasantias" || valor == "Tutoria de servicio comunitario"  ||  valor == "Tutoria de tesis"))
            {
                updateReferencesAdd(tableParticipantes,iid);
                updateReferencesDelete(tableRevista,iid);
                tableRegistros.cell(cell).data('<input id="EmpresaInstitucion'+row+'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">').draw();
                cell.column = "8";
                if(valor == "Tutoria de tesis")
                    tableRegistros.cell(cell).data('<input id="TituloObtenido'+row+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido">').draw();
                else
                    tableRegistros.cell(cell).data('<input id="TituloObtenido'+row+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido" readonly>').draw();
                cell.column = "9";
                tableRegistros.cell(cell).data('<input id="CiudadPais'+row+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais" readonly>').draw();
                cell.column = "10";
                tableRegistros.cell(cell).data('<input id="Congreso'+row+'" value="" type="text" class="form-control" placeholder="Congreso" readonly>').draw();
            }
            else if(column == 2 && valor == "Articulo publicado")
            {
                updateReferencesAdd(tableRevista,iid);
                updateReferencesAdd(tableParticipantes,iid);
                cell.column = "8";
                tableRegistros.cell(cell).data('<input id="TituloObtenido'+row+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido" readonly>').draw();
                cell.column = "9";
                tableRegistros.cell(cell).data('<input id="CiudadPais'+row+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais" readonly>').draw();
                cell.column = "10";
                tableRegistros.cell(cell).data('<input id="Congreso'+row+'" value="" type="text" class="form-control" placeholder="Congreso" readonly>').draw();
            }
            else if(column == 2  && (valor == "Estudio" || valor == "Sociedad Científica y Profesionales" || valor ==  "Becas"  || valor == "Premios" || valor == "Distinciones"))
            {
                updateReferencesDelete(tableParticipantes,iid);
                updateReferencesDelete(tableRevista,iid);
                tableRegistros.cell(cell).data('<input id="EmpresaInstitucion'+row+'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">').draw();
                cell.column = "8";
                tableRegistros.cell(cell).data('<input id="TituloObtenido'+row+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido" readonly>').draw();
                cell.column = "9";
                tableRegistros.cell(cell).data('<input id="CiudadPais'+row+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais" readonly>').draw();
                cell.column = "10";
                tableRegistros.cell(cell).data('<input id="Congreso'+row+'" value="" type="text" class="form-control" placeholder="Congreso" readonly>').draw();
            }if(column == 2  && valor == "Asistencia a Congresos/Seminarios")
            {
                updateReferencesDelete(tableParticipantes,iid);
                updateReferencesDelete(tableRevista,iid);
                tableRegistros.cell(cell).data('<input id="EmpresaInstitucion'+row+'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">').draw();
                cell.column = "8";
                tableRegistros.cell(cell).data('<input id="TituloObtenido'+row+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido" readonly>').draw();
                cell.column = "9";
                tableRegistros.cell(cell).data('<input id="CiudadPais'+row+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais">').draw();
                cell.column = "10";
                tableRegistros.cell(cell).data('<input id="Congreso'+row+'" value="" type="text" class="form-control" placeholder="Congreso">').draw();
            }
            //$('#tableRegistros_last').click();
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
            if(!table.cell(cellPar).data().includes("value='"+iid+"' selected='selected'") && !table.cell(cellPar).data().includes("value='"+iid+"'"))
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
                "IdDelRegistro":"<select id='IdDelRegistro0' class='form-control select2' style='width: 240px;'><option value='"+iid+"' selected='selected'>"+iid+"</option></select>",
                "Nombre":'<input id="Nombre'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Nombre">',
                "Cedula": '<input id="Cedula'+table.page.info().recordsTotal+'" value="" type="number" class="form-control" placeholder="Cedula">',
            }).draw();            
        else if(table.table().node().id == "tableRevista")
        {
            tableRevista.row.add( {
                "Delete":"<img src='"+routeFiles['delete-png']+"' width='30px' heigth='30px'/>",
                "IdDelRegistro":"<select id='IdDelRegistroRevista0' class='form-control select2' style='width: 240px;'><option value='"+iid+"' selected='selected'>"+iid+"</option></select>",
                "Revista":'<input id="Revista'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Revista">',
                "Volumen":'<input id="Volumen'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Volumen">',
                "PrimerayUltimaPagina":'<input id="PrimerayUltimaPagina'+table.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Primera y última página">'
            }).draw();
        }
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
            if(table.cell(cellPar).data().includes("value='"+iid+"'") || table.cell(cellPar).data().includes("value='"+iid+"' selected='selected'"))
            {
                if((table.cell(cellPar).data().split("</option>").length-1) > 1)
                {
                    table.cell(cellPar).data(table.cell(cellPar).data().replace("<option value='"+iid+"'>"+iid+"</option>",""));
                    table.cell(cellPar).data(table.cell(cellPar).data().replace("<option value='"+iid+"' selected='selected'>"+iid+"</option>",""));
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
        data: {"email":$('#mail').val()},
        url:   routeRegistroUnico['registro_consultardocumentshijos_ajax'],
        dataType: 'json',
        success: function(data){
            if($.trim(data.length))
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
                $("#ActaNacCargaHijoDatos").fileinput({
                    //language: "es",
                    overwriteInitial: true,
                    filesCount: paths.length,
                    uploadUrl: routeRegistroUnico['registro_guardararchivosconsulta_ajax'].split('/%20/%20')[0]+"/"+$('#mail').val()+"/"+true, // server upload action
                    initialPreview: paths,
                    uploadAsync: false,
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
    refreshIntervalIdFour = setInterval(initDatePickerHijo, 2500);
    
    $("#CedulaRifActaCargaDatos").fileinput('refresh');
    $.ajax({
        method: "POST",
        data: {"email":$('#mail').val()},
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
                //language: "es",
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
        }
    });
    
    $('#tableCargo').dataTable().fnDestroy();
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
                                { "data":"CiudadPais"},
                                { "data":"Congreso"},
                                {"data":"Archivo"}
                            ]
                        });
                        
    $('#tableParticipantes').dataTable().fnDestroy();  
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
                        
    $('#tableRevista').dataTable().fnDestroy();
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
     refreshIntervalIdThree = setInterval(initDatePickerYear, 2500);
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
        "EmpresaInstitucion": '<input id="EmpresaInstitucion'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Empresa / Institución / Financiamiento y/o Casa editorial">',
        "TituloObtenido": '<input id="TituloObtenido'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Titulo Obtenido">',
        "CiudadPais": '<input id="CiudadPais'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Ciudad / Pais">',
        "Congreso": '<input id="Congreso'+tableRegistros.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Congreso">',
        "Archivo": '<div class="col-offset-xs-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="Archivo'+tableRegistros.page.info().recordsTotal+'" href="#" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true" style="font-size:48px;color:red;"></i></a></div>'
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
        "Revista":'<input id="Revista'+tableRevista.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Revista">',
        "Volumen":'<input id="Volumen'+tableRevista.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Volumen">',
        "PrimerayUltimaPagina":'<input id="PrimerayUltimaPagina'+tableRevista.page.info().recordsTotal+'" value="" type="text" class="form-control" placeholder="Primera y última página">'
    }).draw();
    $('#tableRevista_last').click();
});

$('#agregarHijos').click(function(){
    if(($('#tableHijos tr').length) <= 7)
    {
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
            "Nacionalidad" : '<select id="NacionalidadDatos'+tableHijos.page.info().recordsTotal+'" class="form-control select2" style="width: 200xp;" required>'+
                                    '<option selected="selected" value="">Seleccione una opción</option>'+
                                    '<option value="Venezolano">Venezolano</option>'+
                                    '<option value="Extranjero">Extranjero</option>'+
                              '</select>'
        }).draw();
        $('#tableHijos_last').click();
        $('#datepickerHijo1'+aux).datepicker({
            autoclose: true
        });
        $('#datepickerHijo2'+aux).datepicker({
            autoclose: true
        });
    }
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