/* @author: Cesar Manrique <cesar.manrique.h@gmail.com> */
var PlanSeptenalIndividual = (function () {

var MONTHS = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"],
    TRAMITES = [
        { name: "Sabático", color: "#00a7d0" },
        { name: "Licencia No Remunerada", color: "#30bbbb" },
        { name: "Estudios de Postgrado con Carga Docente", color: "#368763" },
        { name: "Licencia Remunerada", color: "#00e765" },
        { name: "Beca", color: "#ff7701" },
        { name: "Curso de Formación Docente", color: "#db0ead" },
        { name: "Programa de Formación Especial", color: "#555299" },
        { name: "Plan Conjunto", color: "#ca195a" },
        { name: "Posible Extensión de Beca", color: "#00a65a" }
    ];

function PlanSeptenalIndividual (container, starting_year) {
    if (container === undefined) {
        throw "Container (first argument) is mandatory";
    }
    this.container = $(container);

    if ($(container).data("route") === undefined) {
        throw "Container (first argument) must have a data-route attribute";
    }

    if (! isInt(starting_year)) {
        throw "Starting year (second argument) must be a valid integer";
    }
    this.starting_year = starting_year;

    var $grid = $("<div class='grid'></div>").appendTo(this.container);
    this.grid = new Grid($grid, {
        header: MONTHS,
        numeration: range(starting_year, starting_year + 6)
    });

    getControls().appendTo(this.container);

    this.setStatus("");

    var plan = this;

    this.container.on("click", ".grid-clear-btn", function (e) {
        plan.grid.getSelection().css("background", "").removeData("tramite-type");
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

PlanSeptenalIndividual.TRAMITES = TRAMITES;

PlanSeptenalIndividual.prototype = {
    getState: function () {
        return {
            inicio: this.starting_year,
            fin: this.starting_year + 6,
            tramites: getTramitesSummary(this)
        };
    },
    setState : function (state) {
        this.grid.$cells.css("background", "").removeData("tramite-type");
        this.starting_year = state.inicio;
        this.grid.numeration.setData( range(state.inicio, state.fin) ).draw();

        if (Object.prototype.toString.call(state.tramites) != "[object Array]") {
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
            url: this.container.data("route"),
            data: this.getState(),
            method: method,
            success: function (data) {
                toastr["success"]("Los cambios han sido guardados");
                plan.setStatus("Modificando");
                $("#btn-save").show().prop("disabled", false);;
                $("#btn-request-approval").show().prop("disabled", false);
            },
            error: function (data) {
                toastr["error"]("Ocurrió un error. En caso de que el problema persista contacte a soporte");
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

        $.ajax({
            url: plan.container.data("route"),
            data: criteria,
            method: "GET",
            dataType: "json",
            statusCode: {
                200: function (data) {
                    $("#btn-save").show();
                    $("#btn-request-approval").show();
                    plan.setStatus(data.status).setState(data);

                    if (data.status == "Esperando aprobación") {
                        $("#btn-save").prop("disabled", true);
                        $("#btn-request-approval").prop("disabled", true);
                        plan.disableEditing();
                        return;
                    }

                    if (data.status == "Aprobado") {
                        $("#btn-save").prop("disabled", true);
                        $("#btn-request-approval").prop("disabled", true);
                        plan.disableEditing();
                        return;
                    }

                    $("#btn-save").prop("disabled", false);
                    $("#btn-request-approval").prop("disabled", false);
                },
                404: function () {
                    plan.setStatus("En creación");
                    $("#btn-save").show().prop("disabled", false);;
                    $("#btn-request-approval").show().prop("disabled", true);
                },
                500: function (data) {
                    toastr["error"]("Ocurrió un error al intentar cargar el plan septenal. En caso de que el problema persista contacte a soporte");
                }
            }
        });
    },
    askForApproval: function () {
        var plan = this;
        $.ajax({
            url: "/plan-septenal-individual/ask-for-approval",
            data: {
                inicio: plan.starting_year
            },
            method: "PUT",
            dataType: "json",
            statusCode: {
                200: function () {
                    plan.setStatus("Esperando aprobación");
                    toastr["success"]("El plan septenal está en espera por aprobación.");
                    $("#btn-request-approval").show().prop("disabled", true);
                    $("#btn-save").show().prop("disabled", true);
                    plan.disableEditing();
                },
                404: function (data) {
                    var msg = (data.responseJSON !== undefined) ? data.responseJSON[0] : "El plan septenal no existe.";
                    toastr["error"](msg);
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
    var tramite_groups = [];
    for (var i = 0; i < TRAMITES.length; i++) {
        tramite_groups.push(
            $plan.grid.$cells.filter(function () {
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
        related_date = cellIndexToDate(index, $plan);

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

function cellIndexToDate (index, $plan) {
    var year = Math.floor($plan.starting_year + (index / 12)),
        month = ("0" + (1 + index % 12)).slice(-2);

    return month + "/" + year;
}

function getRangeFromPeriodo (periodo, $plan) {
    var start = getCellFromDate(periodo.start, $plan),
        end = getCellFromDate(periodo.end, $plan);

    return $plan.grid.getRange(start, end);
}

function getCellFromDate (date, $plan) {
    var date_parts = date.split("/"),
        month = date_parts[0],
        year = date_parts[1];

    return $plan.grid.cell( (year - $plan.starting_year) * 12 + parseInt(month - 1) );
}

function getTramiteIndexFromName (name) {
    var index = TRAMITES.length;
    while (index-- && TRAMITES[index].name != name);

    return index;
}

function assignTramiteToRange (tramite, range) {
    range.data("tramite-type", tramite).css("background", TRAMITES[ tramite ].color);
}

function range (start, end) {
    var r = [], i;
    for (i = start; i <= end; i++) {
        r.push(i);
    }
    return r;
}

function isInt (value) {
    var x;
    if (isNaN(value)) {
        return false;
    }
    x = parseFloat(value);
    return (x | 0) === x;
}

return PlanSeptenalIndividual;

}());

function attemptToLoadPlanIndividual (receiver, inicio) {
    return $.ajax({
        url: "/plan-septenal-colectivo",
        method: "GET",
        data: {
            inicio: inicio
        },
        dataType: "json",
        statusCode: {
            200: function (data) {
                if (data.status === "En creación") {
                    receiver.plan = new PlanSeptenalIndividual(receiver.container, inicio);
                    receiver.plan.load({inicio: inicio});
                }
            },
            404: function (data) {
                if (receiver.container !== undefined && typeof receiver.container.html == "function") {
                    receiver.container.html("El plan septenal colectivo aún no existe.");
                }
            }
        }
    });
}
