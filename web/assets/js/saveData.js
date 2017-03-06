$('#submitData').click(function(){
    $.ajax({
        method: "POST",
        url:  "/web/app_dev.php/registro/guardar-datos",
        dataType: 'json',
        success: function(data)
        {
            alert(data);
        }
    });
});

function getData()
{
    
}