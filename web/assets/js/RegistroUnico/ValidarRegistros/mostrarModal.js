$( window ).load(function() {
    $('#myModalValidate').modal("show");
    $("html, body").animate({scrollTop:"0px"});
    $('html, body').css('overflow-y', 'hidden');
    $('html, body').css('overflow-x', 'hidden');
    $('.main-footer').addClass("hidden");
    $('.main-header').addClass("hidden");
    $('.main-sidebar').addClass("hidden");
    $('#sidebar').addClass("hidden");
    $('#sidebar-mini-aux').removeClass("hold-transition skin-blue sidebar-mini");
    $('#wraper-aux').removeClass("wrapper");
    $('#content-wrapper-aux').removeClass("content-wrapper");
    
});

$('#validar').click(function(){
   $('#EmailDelRegistro').val(parent.getEmail());
   $('#idDelRegistro').val(parent.getId());
   document.getElementById("completeForm").submit();
   parent.validarIFrame();
});

$('#continuar').click(function(){
   parent.continuarIFrame();
});

$('#cerrar').click(function(){
    parent.closeIFrame();
});

$("#myModalValidate").on("hidden.bs.modal", function () {
    parent.closeIFrame();
});


