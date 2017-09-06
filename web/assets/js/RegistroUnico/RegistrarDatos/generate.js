$('#mail').on('input',function(e){
    $("#formPersonal").addClass("hidden");
    $("#formRegistros").addClass("hidden");
    $("#formCargos").addClass("hidden");
    $("#divCheckbox").addClass("hidden");
    $("#formHijos").addClass("hidden");
    $("#save").addClass("hidden");
});

$('#generate').click(function(){
        toastr.clear();
        $.ajax({
            method: "POST",
            data: {"Email":$('#mail').val()},
            url: routeRegistroUnico['registro_buscaremail_ajax'],
            dataType: 'json',
            success: function(data){
                if(data){
                    $("#formPersonal").removeClass("hidden");
                    $("#formRegistros").removeClass("hidden");
                    $("#formCargos").removeClass("hidden");
                    $("#divCheckbox").removeClass("hidden");
                    $("#save").removeClass("hidden");
                }else{
                    toastr.error("El usuario no se encuentra registrado, está inactivo o ya realizó el registro.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                                });
                    $("#formPersonal").addClass("hidden");
                    $("#formRegistros").addClass("hidden");
                    $("#formCargos").addClass("hidden");
                    $("#divCheckbox").addClass("hidden");
                    $("#save").addClass("hidden");
                }
            }
        });
});