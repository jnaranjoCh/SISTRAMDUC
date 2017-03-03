var $plan, first_year, tramite_types;

function initPlanSeptenalIndividual($el, $starting_year) {
    var $months = ['ENE', 'FEB', 'MAR', 'ABR', 'MAY', 'JUN', 'JUL', 'AGO', 'SEP', 'OCT', 'NOV', 'DIC'];
    first_year = (typeof $starting_year !== 'undefined') ? $starting_year : 2007;

    var numeration = function (i) {
        return first_year + i;
    }, heading = function (i) {
        return $months[i];
    };

    initGrid($el, 12, 7, numeration);
    $plan = $el;

    var $control = $("<div class='grid-control'>");

    tramite_types = [
        'Sabático',
        'Licencia No Remunerada',
        'Estudios de Postgrado con Carga Docente',
        'Licencia Remunerada',
        'Beca',
        'Curso de Formación Docente',
        'Programa de Formación Especial',
        'Plan Conjunto',
        'Posible Extensión de Beca'
    ];

    for (var i = 0; i < 9; i++) {
        $("<input class='grid-action-btn' type='button' value='" + tramite_types[i] + "' data-tramite-type='" + i + "'>").appendTo($control);
    }
    $("<input class='grid-clear-btn' type='button' value='Eliminar'>").appendTo($control);

    $control.appendTo($el);
    return $el;
}

function getSummary() {
    var tramites = [], new_needed = true;

    $('.grid-element').each(function (index) {
        if (new_needed) {
            if ($(this).data('tramite-type') != undefined) {
                var related_date = getRelatedDate(index);

                tramites.push({
                    periodo: {
                        start: related_date,
                        end: related_date,
                    },
                    tipo: tramite_types[ $(this).data('tramite-type') ]
                });

                new_needed = false;
            }
        } else {
            if ($(this).data('tramite-type') != undefined || $(this).data('tramite-type') == '') {
                tramites[tramites.length - 1].periodo.end = getRelatedDate(index);
            } else {
                new_needed = true;
            }
        }
    });

    return tramites;
}

function getRelatedDate (index) {
    var year = Math.floor(first_year + (index / 12));
    var month = (index % 12) + 1;
    month = (month > 9) ? month : '0' + month;

    return month + '/' + year;
}

$(document).on('click', '.grid-action-btn', function (e) {
    $plan.find('.selected').data('tramite-type', $(e.target).data('tramite-type'));
});

$(document).on('click', '.grid-clear-btn', function (e) {
    $plan.find('.selected').removeData('tramite-type');
});
