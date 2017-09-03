$( window ).load(function() {
    toastr.clear();
    var status = window.location.href.split("/");
    if(status[status.length-1] == "success"){
        toastr.success("Datos registrados exitosamente!.", "Exito!", {
            "timeOut": "0",
            "extendedTImeout": "0"});
        }else if(status[status.length-1] != "initial"){
            text = "Error!";
            toastr.error(text, "error", {
                "timeOut": "0",
                "extendedTImeout": "0" });
        }
});
