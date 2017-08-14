$( window ).load(function() {
    var status = window.location.href.split("/");
    if(status[status.length-1] == "registro" && status[status.length-2] == "eliminar")
    {
        $("#myModal").modal("show");
        $.ajax({
            method: "POST",
            url: routeRegistroUnico['registro_eliminarconsultarusaurios_ajax'],
            dataType: 'json',
            success: function(data){
              if(data.length > 0)
              {
                  var users = '<select id="users" class="form-control select2" style="width: 100%;">';
                  users = users+"<option value='' selected='selected'>Seleccione una opción</option>";
                  for(var i = 0; i < data.length; i++)
                    users = users+"<option value='"+data[i]+"'>"+data[i]+"</option>";
                  users = users+'</select>';
              }else
              {
                  var users = '<input type="text" class="form-control" style="width: 100%;" value="No hay usuarios activos" readonly/>';
              }
              $(".modal-body").html(users);
              $("#title-modal").html("Seleccione el usuario a desactivar");
            }
        });
    
    }else if(status[status.length-1] == "registro" && status[status.length-2] == "activar")
    {
        $("#myModal").modal("show");
        $.ajax({
            method: "POST",
            url: routeRegistroUnico['registro_activarconsultarusaurios_ajax'],
            dataType: 'json',
            success: function(data){
              if(data.length > 0)
              {
                  var users = '<select id="users" class="form-control select2" style="width: 100%;">';
                  users = users+"<option value='' selected='selected'>Seleccione una opción</option>";
                  for(var i = 0; i < data.length; i++)
                    users = users+"<option value='"+data[i]+"'>"+data[i]+"</option>";
                  users = users+'</select>';
              }else
              {
                 var users = '<input type="text" class="form-control" style="width: 100%;" value="No hay usuarios desactivados" readonly/>';
              }
              $(".modal-body").html(users);
              $("#title-modal").html("Seleccione el usuario a activar");
            }
        });
    }
});