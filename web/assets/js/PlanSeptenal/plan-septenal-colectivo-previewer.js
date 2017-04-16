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

        if (data.planes.length == 0) {
            throw "data.planes cannot be empty";
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
