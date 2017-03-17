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

    this.config = {
        header: defineComponentData(opts ? opts.header : undefined),
        numeration: defineComponentData(opts ? opts.numeration : undefined)
    };

    this.header.setData(this.config.header).render();
    this.numeration.setData(this.config.numeration).render();
    this.container.append(this._getCellsHtml());

    this.$elements = this.container.find(".grid-element");

    var grid = this;

    function keydownkeyup (e) {
        if ( e.keyCode ==  CTRL_CODE ) {
            grid.state.multiselection = (e.type == "keydown");
        } else if ( e.keyCode == SHIFT_CODE ) {
            grid.state.rangeselection.active = (e.type == "keydown");
        }
    }

    function mouseup (e) {
        if ($(e.target).not(".grid-element, .grid-action-btn, .grid-clear-btn").length) {
            grid.state.rangeselection.active = false;
            grid.state.rangeselection.start = null;

            if (! grid.state.dragging.active) {
                grid.$elements.removeClass("selected");
            }
        }
        grid.state.dragging.active = false;
        grid.state.dragging.start = null;
    }

    $(document)
        .on("keydown keyup", keydownkeyup)
        .on("mouseup", mouseup);

    $(this.container)
        .on("mousedown", ".grid-element", function (e) {
            if (grid.state.multiselection) {
                $(this).toggleClass("selected");
            } else {
                grid.$elements.removeClass("selected");
                $(this).addClass("selected");
            }

            updateSelection(grid.state.rangeselection, this, ! grid.state.multiselection);

            if (! grid.state.rangeselection.active || grid.state.rangeselection.start == null) {
                grid.state.rangeselection.start = this;
            }

            grid.state.dragging.active = true;
            grid.state.dragging.start = this;
        })
        .on("mouseenter", ".grid-element", function (e) {

            updateSelection(grid.state.dragging, this, true);
        })
        .on("dblclick", ".grid-element", function () {
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
            row += "<div class='grid-element'></div>";
        }
        for (var i = 0; i < this.numeration.data.length; i++) {
            grid_body += "<div class='grid-row'>" + row + "</div>";
        }

        return grid_body;
    },
    getSelection: function () {
        return $('.grid-element.selected');
    },
    getRange: function (point1, point2) {
        return getRange(point1, point2);
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

function updateSelection (selection, curr, should_retreat) {
    if (selection.active) {
        var $range = getRange(curr, selection.start);
        $range.addClass("selected");

        if (should_retreat && isSameSelection(selection.last_range, selection.start)) {
            selection.last_range.not($range).removeClass("selected");
        }
        selection.last_range = $range;
    }
}

function isSameSelection (last_range, last_start) {
    return last_range != null && $(last_start).is(last_range);
}

function getRange (point1, point2) {
    var $points = $(point1).add($(point2)), $parents = $points.closest(".grid-row");

    if ($parents.length == 1) {
        return getSimpleRange($points);
    }

    var $range_body = $parents.first().nextUntil( $parents.last() ).find(".grid-element");
    var $range_head = $parents.first().find( $points ).nextAll();
    var $range_tail = $parents.last().find( $points ).prevAll();

    return $range_body.add($range_head).add($range_tail).add($points);
}

function getSimpleRange ($points) {
    return ($points.length == 1) ? $points : $points.first().nextUntil( $points.last() ).add($points);
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
