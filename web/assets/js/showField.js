$('#tr').change(function(){
    var selectedOption = $(this).find('option:selected');
    if(selectedOption.val().localeCompare("Tutoria de pasantias") == 0)
        $("#em").removeClass("hidden");
    else
        $("#em").addClass("hidden");

    
    if(selectedOption.val().localeCompare("Tutoria de servicio comunitario") == 0)
        $("#in").removeClass("hidden");
    else
        $("#in").addClass("hidden");
});