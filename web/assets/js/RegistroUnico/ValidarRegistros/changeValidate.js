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