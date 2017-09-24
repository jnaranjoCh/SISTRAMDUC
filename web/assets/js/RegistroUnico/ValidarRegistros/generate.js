$('#mail').on('input',function(e){
    $("#formRegistros").addClass("hidden");
});

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
               cargarRegistros();
               $("#formRegistros").removeClass("hidden");
               $("#save").removeClass("hidden");
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

function cargarRegistros()
{
    tableRegistros = $('#tableRegistros').DataTable({
                	    "ajax":{
                           "url": routeRegistroUnico['registro_validarconsultarregistros_ajax'],
                           "type": 'POST',
                           "data": {"email":$('#mail').val()}
                        },
                        "pagingType": "full_numbers",
                        "bDestroy": true,
                	    "language": {
                            	"url": tableLenguage['datatable-spanish']
                        },
                        columns: [
                            { "data": "Id del registro" },
                            { "data": "Tipo de referencia" },
                            { "data": "Descripcion" },
                            { "data": "Nivel" },
                            { "data": "Estatus" },
                            { "data": "Año de publicación y/o asistencia" },
                            { "data": "Empresa y/o institución" },
                            { "data": "Titulo Obtenido" },
                            { "data": "CiudadPais"},
                            { "data": "Congreso"},
                            { "data": "Validado" }
                        ]
                    });
}