$("#actualizarUsuario").click(function (){
    toastr.clear();
    var glyphicons = ["glyphicon-envelope","glyphicon-credit-card","glyphicon-lock","glyphicon-log-in"];
    var inputs = ["EmailUser","CedulaUser","PasswordUser","RetryPasswordUser"];
    var can_register = true;
    var text = "";
    
    for(var i = 0; i < inputs.length; i++){
        if($("#"+inputs[i]).val() == ""){
            can_register = false;
            $("#span"+inputs[i]).removeClass(glyphicons[i]);
            $("#span"+inputs[i]).addClass("glyphicon-remove");
            $("#div"+inputs[i]).addClass("has-error");
            text = "Campo mal introducido u obligatorio.";
        }else{
            $("#span"+inputs[i]).addClass(glyphicons[i]);
            $("#span"+inputs[i]).removeClass("glyphicon-remove");
            $("#div"+inputs[i]).removeClass("has-error");
        }
    }
    
    if(!(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/).test($("#EmailUser").val())){
        can_register = false;
        $("#spanEmailUser").removeClass("glyphicon-envelope");
        $("#spanEmailUser").addClass("glyphicon-remove");
        $("#divEmailUser").addClass("has-error");
        text = "Correo mal introducido.";
    }else{
        $("#spanEmailUser").addClass("glyphicon-envelope");
        $("#spanEmailUser").removeClass("glyphicon-remove");
        $("#divEmailUser").removeClass("has-error");
    }

    if(can_register && $("#PasswordUser").val() != $("#RetryPasswordUser").val()){
        can_register = false;
        text = "Las claves no coinciden.";
        $("#spanPasswordUser").removeClass("glyphicon-lock");
        $("#spanPasswordUser").addClass("glyphicon-remove");
        $("#divPasswordUser").addClass("has-error");
        
        $("#spanRetryPasswordUser").removeClass("glyphicon-log-in");
        $("#spanRetryPasswordUser").addClass("glyphicon-remove");
        $("#divRetryPasswordUser").addClass("has-error");
    }else if(can_register){
        $("#spanPasswordUser").addClass("glyphicon-lock");
        $("#spanPasswordUser").removeClass("glyphicon-remove");
        $("#divPasswordUser").removeClass("has-error");
        
        $("#spanRetryPasswordUser").addClass("glyphicon-log-in");
        $("#spanRetryPasswordUser").removeClass("glyphicon-remove");
        $("#divRetryPasswordUser").removeClass("has-error");
    }
    
    
    if(countRol < 1){
            can_register = false;
            $("#spanRolUser").addClass("glyphicon-remove");
            $("#divRolUser").addClass("has-error");
            text = "Error no se han seleccionado roles para este usuario.";
    }
    else{
        $("#spanRolUser").removeClass("glyphicon-remove");
        $("#divRolUser").removeClass("has-error");
        if(can_register){
            $.ajax({
                method: "POST",
                data: {"Id":$("#id").val(),"Cedula":$("#CedulaUser").val(),"Email":$("#EmailUser").val()},
                url:  routeRegistroUnico['registro_buscarcedulaconsultar_ajax'],
                dataType: 'json',
                beforeSend: function() {
                    $("#myModal").modal("show");
                },
                success: function(data){
                        if(data == "S")
                            toastr.error("La cédula o el email ya se encuentran registrados.", "Error", {
                                "timeOut": "0",
                                "extendedTImeout": "0"
                            });
                        else{
                            var array = new Array(countRol);
                            var i = 0;
                            tableRol.column(0)
                                .data()
                                .each( function ( value,index ) {
                                    array[i] = value;
                                    i++;
                                });
                            $.ajax({
                                method: "POST",
                                data: {"EmailActual":$("#gemail").val(),"Cedula":$("#CedulaUser").val(), "Password":$("#PasswordUser").val(), "Roles":array, "Email":$("#EmailUser").val()},
                                url:  routeRegistroUnico['registro_actualizarusuario_ajax'],
                                dataType: 'json',
                                beforeSend: function() {
                                    $("#myModal").modal("show");
                                },
                                success: function(data){
                                        if(data == "actualizado"){
                                            $("#PasswordUser").val("");
                                            $("#RetryPasswordUser").val("");
                                            $("#CedulaUser").val("");
                                            $("#EmailUser").val("");
                                            toastr.success("Datos del usuario actualizados exitosamente!.", "Éxito!", {
                                                "timeOut": "0",
                                                "extendedTImeout": "0"
                                            });
                                            tableRol.clear().draw();
                                            $('#tableUsers').dataTable().fnDestroy();
                                            $("#tableUsers").DataTable( {
                                                    "ajax": routeRegistroUnico['registro_obteneremails_ajax'],
                                                    "columns": [
                                                        { "data": "Email" },
                                                        { "data": "Estatus" },
                                                        { "data": "Registro Completo" }
                                                    ],
                                                    "language": {
                                                        "url": tableLenguage['datatable-spanish']
                                                    }
                                            });
                                            $("#gemail").val("");
                                            $("#formUsuario").addClass("hidden");
                                        }
                                        $("#myModal").modal("hide");
                                }
                            });
                        }
                        $("#myModal").modal("hide");
                }
            });
        }
    }
    
    if(!can_register)
    {
        $("#myModal").modal("hide");
        toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                    });
    }
    else
        toastr.clear();
});