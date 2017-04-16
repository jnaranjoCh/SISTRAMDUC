/* @author: Cesar Manrique <cesar.manrique.h@gmail.com> */

var PlanSeptenalIndividual = (function () {

var MONTHS = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];

function PlanSeptenalIndividual (container, starting_year, ajax_url) {
    if (container === undefined) {
        throw "Container (first argument) is mandatory";
    }
    this.container = $(container);

    if (! Number.isInteger(starting_year)) {
        throw "Starting year (second argument) must be a valid integer";
    }
    this.starting_year = starting_year;

    this.ajax_url = ajax_url || '';

    var $grid = $("<div class='grid'></div>").appendTo(this.container);
    this.grid = new Grid($grid, {
        header: MONTHS,
        numeration: rangoSeptenal(starting_year)
    });

    var spinner_wrapper = getSpinner();
    this.spinner = spinner_wrapper.find(".spinner");

    spinner_wrapper.appendTo(this.container);
    getControls().appendTo(this.container);

    this.setStatus("");

    var plan = this;

    this.container.on("click", ".grid-clear-btn", function (e) {
        removeTramite(plan.grid.getSelection());
    });
    this.container.on("click", ".grid-action-btn", function (e) {
        assignTramiteToRange( $(e.target).data("tramite-type"), plan.grid.getSelection() );
    });
    $(document).on("click", "#btn-save", function (e) {
        plan.save();
    });
    $(document).on("click", "#btn-request-approval", function (e) {
        plan.askForApproval();
    });
}

PlanSeptenalIndividual.prototype = {
    getState: function () {
        return {
            inicio: this.starting_year,
            tramites: getTramitesSummary(this)
        };
    },
    setState : function (state) {
        removeTramite(this.grid.$cells);
        this.starting_year = state.inicio;
        this.grid.numeration.setData( rangoSeptenal(state.inicio) ).draw();

        if (! Array.isArray(state.tramites)) {
            return;
        }

        for (var i = 0; i < state.tramites.length; i++) {
            var related_range = getRangeFromPeriodo(state.tramites[i].periodo, this),
                related_tramite = getTramiteIndexFromName(state.tramites[i].tipo);

            assignTramiteToRange(related_tramite, related_range);
        }
    },
    save: function () {
        var plan = this,
            method = (this.status === "Modificando") ? "PUT" : "POST";
        $.ajax({
            url: plan.ajax_url,
            data: plan.getState(),
            method: method,
            success: function (data) {
                $("#btn-save").show().prop("disabled", false);
                $("#btn-request-approval").show().prop("disabled", false);

                plan.setStatus("Modificando");
                toastr.success("Los cambios han sido guardados");
            },
            error: function (data) {
                toastr.error(SERVER_ERROR_MESSAGE);
            }
        });
    },
    load: function (criteria) {
        var plan = this;
        if (typeof criteria != "object") {
            throw "criteria must be an object";
        }
        if (criteria.id === undefined && criteria.inicio === undefined) {
            throw "criteria must have a property id or inicio";
        }

        removeTramite(plan.grid.$cells);
        plan.spinner.show();

        $.ajax({
            url: plan.ajax_url,
            data: criteria,
            method: "GET",
            dataType: "json",
            statusCode: {
                200: function (data) {
                    $("#btn-save").show();
                    $("#btn-request-approval").show();

                    plan.setStatus(data.status).setState(data);
                    plan.spinner.hide();

                    if (data.status == "Esperando aprobación" || data.status == "Aprobado") {
                        $("#btn-save").prop("disabled", true);
                        $("#btn-request-approval").prop("disabled", true);
                        plan.disableEditing();
                        return;
                    }

                    $("#btn-save").prop("disabled", false);
                    $("#btn-request-approval").prop("disabled", false);
                },
                404: function () {
                    $("#btn-save").show().prop("disabled", false);
                    $("#btn-request-approval").show().prop("disabled", true);

                    plan.setStatus("En creación");
                    plan.spinner.hide();
                },
                500: function (data) {
                    toastr.error(SERVER_ERROR_MESSAGE);
                    plan.spinner.hide();
                }
            }
        });
    },
    askForApproval: function () {
        var plan = this;
        $.ajax({
            url: plan.ajax_url + "/ask-for-approval",
            data: {
                inicio: plan.starting_year
            },
            method: "PUT",
            dataType: "json",
            statusCode: {
                200: function () {
                    $("#btn-save").show().prop("disabled", true);
                    $("#btn-request-approval").show().prop("disabled", true);

                    plan.setStatus("Esperando aprobación");
                    plan.disableEditing();

                    toastr.success("El plan septenal está en espera por aprobación.");
                },
                404: function (jqXHR) {
                    var msg = (jqXHR.responseJSON !== undefined) ? jqXHR.responseJSON[0] : "El plan septenal no existe.";
                    toastr.error(msg);
                }
            }
        });
    },
    getStatus: function () {
        return this.status;
    },
    setStatus: function (status) {
        $("#status").text(status);
        this.status = status;

        return this;
    },
    assignTramiteToRange: function (tramite, range) {
        assignTramiteToRange(tramite, range);
    },
    select: function (start_date, end_date) {
        var range = getRangeFromPeriodo({ start : start_date, end: end_date }, this);
        return this.grid.select(range);
    },
    getSelection: function () {
        return this.grid.getSelection();
    },
    getRange: function (start_date, end_date) {
        return getRangeFromPeriodo({ start : start_date, end: end_date }, this);
    },
    disableEditing: function () {
        this.grid.enabled = false;
        this.grid.unselect(this.getSelection());
        this.container.find(".grid-clear-btn").hide();

        return this;
    }
}

function getSpinner () {
    return $(
        '<div class="spinner-wrapper">' +
          '<div class="spinner" style="display: none;">' +
            '<div class="p">' + '<div></div>'.repeat(5) + '</div>' +
          '</div>' +
        '</div>'
    );
}

function getControls () {
    var $control = $("<div class='grid-control'>"), i;

    for (i = 0; i < TRAMITES.length; i++) {
        $("<input type='button'>")
            .addClass("btn btn-default btn-flat grid-action-btn")
            .data("tramite-type", i)
            .val(TRAMITES[i].name)
            .css("background", TRAMITES[i].color)
            .css("color", "white")
            .appendTo($control);
    }

    $("<input class='btn btn-default btn-flat grid-clear-btn' type='button' value='Eliminar'>").appendTo($control);

    return $control;
}

function getTramitesSummary ($plan) {
    var tramites_summary = [],
        tramite_groups = getTramiteGroups($plan);

    for (var i = 0; i < tramite_groups.length; i++) {
        pushTramitesIntoTramitesSummary(tramites_summary, tramite_groups[i], i, $plan)
    }
    return tramites_summary;
}

function getTramiteGroups ($plan) {
    var tramite_groups = [], group, cells = $plan.grid.$cells;
    for (var i = 0; i < TRAMITES.length; i++) {
        tramite_groups.push(
            cells.filter(function () {
                return $(this).data("tramite-type") == i;
            })
        );
    }
    return tramite_groups;
}

function pushTramitesIntoTramitesSummary (tramites_summary, tramite_group, tramite_index, $plan) {
    var j, index, prev_index = -1, last_tramite;

    for (j = 0; j < tramite_group.length; j++) {
        index = $plan.grid.$cells.index( tramite_group[j] );
        related_date = getDateFromCellIndex(index, $plan.starting_year);

        if (j == 0 || index - 1 != prev_index) {
            last_tramite = {
                periodo: {
                    start: related_date,
                    end: related_date
                },
                tipo: TRAMITES[ tramite_index ].name
            };
            tramites_summary.push(last_tramite);
        } else {
            last_tramite.periodo.end = related_date;
        }
        prev_index = index;
    }
}

function getRangeFromPeriodo (periodo, $plan) {
    var start = getCellIndexFromDate(periodo.start, $plan.starting_year),
        end = getCellIndexFromDate(periodo.end, $plan.starting_year);

    return $plan.grid.getRange($plan.grid.cell(start), $plan.grid.cell(end));
}

function rangoSeptenal (start) {
    var r = [], i, end = start + 6;
    for (i = start; i <= end; i++) {
        r.push(i);
    }
    return r;
}

return PlanSeptenalIndividual;

}());

function attemptToLoadPlanIndividual (receiver, inicio, ajax_url) {
    return $.ajax({
        url: "/plan-septenal-colectivo", // <--- hardcoded url
        method: "GET",
        data: {
            inicio: inicio
        },
        dataType: "json",
        statusCode: {
            200: function (data) {
                if (data.status === "En creación") {
                    receiver.plan = new PlanSeptenalIndividual(receiver.container, inicio, ajax_url);
                    receiver.plan.load({inicio: inicio});
                }
            },
            404: function () {
                if (receiver.container !== undefined && typeof receiver.container.html == "function") {
                    receiver.container.html("El plan septenal colectivo aún no existe.");
                }
            }
        }
    });
}
