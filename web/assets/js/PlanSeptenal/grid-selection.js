/* @author: Cesar Manrique <cesar.manrique.h@gmail.com> */

var state, $elements;

function initGrid ($grid, cols, rows, heading, numeration) {
    // PhantomJs complains about default parameter values
    var rows = typeof rows !== 'undefined' ? rows : 5,
        cols = typeof cols !== 'undefined' ? cols : 5,
        numeration = typeof numeration !== 'undefined' ? numeration : function (a) { return a; },
        heading = typeof heading !== 'undefined' ? heading : function (a) { return a; },
        row = '',
        elements = '';

    for (var i = 0; i < cols; i++) {
        row += '<div class="grid-element"></div>';
    }
    for (var i = 0; i < rows; i++) {
        $('<div class="grid-row"><div class="grid-num">' + numeration(i) + '</div>' + row + '</div>').appendTo($grid);
    }
    $elements = $('.grid-element');

    var header = '<div class="grid-header"><div class="grid-header-el"></div>';
    for (var i = 0; i < 12; i++) {
        header += '<div class="grid-header-el">' + heading(i) + '</div>';
    }
    header += '</div>';

    $grid.prepend(header);

    state = {
        multiselection: false,
        rangeselection: {
            active: false,
            start: null,
            last_range: null,
        },
        dragging: {
            active: false,
            start: null,
            last_range: null
        }
    };
}

var CTRL_CODE = 17, SHIFT_CODE = 16;

$(document).on("keydown", function (e) {
    if ( e.keyCode ==  CTRL_CODE ) {
        state.multiselection = true;
    } else if ( e.keyCode == SHIFT_CODE ) {
        state.rangeselection.active = true;
    }
}).on("keyup", function (e) {
    if ( e.keyCode == CTRL_CODE ) {
        state.multiselection = false;
    } else if ( e.keyCode == SHIFT_CODE ) {
        state.rangeselection.active = false;
    }
});

$(document).on('mousedown', '.grid-element', function () {
    if (state.multiselection) {
        $(this).toggleClass('selected');
    } else {
        $elements.removeClass('selected');
        $(this).addClass('selected');
    }

    update_selection(state.rangeselection, this, ! state.multiselection);

    if (! state.rangeselection.active || state.rangeselection.start == null) {
        state.rangeselection.start = this;
    }

    state.dragging.active = true;
    state.dragging.start = this;
}).on('mouseenter', '.grid-element', function () {

    update_selection(state.dragging, this, true);
}).on('mouseup', function (e) {
    if (! $(e.target).hasClass('grid-element') && ! $(e.target).hasClass('grid-action-btn') && ! $(e.target).hasClass('grid-clear-btn')) {
        state.rangeselection.active = false;
        state.rangeselection.start = null;

        if (! state.dragging.active) {
            $elements.removeClass('selected');
        }
    }
    state.dragging.active = false;
    state.dragging.start = null;
}).on('dblclick', '.grid-element', function () {
    var $siblings = $(this).siblings().add( $(this) );

    if ($siblings.length == $siblings.filter('.selected').length) {
        $siblings.removeClass('selected');
    } else {
        $siblings.addClass('selected');
    }
});

function update_selection (selection, curr, should_retreat) {
    if (selection.active) {
        var $range = get_range(curr, selection.start);
        $range.addClass('selected');

        if (should_retreat && is_same_selection(selection.last_range, selection.start)) {
            selection.last_range.not($range).removeClass('selected');
        }
        selection.last_range = $range;
    }
}

function is_same_selection (last_range, last_start) {
    return last_range != null && $(last_start).is(last_range);
}

function get_range (point1, point2) {
    var $points = $(point1).add($(point2)), $parents = $points.closest('.grid-row');

    if ($parents.length == 1) {
        return get_simple_range($points);
    }

    var $range_body = $parents.first().nextUntil( $parents.last() ).find('.grid-element');
    var $range_head = $parents.first().find( $points ).nextAll();
    var $range_tail = $parents.last().find( $points ).prevAll();

    return $range_body.add($range_head).add($range_tail).add($points);
}

function get_simple_range($points) {
    return ($points.length == 1) ? $points : $points.first().nextUntil( $points.last() ).add($points);
}
