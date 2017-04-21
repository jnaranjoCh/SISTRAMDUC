var creationProcess = {
    start: function () {
        var data = this.getFormData();
        $.ajax({
            url: routes["start-creation-process"],
            method: "POST",
            data: data,
            statusCode: {
                200: function () {
                    $("#plan-septenal-colectivo-creation").find("form").hide();
                    $("#creation-progress").show();
                    planIndividualDataTable.load();
                    toastr.success("Proceso iniciado satisfactoriamente.");
                },
                400: function (jqXHR) {
                    toastr.error(jqXHR.responseJSON);
                },
                500: function () {
                    toastr.error(SERVER_ERROR_MESSAGE)
                }
            }
        });
    },
    next_year: Number.parseInt($("#start_year").val()),
    getFormData: function () {
        return {
            inicio: this.next_year,
            creation_deadline: $("#creation_deadline").val()
        };
    },
    approvePlanIndividual: function (id) {
        $.ajax({
            url: routes["approve-plan-septenal-individual"],
            method: "POST",
            data: {
                id: id
            },
            success: function () {
                toastr.success("Plan septenal individual aprobado satisfactoriamente.");
                planIndividualDataTable.dt.ajax.reload();
            },
            error: function () {
                toastr.error(SERVER_ERROR_MESSAGE);
            }
        });
    }
};

var planSeptenalColectivo = {
    get: function (inicio) {
        $.ajax({
            url: routes["plan-septenal-colectivo"],
            method: "GET",
            data: {
                inicio: inicio
            },
            statusCode: {
                200: function (data) {
                    if (data.status === "En creación") {
                        $("#plan-septenal-colectivo-creation").find("form").hide();
                        $("#creation-progress").show();
                    }
                    planIndividualDataTable.load({
                        "ajax": routes["get-all-plan-septenal-individual"] + "?inicio=" + inicio
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
                    toastr.error(SERVER_ERROR_MESSAGE)
                }
            }
        });
    }
};

var planIndividualDataTable = {
    dt: null,
    config: {
        columnDefs: [
            {
                render: function (data, type, row) {
                    var buttons = "<a class='btn-view-plan btn btn-flat btn-xs btn-primary' title='Ver detalles' data-id='" +
                        row[0] + "'><i class='fa fa-eye'></i></a>";

                    if (row[3] == "Esperando aprobación") {
                        buttons += "&nbsp;<a class='btn-approve-plan btn btn-flat btn-xs btn-success' title='Aprobar' data-id='" +
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
    },
    load: function (config) {
        $.extend(true, config, this.config);
        this.dt = $("#planes-septenales-individuales-table").DataTable(config);

        return this.dt;
    }
};

var planIndividualViewer = (function () {
    var viewer = $("#details-viewer"),
        widget = null, built = false;

    function buildWidget () {
        widget = new PlanSeptenalIndividual(
            $(".plan-septenal-individual"),
            creationProcess.next_year,
            routes["plan-septenal-individual"]
        );
        widget.disableEditing();
    }

    Object.defineProperties(viewer, {
        planWidget: {
            get: function () {
                if (! built) {
                    buildWidget();
                    built = true;
                }
                return widget;
            }
        },
        load: {
            value: function (data) {
                viewer.planWidget.load(data);
                viewer.modal("show");
            },
            writable: true
        }
    });

    return viewer;
}());

$(document).on("click", ".btn-view-plan", function (e) {
    planIndividualViewer.load({ id: $(this).data("id") });
});

$(document).on("click", ".btn-approve-plan", function (e) {
    creationProcess.approvePlanIndividual($(this).data("id"));
});

$(document).on("click", "#start-creation-btn", function (e) {
    creationProcess.start();
    e.preventDefault();
});
