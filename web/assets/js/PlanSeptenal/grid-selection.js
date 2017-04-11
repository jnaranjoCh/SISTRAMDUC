/* @author: Cesar Manrique <cesar.manrique.h@gmail.com> */
var Grid = (function() {

var CTRL_CODE = 17, SHIFT_CODE = 16;

function GridComponent () {
    this.$elements = null;
    this.data = [];
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
    jwrapped_set.__proto__ = {};
    for (i in jwrapped_set) {
        this[i] = jwrapped_set[i];
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

    this.header = new GridComponent();
    this.header.render = function () {
        var html = "<div class='grid-header'>";
        for (var i = 0; i < this.data.length; i++) {
            html += "<div class='grid-header-el'></div>";
        }
        html += "</div>";

        $header = $(html).appendTo(container);
        this.$elements = $header.find(".grid-header-el");
        this.draw();
    }

    this.numeration = new GridComponent();
    this.numeration.render = function () {
        var html = "<div class='grid-numeration'>";
        for (var i = 0; i < this.data.length; i++) {
            html += "<div class='grid-num'></div>";
        }
        html += "</div>";

        $numeration = $(html).appendTo(container);
        this.$elements = $numeration.find(".grid-num");
        this.draw();
    }

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

    this.header.setData(this.config.header).render();
    this.numeration.setData(this.config.numeration).render();
    this.container.append(this._getCellsHtml());

    this.$cells = this.container.find(".grid-cell");
    this.$rows = this.container.find(".grid-row");

    this._cells = [];
    for (var i = 0; i < this.$cells.length; i++) {
        this._cells[i] = new Cell(this.$cells.eq(i));
    }

    this._rows = [];
    var cells_per_row = this._cells.length / this.numeration.data.length,
        start, end;
    for (var i = 0; i < this.$rows.length; i++) {
        start = cells_per_row * i;
        end = start + cells_per_row;
        this._rows[i] = new Row(this.$rows.eq(i), this._cells.slice(start, end));
    }

    var grid = this;

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

    $(this.container)
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

Grid.prototype = {
    _getCellsHtml: function () {
        var row = "", grid_body = "";

        for (var i = 0; i < this.header.data.length; i++) {
            row += "<div class='grid-cell'></div>";
        }
        for (var i = 0; i < this.numeration.data.length; i++) {
            grid_body += "<div class='grid-row'>" + row + "</div>";
        }

        return grid_body;
    },
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
            return getObviousRange(point1, point2);
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

function getObviousRange (point1, point2) {
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

if (typeof Array.prototype.fill !== "function") {
    Array.prototype.fill = function (val) {
        for (var i = 0; i < this.length; i++) {
            this[i] = val;
        }
        return this;
    };
}

return Grid;

}());
