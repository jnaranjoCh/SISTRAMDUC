var routes = {
    "plan-colectivo-get-all": "/plan-septenal-individual/get-all",
    "approve-plan-individual": "/plan-septenal-individual/approve",
    "start-creation-process": $("#plan-septenal-colectivo-creation").find("form").attr("action"),
    "plan-septenal-colectivo": $("#plan-septenal-colectivo-main-section").data("resource"),
    "plan-septenal-individual": $(".plan-septenal-individual").data("route")
};

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
            url: routes["approve-plan-individual"],
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
                        "ajax": routes["plan-colectivo-get-all"] + "?inicio=" + inicio
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
        widget = null, builded = false;

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
                if (! builded) {
                    buildWidget();
                    builded = true;
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

var PlanColectivoPreViewer = (function () {
    function render (container, data) {
        validateInput(container, data);
        container = $(container);
        container.html("");

        var table = buildTable(container, data);
        container.append(table);

        addTramitesToTable(container, data, table);
    }

    function addTramitesToTable(container, data, table) {
        var rows = table.find("tbody tr"), j, tramites, cells, range, start, end;
        for (i = 0; i < data.planes.length; i++) {
            tramites = data.planes[i].tramites;
            cells = rows.eq(i).find("td");

            for (j = 0; j < tramites.length; j++) {
                start = getCellIndexFromDate(tramites[j].periodo.start, data.year) + 1;
                end = getCellIndexFromDate(tramites[j].periodo.end, data.year) + 1;

                assignTramiteToRange(
                    getTramiteIndexFromName(tramites[j].tipo),
                    getSimpleRange(start, end, cells)
                );
            }
        }
    }

    function getSimpleRange (start, end, cells) {
        var range = (start == end) ? $() : cells.eq(start).nextUntil(cells.eq(end));
        return range.add(cells.eq(start)).add(cells.eq(end));
    }

    function validateInput (container, data) {
        if (container === undefined) {
            throw "container (first argument) is mandatory";
        }

        if (typeof data != "object") {
            throw "data of plan colectivo (second argument) is mandatory";
        }

        if (data.year === undefined || data.planes === undefined) {
            throw "year and planes are mandatory properties of data";
        }

        if (! Array.isArray(data.planes)) {
            throw "data.planes must be an array";
        }

        var invalidPlanes = data.planes.filter(function (plan) {
            return typeof plan != "object" || typeof plan.owner != "string" || typeof plan.status != "string" || ! Array.isArray(plan.tramites);
        });

        if (invalidPlanes.length) {
            throw 'data.planes elements must be objects of the form {owner: "owner", status: "status", tramites: []}';
        }
    }

    function buildTable (container, data) {
        var table = $("<table class='plan-table'></table>"),
            cells = "<td></td>".repeat(84), rows = "",
            i, planesCount = data.planes.length;

        for (i = 0; i < planesCount; i++) {
            rows += "<tr><td>" + data.planes[i].owner + "</td>" + cells + "</tr>";
        }

        var headers = "<td>Profesores</td>";

        for (i = 0; i < 7; i++) {
            headers += "<td colspan='12'>" + (data.year + i)+ "</td>";
        }

        table
            .append($("<thead>" + headers + "</thead>"))
            .append($("<tbody>" + rows + "</tbody>"));

        return table;
    }

    return {
        render: render
    };
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
