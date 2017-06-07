function uploadFiles() {
    event.preventDefault();
    console.log("hago algo");
    var form = $('#form_recaudos')[0]

    $.ajax({
        url: "{{ path('verificar-datos') }}",
        data: new FormData(form),
        type: "POST",
        contentType: false,
        processData: false,
        cache: false,
        dataType: "json",
        error: function(error) {
            console.log('error',error);
        },
        success: function(data) {
            console.log('success',data);
        },
        complete: function() {
            console.log("Request finished");
        }
    });
// {{ path('verificar-datos') }}
}
