$('#gemail').on('input',function(e){
    $("#formUsuario").addClass("hidden");
});

$('#generate').click(function(){
    toastr.clear();
    $.ajax({
        method: "POST",
        data: {"Email":$('#gemail').val()},
        url: routeRegistroUnico['registro_buscaremailconsultar_ajax'],
        dataType: 'json',
        success: function(data){
            if(data){
                $.ajax({
                    method: "POST",
                    data: {"Email":$('#gemail').val()},
                    url: routeRegistroUnico['registro_consultarusuario_ajax'],
                    dataType: 'json',
                    success: function(data){
                        if($('#gemail').val().indexOf("(Sin registrar)") >= 0)
                        {
                            $("#CedulaUser").prop('disabled', true);
                        }
                        $("#EmailUser").val($('#gemail').val());
                        $("#CedulaUser").val(data.Cedula);
                        $("#id").val(data.Id);
                        $("#formUsuario").removeClass("hidden");
                        tableRol = $('#tableRol').DataTable({
                                        "ajax":{
                                        "url": routeRegistroUnico['registro_consultarroles_ajax'],
                                        "type": 'POST',
                                        "data": {"Email":$('#gemail').val()}
                                        },
                                        "pagingType": "full_numbers",
                                        "bDestroy": true,
                                        "language": {
                                                "url": tableLenguage['datatable-spanish']
                                        },
                                        columns: [
                                            { "data": "Rol"}
                                        ]
                                    });
                        refreshIntervalId = setInterval(initCountRol, 2500);
                    }
                });          
            }else{
                toastr.error("El usuario no se encuentra registrado", "Error", {
                            "timeOut": "0",
                            "extendedTImeout": "0"
                            });
                $("#formUsuario").addClass("hidden");
            }
        }
    });
});

function initCountRol(){
    countRol = tableRol.page.info().recordsTotal;
    if(countRol > 0)
    {
        clearInterval(refreshIntervalId);
    }
}
