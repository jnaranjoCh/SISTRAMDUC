var $plan, first_year;

var tramites = [
    { name: 'Sabático', color: '#00a7d0' },
    { name: 'Licencia No Remunerada', color: '#30bbbb' },
    { name: 'Estudios de Postgrado con Carga Docente', color: '#368763' },
    { name: 'Licencia Remunerada', color: '#00e765' },
    { name: 'Beca', color: '#ff7701' },
    { name: 'Curso de Formación Docente', color: '#db0ead' },
    { name: 'Programa de Formación Especial', color: '#555299' },
    { name: 'Plan Conjunto', color: '#ca195a' },
    { name: 'Posible Extensión de Beca', color: '#00a65a' }
];

function initPlanSeptenalIndividual($container, $starting_year) {
    var $months = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
    first_year = (typeof $starting_year !== 'undefined') ? $starting_year : 2007;

    var numeration = function (i) {
        return first_year + i;
    }
    var  heading = function (i) {
        return $months[i];
    }

    var $grid = $("<div class='grid'></div>");

    $container.append($grid);

    initGrid($grid, 12, 7, heading, numeration);
    $plan = $container;

    var $control = $("<div class='grid-control'>");

    for (var i = 0; i < 9; i++) {
        $("<input type='button'>")
            .addClass('btn btn-default btn-flat grid-action-btn')
            .data('tramite-type', i)
            .val(tramites[i].name)
            .css('background', tramites[i].color)
            .css('color', 'white')
            .appendTo($control);
    }

    $("<input class='btn btn-default btn-flat grid-clear-btn' type='button' value='Eliminar'>").appendTo($control);

    $control.appendTo($container);

    return $container;
}

function getSummary() {
    var summary = {
        inicio: first_year,
        fin: first_year + 6,
        tramites: []
    };

    var new_needed = true;

    $('.grid-element').each(function (index) {
        var tramite_type = $(this).data('tramite-type');

        if (! new_needed) {
            if (tramite_type == undefined || tramite_type === '') {
                new_needed = true;
                return true;
            }

            if (tramites[ tramite_type ].name == summary.tramites[ summary.tramites.length - 1 ].tipo) {
                summary.tramites[ summary.tramites.length - 1 ].periodo.end = getRelatedDate(index);
                return true;
            }
        }

        if (tramite_type != undefined && tramite_type !== '') {
            var related_date = getRelatedDate(index);

            summary.tramites.push({
                periodo: {
                    start: related_date,
                    end: related_date,
                },
                tipo: tramites[ tramite_type ].name
            });

            new_needed = false;
        }
    });

    return summary;
}

function getRelatedDate (index) {
    var year = Math.floor(first_year + (index / 12));
    var month = (index % 12) + 1;
    month = (month > 9) ? month : '0' + month;

    return month + '/' + year;
}

$(document).on('click', '.grid-action-btn', function (e) {
    var tramite_type = $(e.target).data('tramite-type');
    $plan.find('.grid-element.selected').data('tramite-type', tramite_type).css('background', tramites[ tramite_type ].color);
});

$(document).on('click', '.grid-clear-btn', function (e) {
    $plan.find('.selected').css('background', '').removeData('tramite-type');
});

$(document).on('click', 'button[type="submit"]', function (e) {
    saveChanges();
});

function saveChanges () {
    $.ajax({
        url: "/plan-septenal/individual",
        data: getSummary(),
        method: 'POST'
    });
}
