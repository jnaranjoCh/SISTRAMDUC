var idGlobal;
var idCheckGlobal;

$('#tableRegistros').on( 'click', 'td', function () {
    var cell = new Object();
    cell.row = tableRegistros.cell( this ).index().row; cell.column = "10"; cell.columnVisible = "0";
    if(tableRegistros.cell( this ).index().column == 10){
        var id = tableRegistros.cell(cell).data().split('<span id="')[1].split('"')[0];
        var idCheck = tableRegistros.cell(cell).data().split('<input type="checkbox" id="')[1].split('"')[0];
        if($('#'+id).attr('class').includes('label-warning') && $("#"+idCheck).prop('checked'))
        {
            idGlobal = id;
            idCheckGlobal = idCheck;
            detener = false;
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
}

function continuarIFrame(){
    $('#'+idGlobal).removeClass('label label-warning');
    $('#'+idGlobal).addClass('label label-success');
    $('#'+idGlobal).text("Validado");
    $('#guardarArchivoIframe').addClass("hidden");
    $('html, body').css('overflow-y', 'auto');
    $('html, body').css('overflow-x', 'auto');
}

function validarIFrame(){
    $('#'+idGlobal).removeClass('label label-warning');
    $('#'+idGlobal).addClass('label label-success');
    $('#'+idGlobal).text("Validado");
    $('#guardarArchivoIframe').addClass("hidden");
    $('html, body').css('overflow-y', 'auto');
    $('html, body').css('overflow-x', 'auto');
}

function subir()
{
    $("html, body").animate({scrollTop:"0px"});
}
