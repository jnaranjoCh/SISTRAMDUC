$('#submitData').click(function(){
    var anio;
    var date = new Date();
    
    if(!(/^[a-zA-Z]*$/).test($('#primerNombreDatosI').val()))
    {
        $('#primerNombreDatos').removeClass("hidden");
        $('#primerNombreDatosE').addClass("hidden");
        
    }else if($('#primerNombreDatosI').val() == "")
    {
        $('#primerNombreDatosE').removeClass("hidden");
        $('#primerNombreDatos').addClass("hidden");
    }else{
        $('#primerNombreDatosE').addClass("hidden");
        $('#primerNombreDatos').addClass("hidden");
    }
    
    if(!(/^[a-zA-Z]*$/).test($('#segundoNombreDatosI').val()))
    {
        $('#segundoNombreDatos').removeClass("hidden");
        $('#segundoNombreDatosE').addClass("hidden");
        
    }else if($('#segundoNombreDatosI').val() == "")
    {
        $('#segundoNombreDatosE').removeClass("hidden");
        $('#segundoNombreDatos').addClass("hidden");
    }else{
        $('#segundoNombreDatosE').addClass("hidden");
        $('#segundoNombreDatos').addClass("hidden");
    }
    
    if(!(/^[a-zA-Z]*$/).test($('#primerApellidoDatosI').val()))
    {
        $('#primerApellidoDatos').removeClass("hidden");
        $('#primerApellidoDatosE').addClass("hidden");
        
    }else if($('#primerApellidoDatosI').val() == "")
    {
        $('#primerApellidoDatosE').removeClass("hidden");
        $('#primerApellidoDatos').addClass("hidden");
    }else{
        $('#primerApellidoDatosE').addClass("hidden");
        $('#primerApellidoDatos').addClass("hidden");
    }
    
    if(!(/^[a-zA-Z]*$/).test($('#segundoApellidoDatosI').val()))
    {
        $('#segundoApellidoDatos').removeClass("hidden");
        $('#segundoApellidoDatosE').addClass("hidden");
        
    }else if($('#segundoApellidoDatosI').val() == "")
    {
        $('#segundoApellidoDatosE').removeClass("hidden");
        $('#segundoApellidoDatos').addClass("hidden");
    }else{
        $('#segundoApellidoDatosE').addClass("hidden");
        $('#segundoApellidoDatos').addClass("hidden");
    }
    
    if($('#nacionalidadDatosI').find('option:selected').val() == "")
    {
        $('#nacionalidadDatos').removeClass("hidden");
    }else{
        $('#nacionalidadDatos').addClass("hidden");
    }
    
    if($('#fechaNacimientoDatosI').val() == "")
    {
         $('#fechaNacimientoDatos').removeClass("hidden");
    }else{
        anio = parseInt($('#fechaNacimientoDatosI').val()[6]+$('#fechaNacimientoDatosI').val()[7]+$('#fechaNacimientoDatosI').val()[8]+$('#fechaNacimientoDatosI').val()[9]); 
        $('#fechaNacimientoDatos').addClass("hidden");
    }

    if(parseInt($('#edadDatosI').val()) > 80 || parseInt($('#edadDatosI').val()) < 18)
    {
        $('#edadDatos').removeClass("hidden");
        $('#edadDatosE').addClass("hidden");
        
    }else if($('#edadDatosI').val() == "")
    {
        $('#edadDatosE').removeClass("hidden");
        $('#edadDatos').addClass("hidden");
    }else{
        $('#edadDatosE').addClass("hidden");
        $('#edadDatos').addClass("hidden");
    }
    
    if($('#sexoDatosI').find('option:selected').val() == "")
    {
        $('#sexoDatos').removeClass("hidden");
    }else{
        $('#sexoDatos').addClass("hidden");
    }
    
    if($('#cedulaDatosI').val() == "")
    {
        $('#cedulaDatos').removeClass("hidden");
    }else{
        $('#cedulaDatos').addClass("hidden");
    }
    
    if($('#rifDatosI').val() == "")
    {
        $('#rifDatos').removeClass("hidden");
    }else{
        $('#rifDatos').addClass("hidden");
    }
    
    if(parseInt($('#numeroDatosI').val()) > 999 || parseInt($('#numeroDatosI').val()) < 100 || parseInt($('#numeroDatosII').val()) > 9999999 || parseInt($('#numeroDatosII').val()) < 1000000)
    {
        $('#numeroDatos').removeClass("hidden");
        $('#numeroDatosE').addClass("hidden");
        
    }else if($('#numeroDatosI').val() == "" || $('#numeroDatosII').val() == "")
    {
        $('#numeroDatos').addClass("hidden");
        $('#numeroDatosE').removeClass("hidden");
    }else{
        $('#numeroDatosE').addClass("hidden");
        $('#numeroDatos').addClass("hidden");
    }
    
    if(((parseInt($('#edadDatosI').val())-(parseInt(date.getFullYear())-anio)) < -1) || ((parseInt($('#edadDatosI').val())-(parseInt(date.getFullYear())-anio)) > 0))
            $('#noCoincide').removeClass("hidden");
    else
            $('#noCoincide').addClass("hidden");
    
    
    
    /*$.ajax({
        method: "POST",
        url:  "/web/app_dev.php/registro/guardar-datos",
        dataType: 'json',
        success: function(data)
        {
            alert(data);
        }
    });*/
});
