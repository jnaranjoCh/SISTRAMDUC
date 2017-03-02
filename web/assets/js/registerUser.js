$("#registrarUsuario").click(function (){
    
    var can_register = true;
    if($("#passwordUsuario").val() != $("#retryPasswordUsuario").val())
    {
        can_register = false;
        $("#retryPasswordUsuarioS").removeClass("hidden");
    }
    else
        $("#retryPasswordUsuarioS").addClass("hidden");
    
    if($("#passwordUsuario").val() == "")
    {
        can_register = false;
        $("#passwordUsuarioS").removeClass("hidden");
    }
    else
        $("#passwordUsuarioS").addClass("hidden");
       
    if($("#retryPasswordUsuario").val() == "")
    {
        can_register = false;
        $("#retryPasswordUsuarioSS").removeClass("hidden");
    }
    else 
        $("#retryPasswordUsuarioSS").addClass("hidden");
       
    if($("#tu").find('option:selected').val() == "")
    {
        can_register = false;
        $("#tuS").removeClass("hidden");
    }
    else 
        $("#tuS").addClass("hidden");
    
    if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/).test($("#emailUsuario").val()))
        $("#emailUsuarioS").removeClass("hidden");
    else
    {
        $("#emailUsuarioS").addClass("hidden");
        $.ajax({
            method: "POST",
            data: {"Email":$("#emailUsuario").val()},
            url:  "/web/app_dev.php/registro/buscar-email",
            dataType: 'json',
            success: function(data)
            {
                if(data == "S")
                  $("#emailUsuarioSS").removeClass("hidden");
                else
                {
                    $("#emailUsuarioSS").addClass("hidden");
                    if(can_register)
                    {
                        $.ajax({
                            method: "POST",
                            data: {"Email":$("#emailUsuario").val(), "Password":$("#passwordUsuario").val(), "TipoUsuario":$("#tu").find('option:selected').val()},
                            url:  "/web/app_dev.php/registro/registrar-usuario",
                            dataType: 'json',
                            success: function(data)
                            {
                                $("#emailUsuario").val("");
                                $("#passwordUsuario").val("");
                                $("#retryPasswordUsuario").val("");
                            }
                        });
                    }
                }
                 
            }
        });
    }
});