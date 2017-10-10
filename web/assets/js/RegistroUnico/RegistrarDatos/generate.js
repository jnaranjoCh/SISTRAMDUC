$('#mail').on('input',function(e){
    $("#formPersonal").addClass("hidden");
    $("#formRegistros").addClass("hidden");
    $("#formCargos").addClass("hidden");
    $("#divCheckbox").addClass("hidden");
    $("#formHijos").addClass("hidden");
    $("#save").addClass("hidden");
});

$('#generate').click(function(){
        var tableRelationship;
        
        toastr.clear();
        $.ajax({
            method: "POST",
            data: {"Email":$('#mail').val()},
            url: routeRegistroUnico['registro_buscaremail_ajax'],
            dataType: 'json',
            success: function(data){
                if(data["is_register"]){
                    $("#formPersonal").removeClass("hidden");
                    $("#formRegistros").removeClass("hidden");
                    $("#formCargos").removeClass("hidden");
                    $("#divCheckbox").removeClass("hidden");
                    $("#save").removeClass("hidden");
                    $("#CedulaMadreHijoDatos").val(data["ci"]);
                    $("#CedulaPadreHijoDatos").val(data["ci"]);
                    $.ajax({
                        method: "POST",
                        data: {"Email":$('#mail').val()},
                        url: routeRegistroUnico['registro_consultar_parentesco_ajax'],
                        dataType: 'json',
                        success: function(data){
                            if($.trim(data.length))
                            {
                                $('#otherChildrens').removeClass("hidden");
                                $('#relationship').removeClass("hidden");
                                $('#relationship').html('');
                                tableRelationship = "";
                                for(var i = 0; i < data.length; i++)
                                {
                                    $(tableRelationshipList[i]).dataTable().fnDestroy();
                                    tableRelationship = tableRelationship + '<h4 id="infoOtherParent'+i+'"></h4><div style="overflow-x: scroll; white-space: nowrap;">';
                                    tableRelationship = tableRelationship + '<table id="tableRelationship'+i+'" class="table table-bordered table-striped">'+
                                                                                '<thead>'+
                                                                                '<tr>'+
                                                                                  '<th>CI Madre</th>'+
                                                                                  '<th>CI Padre</th>'+
                                                                                  '<th>CI Hijo</th>'+
                                                                                  '<th>1er Nombre</th>'+
                                                                                  '<th>2do Nombre</th>'+
                                                                                  '<th>1er Apellido</th>'+
                                                                                  '<th>2do Apellido</th>'+
                                                                                  '<th>F Nacimiento</th>'+
                                                                                  '<th>Nacionalidad</th>'+          
                                                                                '</tr>'+
                                                                                '</thead>'+
                                                                                '<tbody>'+
                                                                                '</tbody>'+
                                                                                '<tfoot>'+
                                                                                '<tr>'+
                                                                                  '<th>CI Madre</th>'+
                                                                                  '<th>CI Padre</th>'+
                                                                                  '<th>CI Hijo</th>'+
                                                                                  '<th>1er Nombre</th>'+
                                                                                  '<th>2do Nombre</th>'+
                                                                                  '<th>1er Apellido</th>'+
                                                                                  '<th>2do Apellido</th>'+
                                                                                  '<th>F Nacimiento</th>'+
                                                                                  '<th>Nacionalidad</th>'+
                                                                                '</tr>'+
                                                                                '</tfoot>'+
                                                                              '</table>';
                                    tableRelationship = tableRelationship+'</div></br>';
                                    tableRelationshipList[i] = "#tableRelationship"+i;
                                }
                                $('#relationship').html(tableRelationship);
                                for(var i = 0; i < data.length; i++)
                                {
                                    if(data[i].primerNombre == "" && data[i].segundoNombre == "" && data[i].primerApellido == "" && data[i].segundoApellido == "")
                                        $("#infoOtherParent"+i).html("<strong>Usuario en espera por registrar ( Cedula: "+data[i].cedula+")</strong>");
                                    else
                                        $("#infoOtherParent"+i).html("<strong>Hijos de:</strong> "+data[i].primerNombre+" "+data[i].segundoNombre+" "+data[i].primerApellido+" "+data[i].segundoApellido);
                                    $(tableRelationshipList[i]).DataTable({
                                                "ajax":{
                                                   "url": routeRegistroUnico['registro_consultar_parentesco_hijos_ajax'],
                                                   "type": 'POST',
                                                   "data": {"cedula":data[i].cedula,"Email":$('#mail').val()}
                                                },
                                                "pagingType": "full_numbers",
                                        	    "language": {
                                                    	"url": tableLenguage['datatable-spanish']
                                                },
                                                columns: [
                                                    {"data":"CIMadre"},
                                                    {"data":"CIPadre"},
                                                    {"data":"CIHijo"},
                                                    {"data":"1erNombre"},
                                                    {"data":"2doNombre"},
                                                    {"data":"1erApellido"},
                                                    {"data":"2doApellido"},
                                                    {"data":"FNacimiento"},
                                                    {"data":"Nacionalidad"}
                                                ]
                                    });
                                }
                            }else
                            {
                                $('#otherChildrens').addClass("hidden");
                                $('#relationship').addClass("hidden");
                            }
                        }
                    });
                    
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
                    $('#relationship').addClass("hidden");
                }
            }
        });
});