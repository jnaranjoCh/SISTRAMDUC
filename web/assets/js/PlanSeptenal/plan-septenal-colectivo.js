var planSeptenalColectivo = {
    startCreationProcess: function (url, data) {
        var plan = this;
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            statusCode: {
                200: function () {
                    toastr.success("Proceso iniciado satisfactoriamente.");
                    $("#plan-septenal-colectivo-creation").find("form").hide();
                    $("#plan-septenal-colectivo-creation").find("#creation-progress").show();
                    plan._loadPlanesIndividualesTable({
                        dataSet: []
                    });
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
        var inicio = parseInt($("#start_year").val()),
            plan = this;

        $.ajax({
            url: url,
            method: "GET",
            data: {
                inicio: inicio
            },
            statusCode: {
                200: function (data) {
                    var json_data = (typeof data === "object") ? data : $.parseJSON(data);
                    if (json_data.status === "En creación") {
                        $("#plan-septenal-colectivo-creation").find("form").hide();
                        $("#creation-progress").show();
                    }
                    plan._loadPlanesIndividualesTable({
                        "ajax": "/plan-septenal-individual/get-all?inicio=" + inicio
                    });
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
    },
    _loadPlanesIndividualesTable: function (config) {
        $.extend(true, config, {
            columnDefs: [
                {
                    render: function (data, type, row) {
                        var buttons = "<a class='btn-view-plan btn btn-xs btn-primary' title='Ver detalles' data-id='" +
                            row[0] + "'><i class='fa fa-eye'></i></a>";

                        if (row[3] == "Esperando aprobación") {
                            buttons += "&nbsp;<a class='btn-approve-plan btn btn-xs btn-success' title='Aprobar' data-id='" +
                                row[0] + "'><i class='fa fa-check'></i></a>"
                        }

                        return buttons;
                    },
                    searchable: false,
                    orderable: false,
                    defaultContent: "",
                    targets: 4
                }
            ]
        });

        this.datatable = $("#planes-septenales-individuales-table").DataTable(config);
    },
    datatable: null
};

planSeptenalColectivo.details_viewer = $("#details-viewer");
planSeptenalColectivo.details_viewer.planIndividual = new PlanSeptenalIndividual( $(".plan-septenal-individual"), parseInt($("#start_year").val()));
planSeptenalColectivo.details_viewer.planIndividual.disableEditing();

$(document).on("click", ".btn-view-plan", function (e) {
    var viewer = planSeptenalColectivo.details_viewer;
    viewer.modal("show");
    viewer.planIndividual.load({ id: $(this).data("id") });
});

$(document).on("click", ".btn-approve-plan", function (e) {
    $.ajax({
        url: "/plan-septenal-individual/approve",
        method: "POST",
        data: {
            id: $(this).data("id")
        },
        success: function () {
            toastr.success("Plan septenal individual aprobado satisfactoriamente.");
            planSeptenalColectivo.datatable.ajax.reload();
        },
        error: function () {
            toastr.error("Ocurrió un error. En caso de que el problema persista contacte a soporte");
        }
    });
});

$(document).on("click", "#start-creation-btn", function (e) {
    var url = $("#plan-septenal-colectivo-creation").find("form").attr("action");
    planSeptenalColectivo.startCreationProcess(url, getFormData());
    e.preventDefault();
});

function getFormData () {
    return {
        inicio: parseInt($("#start_year").val()),
        creation_deadline: $("#creation_deadline").val()
    };
}
