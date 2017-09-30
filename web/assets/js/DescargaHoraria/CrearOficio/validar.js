 $('#cedula').on('input',function(e){
        $("#formOficio").addClass("hidden");
    });


$('#validar').click(function(){
    toastr.clear();
    
    $.ajax({
        method: "POST",
        data: {"Cedula":$('#cedula').val()},
        url:   routeDescargaHoraria['oficio_buscarcedula_ajax'] ,
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