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
               toastr.error("El usuario no se encuentra registrado, está inactivo o no ha realizado el registro de datos.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                             });
            }
        }
    });
});