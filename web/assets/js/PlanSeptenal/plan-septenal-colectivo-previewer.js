var PlanSeptenalColectivoTable = (function () {

function PlanSeptenalColectivoTable (container, data) {
    validateInput(container, data);
    this.container = $(container);
    this.container.addClass("plan-table");
    this.setState(data);
}

PlanSeptenalColectivoTable.prototype = {
    setState: function (data) {
        this.container.html("");

        build(this.container, data);
        addTramitesToTable(this.container, data);

        this.state = data;
    }
};

function validateInput (container, data) {
    if (container === undefined) {
        throw "container (first argument) is mandatory";
    }

    if (typeof data != "object") {
        throw "data of plan colectivo (second argument) is mandatory";
    }

    if (data.inicio === undefined || data.planes === undefined) {
        throw "inicio and planes are mandatory properties of data";
    }

    if (! Array.isArray(data.planes)) {
        throw "data.planes must be an array";
    }
}

function build (table, data) {
    var cells = "<td></td>".repeat(84),
        i, planesCount = data.planes.length;

    var rows = "";
    for (i = 0; i < planesCount; i++) {
        rows += "<tr><td>" + data.planes[i].owner.nombre + "</td>" + cells + "</tr>";
    }

    var headers = "<td>Profesores</td>";
    for (i = 0; i < 7; i++) {
        headers += "<td colspan='12'>" + (data.inicio + i)+ "</td>";
    }

    table
        .append($("<thead>" + headers + "</thead>"))
        .append($("<tbody>" + rows + "</tbody>"));

    return table;
}

function addTramitesToTable(table, data) {
    var rows = table.find("tbody tr"), j, tramites, cells, range, start, end;
    for (i = 0; i < data.planes.length; i++) {
        tramites = data.planes[i].tramites;
        cells = rows.eq(i).find("td");

        for (j = 0; j < tramites.length; j++) {
            start = getCellIndexFromDate(tramites[j].periodo.start, data.inicio) + 1;
            end = getCellIndexFromDate(tramites[j].periodo.end, data.inicio) + 1;

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

return PlanSeptenalColectivoTable;

}());

var PlanSeptenalColectivoPreviewer = (function () {
    function PlanSeptenalColectivoPreviewer (container) {
        if (container === undefined) {
            throw "container (first argument) is mandatory";
        }

        this.container = $(container);
    }

    PlanSeptenalColectivoPreviewer.prototype = {
        load: function (criteria) {
            var plan = this;

            $.ajax({
                url: routes["plan-septenal-colectivo"],
                method: "GET",
                data: criteria,
                success: function (data) {
                    if (plan.table === undefined) {
                        plan.table = new PlanSeptenalColectivoTable(plan.container.find('table'), data);
                    } else {
                        plan.table.setState(data);
                    }
                    plan.container.modal("show");
                },
                error: function () {
                    toastr.error(SERVER_ERROR_MESSAGE);
                }
            });
        }
    }

    return PlanSeptenalColectivoPreviewer;
}());
