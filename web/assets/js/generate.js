$('#generate').click(function(){
    if($('#gemail').val().localeCompare("docente") == 0 || $('#gemail').val().localeCompare("administrador") == 0)
    {
        $("#alertData").addClass("hidden");
        $("#form-1").removeClass("hidden");
        $("#form-2").removeClass("hidden");
    }
    else if($('#gemail').val().localeCompare("otros") == 0)
    {
        $("#alertData").addClass("hidden");
        $("#form-1").removeClass("hidden");
        $("#form-2").addClass("hidden");
    }else
    {
        $("#alertData").removeClass("hidden");
        $("#form-1").addClass("hidden");
        $("#form-2").addClass("hidden");
    }
});