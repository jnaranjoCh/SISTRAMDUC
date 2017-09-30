var idGlobal;
var idCheckGlobal;
var width;
var heigth;
var claveGlobal;

$('#tableRegistros').on( 'click', 'td', function () {
    var cell = new Object();
    var cell2 = new Object();
    cell.row = tableRegistros.cell( this ).index().row; cell.column = "10"; cell.columnVisible = "0";
    if(tableRegistros.cell( this ).index().column == 10){
        var id = tableRegistros.cell(cell).data().split('<span id="')[1].split('"')[0];
        var idCheck = tableRegistros.cell(cell).data().split('<input type="checkbox" id="')[1].split('"')[0];
        if($('#'+id).attr('class').includes('label-warning') && $("#"+idCheck).prop('checked'))
        {
            idGlobal = id;
            idCheckGlobal = idCheck;
            detener = false;
            width  = widthModal();
            heigth = heightModal();
            cell2.row = tableRegistros.cell( this ).index().row; cell2.column = "0"; cell2.columnVisible = "0";
            claveGlobal = tableRegistros.cell(cell2).data();
            $('#guardarArchivoIframe').width(widthModal());
            $('#guardarArchivoIframe').height(heightModal());
            $('#oscurecer').modal('show'); 
            $('html, body').css('overflow-y', 'hidden');
            $('html, body').css('overflow-x', 'hidden');
            $('#guardarArchivoIframe').removeClass("hidden");
            subir();        
        }
        else if($('#'+id).attr('class').includes('label-success') &&  !$("#"+idCheck).prop('checked'))
        {
            $('#'+id).removeClass('label label-success');
            $('#'+id).addClass('label label-warning');
            $('#'+id).text("No validado");
            $('#guardarArchivoIframe').addClass("hidden");
        }
    }   
});

function closeIFrame(){
    $('#'+idGlobal).removeClass('label label-success');
    $('#'+idGlobal).addClass('label label-warning');
    $('#'+idGlobal).text("No validado");
    $("#"+idCheckGlobal).prop('checked',false);
    $('#guardarArchivoIframe').addClass("hidden");
    $('html, body').css('overflow-y', 'auto');
    $('html, body').css('overflow-x', 'auto');
    $('#oscurecer').modal('hide');
}

function continuarIFrame(){
    $('#'+idGlobal).removeClass('label label-warning');
    $('#'+idGlobal).addClass('label label-success');
    $('#'+idGlobal).text("Validado");
    $('#guardarArchivoIframe').addClass("hidden");
    $('html, body').css('overflow-y', 'auto');
    $('html, body').css('overflow-x', 'auto');
    $('#oscurecer').modal('hide');
}

function validarIFrame(){
    $('#'+idGlobal).removeClass('label label-warning');
    $('#'+idGlobal).addClass('label label-success');
    $('#'+idGlobal).text("Validado");
    $('#guardarArchivoIframe').addClass("hidden");
    $('html, body').css('overflow-y', 'auto');
    $('html, body').css('overflow-x', 'auto');
    $('#oscurecer').modal('hide'); 
    
    toastr.clear();
    $.ajax({
        method: "POST",
        data: {"Registros":getData()},
        url:    routeRegistroUnico['registro_validaractualizarregistros_ajax'],
        dataType: 'json',
        beforeSend: function() {
            $("#myModal2").modal("show");
        },
        success: function(data){
            if(!data.localeCompare("Actualizado"))
                toastr.success("Datos actualizados exitosamente!.", "Éxito!", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                               });
            else
                toastr.error("Falló la actualización de los registros", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
            $("#myModal2").modal("hide");
        }
    });
}

function subir()
{
    $("html, body").animate({scrollTop:"0px"});
}

function widthModal()
{
    return $("#myModalValidateAux").width()-705;
}

function heightModal()
{
    return $("#myModalValidateAux").height()-420;
}

function getId()
{
    return claveGlobal;
}


function getEmail()
{
    return $('#mail').val();
}

function getData()
{
    var numFilas = obtenerFilas(tableRegistros);
    var registros = [];
    
    for(var i = 0; i < numFilas; i++)
    {
        var validar = new Object();
        var cellsRegistros = new Object();
        cellsRegistros.row = i;
        cellsRegistros.column = 10;
        cellsRegistros.columnVisible = "0";
        idCheck = findId(tableRegistros, cellsRegistros);
        if(findProp(tableRegistros, cellsRegistros))
            validar.validado = true;
        else
            validar.validado = false;
        cellsRegistros.column = 0;
        validar.idRegistro = tableRegistros.cell(cellsRegistros).data();
        registros[i] = validar;
    }
    
    return registros;
}

function obtenerFilas(table)
{
    return table.page.info().recordsTotal;
}

function findId(table, cellTable)
{
    var valor;
    if(table.cell(cellTable).nodes().to$().find('input').val() != null)
        valor = table.cell(cellTable).nodes().to$().find('input')[0].id;
    else
        valor = table.cell(cellTable).nodes().to$().find('select')[0].id;
    return valor;
}

function findProp(table, cellTable)
{
    var valor;
    if(table.cell(cellTable).nodes().to$().find('input').val() != null)
        valor = table.cell(cellTable).nodes().to$().find('input')[0].checked;
    else
        valor = table.cell(cellTable).nodes().to$().find('select')[0].checked;
    return valor;
}

function sendMessage()
{
    toastr.clear();
    toastr.error("Solo debe adjuntar un solo archivo", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
}