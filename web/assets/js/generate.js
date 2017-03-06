$('#gemail').on('input',function(e){
    $("#form-1").addClass("hidden");
    $("#form-2").addClass("hidden");
    $("#form-3").addClass("hidden");
    $("#save").addClass("hidden");
});

$('#generate').click(function(){
        $.ajax({
            method: "POST",
            data: {"Email":$('#gemail').val()},
            url:  "/web/app_dev.php/registro/buscar-email",
            dataType: 'json',
            success: function(data)
            {
                if((data["Rol"] ==  "Docente"  || data["Rol"] == "Administrador") && data["Activo"] == true)
                {
                    $("#alertData").addClass("hidden");
                    $("#form-1").removeClass("hidden");
                    $("#form-2").removeClass("hidden");
                    $("#form-3").removeClass("hidden");
                    $("#save").removeClass("hidden");
                }
                else if(data["Rol"] == "Otros" && data["Activo"] == true)
                {
                    $("#alertData").addClass("hidden");
                    $("#form-1").removeClass("hidden");
                    $("#form-2").addClass("hidden");
                    $("#form-3").addClass("hidden");
                    $("#save").removeClass("hidden");
                }else
                {
                    $("#alertData").removeClass("hidden");
                    $("#form-1").addClass("hidden");
                    $("#form-2").addClass("hidden");
                    $("#form-3").addClass("hidden");
                    $("#save").addClass("hidden");
                }
            }
        });
});