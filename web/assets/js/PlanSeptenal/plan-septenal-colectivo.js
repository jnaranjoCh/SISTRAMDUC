var planSeptenalColectivo = {
    startCreationProcess: function (url, data) {
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            statusCode: {
                200: function () {
                    toastr.success("Proceso iniciado satisfactoriamente.");
                    $("#plan-septenal-colectivo-creation").find("form").hide();
                    $("#plan-septenal-colectivo-creation").find("#creation-progress").show();
                },
                400: function (jqXHR) {
                    toastr.error(jqXHR.responseJSON);
                },
                500: function () {
                    toastr.error("Ocurrió un error. En caso de que el problema persista contacte a soporte")
                }
            }
        });
    },
    getPlanSeptenalColectivoOfNextYear: function (url) {
        var inicio = parseInt($("#start_year").val());
        $.ajax({
            url: url,
            method: "GET",
            data: {
                inicio: inicio,
                fin: inicio + 6
            },
            statusCode: {
                200: function (data) {
                    var json_data = (typeof data === "object") ? data : $.parseJSON(data);
                    if (json_data.status === "En creación") {
                        $("#plan-septenal-colectivo-creation").find("form").hide();
                        $("#creation-progress").show();
                    }
                },
                404: function () {
                    $("#plan-septenal-colectivo-creation").find("form").show();
                    $("#creation-progress").hide();
                },
                400: function (jqXHR) {
                    toastr.error(jqXHR.responseJSON);
                },
                500: function () {
                    toastr.error("Ocurrió un error. En caso de que el problema persista contacte a soporte")
                }
            }
        });
    }
};

$(document).on("click", "#start-creation-btn", function (e) {
    var url = $("#plan-septenal-colectivo-creation").find("form").attr("action");
    planSeptenalColectivo.startCreationProcess(url, getFormData());
    e.preventDefault();
});

function getFormData () {
    var start_year = parseInt($("#start_year").val()),
        creation_deadline = $("#creation_deadline").val();

    return {
        inicio: start_year,
        fin: start_year + 6,
        creation_deadline: creation_deadline
    };
}
