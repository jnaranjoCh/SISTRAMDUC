$("#continue").click(function(){
    toastr.clear();
    if($("#users").val() != null && $("#users").val() != "")
    {
        var status = window.location.href.split("/");
        if(status[status.length-1] == "registro" && status[status.length-2] == "eliminar")
        {
             $.ajax({
                method: "POST",
                data: {"user":$("#users").val()},
                url:  routeRegistroUnico['registro_eliminarusaurios_ajax'],
                dataType: 'json',
                success: function(data){
                  $("#myModal").modal("hide");
                  toastr.success("Usuario desactivado exitosamente!", "Exito!", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                        });
                }
            });
        }else if(status[status.length-1] == "registro" && status[status.length-2] == "activar")
        {
             $.ajax({
                method: "POST",
                data: {"user":$("#users").val()},
                url:  routeRegistroUnico['registro_activarusaurios_ajax'] ,
                dataType: 'json',
                success: function(data){
                  $("#myModal").modal("hide");
                  toastr.success("Usuario activado exitosamente!", "Exito!", {
                        "timeOut": "0",
                        "extendedTImeout": "0"
                        });
                }
            });
            
        }
    }else
    {
       toastr.error("Error debe seleccionar un usuario", "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                    });
    }
});