$( window ).load(function() {
    $('#myModalValidate').modal("show");
    $("html, body").animate({scrollTop:"0px"});
    $('html, body').css('overflow-y', 'hidden');
    $('html, body').css('overflow-x', 'hidden');
});


$('#validar').click(function(){
   document.getElementById("completeForm").submit();
   parent.validarIFrame();
});

$('#continuar').click(function(){
   parent.continuarIFrame();
});

$('#cerrar').click(function(){
    parent.closeIFrame();
});

