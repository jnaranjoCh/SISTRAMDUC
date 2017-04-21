/* @author: Cesar Manrique <cesar.manrique.h@gmail.com> */
var Grid = (function() {

var CTRL_CODE = 17, SHIFT_CODE = 16;

function GridComponent (data) {
    this.$elements = null;
    this.data = data === undefined ? [] : data;
    this.display = [];
};

GridComponent.prototype = {
    setData: function (data) {
        this.data = data;
        return this;
    },
    draw: function () {
        for (var i = 0; i < this.data.length; i++) {
            this.$elements.eq(i).html(this.data[i]);
        }
        this.display = this.data.slice();
        return this;
    },
    render: function (container, wrapper, element) {
        var html = "<div class='" + wrapper + "'>"
            + ("<div class='" + element + "'></div>").repeat(this.data.length) + "</div>";

        var $component = $(html).appendTo(container);
        this.$elements = $component.find("." + element);
        this.draw();
    }
}

function Cell (jwrapped_set) {
    jwrapped_set.__proto__ = {};
    for (i in jwrapped_set) {
        this[i] = jwrapped_set[i];
    }
}

Cell.prototype = $();
Cell.prototype.isSelected = function () {
    return this.hasClass('selected');
}

function Row (jwrapped_set, cells) {
    for (i in jwrapped_set) {
        if (jwrapped_set.hasOwnProperty(i)) {
            this[i] = jwrapped_set[i];
        }
    }
    this.cells = cells;
}

Row.prototype = $();
Row.prototype.cell = function (n) {
    return this.cells[n];
}

function Grid (container, opts) {
    if (container === undefined) {
        throw "Container (first argument) is mandatory";
    }
    this.container = container = $(container);

    this.state = {
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

    this.enabled = true;

    this.config = {
        header: defineComponentData(opts ? opts.header : undefined),
        numeration: defineComponentData(opts ? opts.numeration : undefined)
    };

    this.header = new GridComponent(this.config.header);
    this.header.render(this.container, 'grid-header', 'grid-header-el');

    this.numeration = new GridComponent(this.config.numeration);
    this.numeration.render(this.container, 'grid-numeration', 'grid-num');

    this.container.append(this._getCellsHtml());

    this.$cells = this.container.find(".grid-cell");
    this.$rows = this.container.find(".grid-row");

    this._cells = createCells(this.$cells);
    this._rows = createRows(this.header.data.length, this.$rows, this._cells)

    addDomListeners(this);
}

Grid.prototype = {
    cell: function (pos) {
        return this._cells[pos];
    },
    row: function (pos) {
        return this._rows[pos];
    },
    select: function (start, end) {
        var range = (start instanceof jQuery) ? start : this.getRange(start, end);
        return range.addClass('selected');
    },
    unselect: function (start, end) {
        var range = (start instanceof jQuery) ? start : this.getRange(start, end);
        return range.removeClass('selected');
    },
    getSelection: function () {
        return this.$cells.filter('.selected');
    },
    getRange: function (point1, point2) {
        point1 = typeof point1 == "number" ? this.$cells.eq(point1) : point1;
        point2 = typeof point2 == "number" ? this.$cells.eq(point2) : point2;

        if (point1 === null || point2 === null) {
            return getOnePointRange(point1, point2);
        }

        var bounds = getRangeBoundaries(point1, point2, this.$cells),
            range = $(), i;

        for (i = bounds.start; i <= bounds.end; i++) {
            range = range.add(this.$cells[i]);
        }

        return range;
    },
    destroy: function () {
        this.container.empty();
        this.container.off();
        // don't remove this comment without adding the corresponding test
        $(document)
            .off("keydown keyup", this._keydownkeyup)
            .off("mouseup", this._mouseup);
    },
    _getCellsHtml: function () {
        var row = "<div class='grid-cell'></div>".repeat(this.header.data.length);
        return ("<div class='grid-row'>" + row + "</div>").repeat(this.numeration.data.length);
    }
};

function updateSelection (selection, curr, should_retreat, grid) {
    if (selection.active) {
        var $range = grid.select(curr, selection.start);
        if (should_retreat && isSameSelection(selection.last_range, selection.start)) {
            selection.last_range.not($range).removeClass("selected");
        }
        selection.last_range = $range;
    }

    function isSameSelection (last_range, last_start) {
        return last_range != null && $(last_start).is(last_range);
    }
}

function getOnePointRange (point1, point2) {
    var range = $();
    if (point1 !== null) {
        range = range.add(point1);
    }
    if (point2 !== null) {
        range = range.add(point1);
    }
    return range;
}

function getRangeBoundaries (point1, point2, cells) {
    var point1_index = cells.index(point1),
        point2_index = cells.index(point2),
        start = Math.min(point1_index, point2_index),
        end = Math.max(point1_index, point2_index);

    return {start: start, end: end};
}

function defineComponentData (prop) {
    if (typeof prop === "number") {
        prop = (new Array(prop)).fill('');
    } else if (! prop || ! prop.constructor === Array) {
        prop = (new Array(5)).fill('');
    }
    return prop;
}

function addDomListeners (grid) {
    function keydownkeyup (e) {
        if ( e.keyCode ==  CTRL_CODE ) {
            grid.state.multiselection = (e.type == "keydown");
        } else if ( e.keyCode == SHIFT_CODE ) {
            grid.state.rangeselection.active = (e.type == "keydown");
        }
    }

    function mouseup (e) {
        if ($(e.target).not(".grid-cell, .grid-action-btn, .grid-clear-btn").length) {
            grid.state.rangeselection.active = false;
            grid.state.rangeselection.start = null;

            if (! grid.state.dragging.active) {
                grid.$cells.removeClass("selected");
            }
        }
        grid.state.dragging.active = false;
        grid.state.dragging.start = null;
    }

    $(document)
        .on("keydown keyup", keydownkeyup)
        .on("mouseup", mouseup);

    $(grid.container)
        .on("mousedown", ".grid-cell", function (e) {
            if (! grid.enabled) {
                return;
            }

            if (grid.state.multiselection) {
                $(this).toggleClass("selected");
            } else {
                grid.$cells.removeClass("selected");
                $(this).addClass("selected");
            }

            updateSelection(grid.state.rangeselection, this, ! grid.state.multiselection, grid);

            if (! grid.state.rangeselection.active || grid.state.rangeselection.start == null) {
                grid.state.rangeselection.start = this;
            }

            grid.state.dragging.active = true;
            grid.state.dragging.start = this;
        })
        .on("mouseenter", ".grid-cell", function (e) {

            updateSelection(grid.state.dragging, this, true, grid);
        })
        .on("dblclick", ".grid-cell", function () {
            if (! grid.enabled) {
                return;
            }

            var $siblings = $(this).siblings().add( $(this) );

            if ($siblings.length == $siblings.filter(".selected").length) {
                $siblings.removeClass("selected");
            } else {
                $siblings.addClass("selected");
            }
        });
}

function createCells ($cells) {
    var cells = [];
    for (var i = 0; i < $cells.length; i++) {
        cells[i] = new Cell($cells.eq(i));
    }
    return cells;
}

function createRows (cells_per_row, $rows, cells) {
    var start, end, rows = [];

    for (var i = 0; i < $rows.length; i++) {
        start = cells_per_row * i;
        end = start + cells_per_row;
        rows[i] = new Row($rows.eq(i), cells.slice(start, end));
    }

    return rows;
}

return Grid;

}());
