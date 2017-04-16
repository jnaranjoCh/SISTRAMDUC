var SERVER_ERROR_MESSAGE = "Ocurrió un error. En caso de que el problema persista contacte a soporte";

var TRAMITES = [
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

function getTramiteIndexFromName (name) {
    var index = TRAMITES.length;
    while (index-- && TRAMITES[index].name != name);

    return index;
}


function assignTramiteToRange (tramite, range) {
    range.data("tramite-type", tramite).css("background", TRAMITES[ tramite ].color);
}

function removeTramite (range) {
    range.removeData("tramite-type").css("background", "");
}


function getCellIndexFromDate (date, starting_year) {
    var date_parts = date.split("/"),
        month = Number.parseInt(date_parts[0]),
        year  = Number.parseInt(date_parts[1]);

    return (year - starting_year) * 12 + (month - 1);
}

function getDateFromCellIndex (index, starting_year) {
    var year = starting_year + Math.floor(index / 12),
        month = ("0" + (1 + index % 12)).slice(-2);

    return month + "/" + year;
}
