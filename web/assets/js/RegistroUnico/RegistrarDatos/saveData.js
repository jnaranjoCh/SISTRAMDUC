$('#submitData').click(function(){
    toastr.clear();
    var inputsO = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos","NacionalidadDatos","FechaNacimientoDatos","EdadDatos","SexoDatos","RifDatos", "NumeroDatos", "NumeroDatosII"];
    var inputsW = ["PrimerNombreDatos","SegundoNombreDatos","PrimerApellidoDatos","SegundoApellidoDatos"];
    var inputsR = ["EstatusDatos","NivelDeEstudioDatos","TipoDeRegistroDatos","DescripcionDatos","AnoPublicacionDatos","EmpresaDatos","InstitucionDatos"];
    var can_register = true;
    var date = new Date();
    var anio;
    var text = "";
    
    
    for(var i = 0; i < inputsO.length; i++)
    {
        if($("#"+inputsO[i]).val() == "")
        {
            if(inputsO[i] != "NumeroDatosII")
            {
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
            }else
            {
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#div"+inputsO[i-1]).addClass("has-error");
            }
            
            text = "Error campo mal introducido o obligatorio.";
        }else
        {
            if(inputsO[i] == "FechaNacimientoDatos")
              anio = parseInt($("#"+inputsO[i]).val()[6]+$("#"+inputsO[i]).val()[7]+$("#"+inputsO[i]).val()[8]+$("#"+inputsO[i]).val()[9]); 
              
            if(inputsO[i] == "EdadDatos" && (parseInt($("#"+inputsO[i]).val()) > 80 || parseInt($("#"+inputsO[i]).val()) < 18))
            {
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatos" &&  (parseInt($("#"+inputsO[i]).val()) > 999 || parseInt($("#"+inputsO[i]).val()) < 100)){
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i]).addClass("glyphicon-remove");
                $("#div"+inputsO[i]).addClass("has-error");
                text = "Error dato invalida.";
            }else if(inputsO[i] == "NumeroDatosII" &&   (parseInt($("#"+inputsO[i]).val()) > 9999999 || parseInt($("#"+inputsO[i]).val()) < 1000000))
            {
                can_register = false;
                $("#headerPersonal").css({ 'color': "red" });
                $("#span"+inputsO[i-1]).addClass("glyphicon-remove");
                $("#div"+inputsO[i-1]).addClass("has-error");
                text = "Error dato invalida.";
            }else
            {
                $("#headerPersonal").css({ 'color': "black" });
                $("#span"+inputsO[i]).removeClass("glyphicon-remove");
                $("#div"+inputsO[i]).removeClass("has-error");   
            }
        }
    }
    
    for(var i = 0; i < inputsW.length; i++)
    {
        if(!(/^[a-zA-Z]*$/).test($("#"+inputsW[i]).val()))
        {
            can_register = false;
            $("#headerPersonal").css({ 'color': "red" });
            $("#span"+inputsW[i]).addClass("glyphicon-remove");
            $("#div"+inputsW[i]).addClass("has-error");
            text = "Campo mal introducido.";
            
        }else if($("#"+inputsW[i]).val() != ""){
            $("#headerPersonal").css({ 'color': "black" });
            $("#span"+inputsW[i]).removeClass("glyphicon-remove");
            $("#div"+inputsW[i]).removeClass("has-error");
        }
    
    }
    
    if(can_register && (((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) < -1) || ((parseInt($('#EdadDatos').val())-(parseInt(date.getFullYear())-anio)) > 0)))
    {
        can_register = false;
        $("#headerPersonal").css({ 'color': "red" });
        text = "Error la edad no coincide con la fecha de nacimiento.";
    }

    if(countCargo < 1)
    {
         can_register = false;
         $("#spanCargosDatos").addClass("glyphicon-remove");
         $("#divCargosDatos").addClass("has-error");
         $("#headerCargos").css({ 'color': "red" });
    }else
    {
         $("#spanCargosDatos").removeClass("glyphicon-remove");
         $("#divCargosDatos").removeClass("has-error");
         $("#headerCargos").css({ 'color': "black" });
    }
    
    if(countRegistro < 1)
    {
         can_register = false;
         for(var i = 0; i < inputsR.length; i++)
         {
            if(inputsR[i] == "InstitucionDatos")
            {    
                if($("#TipoDeRegistroDatos").find('option:selected').val() == "Tutoria de servicio comunitario" && $("#"+inputsR[i]).val() == "")
                {    
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else
                {
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }else if(inputsR[i] == "EmpresaDatos")
            {   
                if($("#TipoDeRegistroDatos").find('option:selected').val() == "Tutoria de pasantias" && $("#"+inputsR[i]).val() == "")
                {    
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else
                {
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }else
            {
                
                if($("#"+inputsR[i]).val() == "")
                {
                    $("#headerRegistros").css({ 'color': "red" });
                    $("#span"+inputsR[i]).addClass("glyphicon-remove");
                    $("#div"+inputsR[i]).addClass("has-error");
                    text = "Error campo mal introducido o obligatorio.";
                }else
                {
                    $("#span"+inputsR[i]).removeClass("glyphicon-remove");
                    $("#div"+inputsR[i]).removeClass("has-error");        
                }
            }
         }
    }else
    {
        for(var i = 0; i < inputsR.length; i++)
        {    
            $("#headerRegistros").css({ 'color': "black" });
            $("#span"+inputsR[i]).removeClass("glyphicon-remove");
            $("#div"+inputsR[i]).removeClass("has-error");
        }
    }

    
    
    /*$.ajax({
        method: "POST",
        url:  "/web/app_dev.php/registro/guardar-datos",
        dataType: 'json',
        success: function(data)
        {
            alert(data);
        }
    });*/
    
    if(!can_register)
        toastr.error(text, "Error", {
                    "timeOut": "0",
                    "extendedTImeout": "0"
                 });
    else
       toastr.clear();
});
