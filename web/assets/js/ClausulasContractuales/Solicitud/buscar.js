$('#generate').click(function(){
    toastr.clear();
    $('.fileinput-remove-button').click();
    $.ajax({
        method: "POST",
        data: {"Email":$('#mail').val()},
        url:   routeRegistroUnico['registro_consultarbuscarcedula_ajax'],
        dataType: 'json',
        success: function(data){
            if(data){
                $("#miniPersonal").removeClass("hidden");
                if($('#boxBodyHijos').css('display') == 'none'){
                    $("#miniPersonal").click();
                }
                
                if($('#boxBodyRecaudos').css('display') == 'block'){
                    $("#miniRecaudo").addClass("hidden");
                    $("#miniRecaudo").click();
                }
                $.ajax({
                    method: "POST",
                    data: {"Cedula":$('#mail').val()},
                    url: routeClausulasContractuales['clausulas_contractuales_discapacidad'],
                    dataType: 'json',
                    success: function(data){
                        var hijos = "";
                        if($.trim(data))
                        {
                            hijos ="<option value='' selected='selected'>Seleccione una opción</option>";
                            for(var i = 0; i < data["nombre"].length; i++)
                                hijos = hijos+"<option value='"+data["nombre"][i]+"_"+data["apellido"][i]+"_"+data["id"][i]+"'>"+data["nombre"][i]+" "+data["apellido"][i]+"</option>";
                            $("#selectedHijo").html(hijos);
                            
                        }else
                        {
                           hijos ="<option value='' selected='selected'>Este usuario no tiene hijos</option>";
                           $("#selectedHijo").html(hijos);
                        }
                    }
                });
                $.ajax({
                    method: "POST",
                    data: {"Cedula":$('#mail').val()},
                    url: routeClausulasContractuales['clausulas_contractuales_beca'],
                    dataType: 'json',
                    success: function(data){
                        var hijos = "";
                        if($.trim(data))
                        {
                            hijos ="<option value='' selected='selected'>Seleccione una opción</option>";
                            for(var i = 0; i < data["nombre"].length; i++)
                                hijos = hijos+"<option value='"+data["nombre"][i]+"_"+data["apellido"][i]+"_"+data["id"][i]+"'>"+data["nombre"][i]+" "+data["apellido"][i]+"</option>";
                            $("#selectedHijo").html(hijos);
                            
                        }else
                        {
                           hijos ="<option value='' selected='selected'>Este usuario no tiene hijos</option>";
                           $("#selectedHijo").html(hijos);
                        }
                    }
                });
                $.ajax({
                    method: "POST",
                    data: {"Cedula":$('#mail').val()},
                    url: routeClausulasContractuales['clausulas_contractuales_prima_hijos'],
                    dataType: 'json',
                    success: function(data){
                        var hijos = "";
                        if($.trim(data))
                        {
                            hijos ="<option value='' selected='selected'>Seleccione una opción</option>";
                            for(var i = 0; i < data["nombre"].length; i++)
                                hijos = hijos+"<option value='"+data["nombre"][i]+"_"+data["apellido"][i]+"_"+data["id"][i]+"'>"+data["nombre"][i]+" "+data["apellido"][i]+"</option>";
                            $("#selectedHijo").html(hijos);
                            
                        }else
                        {
                           hijos ="<option value='' selected='selected'>Este usuario no tiene hijos</option>";
                           $("#selectedHijo").html(hijos);
                        }
                    }
                });
            }else{
                $("#miniPersonal").addClass("hidden");
                toastr.error("El usuario no se encuentra registrado, está inactivo o no ha realizado el registro de datos.", "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                });

                if($('#boxBodyRecaudos').css('display') == 'block'){
                    $("#miniRecaudo").addClass("hidden");
                    $("#miniRecaudo").click();
                } 
                if($('#boxBodyHijos').css('display') == 'block'){
                    $("#miniPersonal").addClass("hidden");
                    $("#miniPersonal").click();
                } 
            }
        }
    });
});

