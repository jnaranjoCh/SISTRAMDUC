var CTRL_CODE = 17, SHIFT_CODE = 16,
    ctrlDown  = jQuery.Event("keydown", { keyCode : CTRL_CODE }),
    ctrlUp    = jQuery.Event("keyup", { keyCode : CTRL_CODE }),
    shiftDown = jQuery.Event("keydown", { keyCode : SHIFT_CODE }),
    shiftUp   = jQuery.Event("keyup", { keyCode : SHIFT_CODE });

function triggerClickRelatedEvents () {
    $targets = Array.prototype.slice.call(arguments);
    for (var i = 0; i < $targets.length; i++) {
        $targets[i].trigger("mouseenter").trigger("mousedown").trigger("mouseup").trigger("click");
    }
}

function addGridReferences (that) {
    that.$cells  = that.grid.$cells;
    that.$first  = that.grid.cell(0);
    that.$second = that.grid.cell(1);
    that.$third  = that.grid.cell(2);
    that.$fourth = that.grid.cell(3);
    that.$fifth  = that.grid.cell(4);
    that.$last   = that.grid.cell(that.$cells.length -1);
}

QUnit.test("container is mandatory for grid initialization", function (assert) {
    assert.throws(
        function () {
            var g = new Grid();
        },
        /Container/
    );
});

QUnit.test("Create grid appropriately", function (assert) {
    this.grid = new Grid($(".grid"), {
        header : 12,
        numeration: 7
    });
    assert.equal($(".grid-header-el").length, 12, "grid must have 12 column headers");
    assert.equal($(".grid-num").length, 7, "grid must have 7 row numbers");
    assert.equal($(".grid-row").length, 7, "grid must have 7 rows");
    assert.equal($(".grid-cell").length, 84, "grid must have 84 cells");
});

QUnit.module("Single Selection", {
    beforeEach: function () {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("Click on a cell must add class selected", function (assert) {
    triggerClickRelatedEvents(this.$first);
    assert.ok(this.$first.isSelected(), "Clicked cell must has class .selected");
});

QUnit.test("Click on a cell must remove class selected from all cells", function (assert) {
    this.$cells.addClass("selected");
    triggerClickRelatedEvents(this.$first);

    assert.equal(this.grid.getSelection().length, 1, "Class .selected must be only in clicked cell");
});

QUnit.test("Holding click over a cell should keep it selected", function (assert) {
    this.$first.trigger("mousedown");
    assert.ok(this.$first.isSelected(), "Clicked cell must has class .selected");
});

QUnit.module( "Multiselection", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("Pressing ctrl key must allow multiselection", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first, this.$last);

    assert.equal(this.grid.getSelection().length, 2, "There should be two selected cells");
});

QUnit.test("Releasing ctrl key must deactivate multiselection", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first, this.$last);

    $(document).trigger(ctrlUp);
    triggerClickRelatedEvents(this.$first);

    assert.equal(this.grid.getSelection().length, 1, "There should be two selected cells");
});

QUnit.test("When user is multiselecting class selected must be toggled in clicked cells", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first);
    assert.equal(this.grid.getSelection().length, 1, "There should be one selected cells");

    triggerClickRelatedEvents(this.$first);
    assert.equal(this.grid.getSelection().length, 0, "There should be one selected cells");
});


QUnit.module("Rangeselection", {
    beforeEach: function() {
        this.cols = 12;
        this.grid = new Grid($(".grid"), {
            header: this.cols,
            numeration: 7
        });
        addGridReferences(this);
    }
});

QUnit.test("Pressing shift must allow range selection", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$first, this.$third);

    assert.equal(this.grid.getSelection().length, 3, "Three cells should be selected");
});

QUnit.test("Range selection must start in current clicked cell if no other cell has been clicked before", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third);

    assert.equal(this.grid.getSelection().length, 1, "Only one cell should be selected");
    assert.ok(this.$third.isSelected(), "The starting point of range should be selected");
});

QUnit.test("Range selection must work across different rows", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$cells.eq(this.cols * 2 + 4));

    assert.equal(this.grid.getSelection().length, 2 * this.cols + 3, 2 * this.cols + 3 + " cells should be selected");
});

QUnit.test("Range selection must work across different rows, backwards", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$cells.eq(this.cols * 2 + 4), this.$third);

    assert.equal(this.grid.getSelection().length, 2 * this.cols + 3, 2 * this.cols + 3 + " cells should be selected");
});

QUnit.test("Stopping shift key pressing must deactivate rangeselection", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$first, this.$second);

    $(document).trigger(shiftUp);
    triggerClickRelatedEvents(this.$second);

    assert.equal(this.grid.getSelection().length, 1, "Only one cell should be selected");
});

QUnit.test("After starting rangeselection first bound must be anchored", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$second, this.$first);

    assert.equal(this.grid.getSelection().length, 3, "Three cells should be selected");
});

QUnit.test("After starting rangeselection first bound must be always anchored if selection change direction previous selection must be turned off", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$second, this.$first);

    triggerClickRelatedEvents(this.$fifth);

    assert.equal(this.grid.getSelection().length, 3, "Three cells should be selected");
    assert.ok(! this.$first.isSelected(), "First cell should be unselected");
    assert.ok(! this.$second.isSelected(), "Second cell should be unselected");
    assert.ok(this.$third.isSelected(), "Third cell should be selected");
    assert.ok(this.$fourth.isSelected(), "Fourth cell should be selected");
    assert.ok(this.$fifth.isSelected(), "Fifth cell should be selected");
});

QUnit.module("Unselecting cells", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
    }
});

QUnit.test("A click outside of the grid should unselect selected cells", function (assert) {
    var $target = this.grid.$cells.first();
    triggerClickRelatedEvents($target, $("#qunit-fixture"));

    assert.equal(this.grid.getSelection().length, 0, "There should be no selected cells");
});

QUnit.module("Dragging", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("Click holding and moving mouse should perform dragging selection", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 2, "There should be two selected cells");
});

QUnit.test("Releasing the click should stop dragging selection", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$second.trigger("mouseup").trigger("click");
    this.$third.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 2, "There should be two selected cells");

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    $("#qunit-fixture").trigger("mouseup");
    this.$third.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 2, "There should be two selected cells");
});

QUnit.test("New selections using dragging range selection should turn off previous selections", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    this.$third.trigger("mouseenter").trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(! this.$first.isSelected(), "First cell should be unselected");
    assert.ok(! this.$second.isSelected(), "Second cell should be unselected");

    assert.ok(this.$third.isSelected(), "Third cell should be selected");
    assert.ok(this.$fourth.isSelected(),"Fourth cell should be selected");
});

QUnit.test("New selections using dragging range selection should keep previous selections when control key is being held", function (assert) {
    var CTRL_CODE = 17, keyDown = jQuery.Event("keydown", { keyCode : CTRL_CODE });

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    $(document).trigger(keyDown);

    this.$fourth.trigger("mouseenter");
    this.$fifth.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(this.$first.isSelected(), "First cell should be selected");
    assert.ok(this.$second.isSelected(), "Second cell should be selected");
    assert.ok(! this.$third.isSelected(), "Third cell should be unselected");
    assert.ok(this.$fourth.isSelected(),"Fourth cell should be selected");
    assert.ok(this.$fifth.isSelected(), "Fifth cell should be selected");

    assert.equal(this.grid.getSelection().length, 4, "There should be 4 selected cells");
});

QUnit.test("Dragging selections must not have gaps of unselected cells", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 4, "Need four selected cells here");
    assert.ok(this.$first.isSelected(), "There a blank space in first cell");
    assert.ok(this.$second.isSelected(), "There a blank space in second cell");
    assert.ok(this.$third.isSelected(), "There a blank space in third cell");
    assert.ok(this.$fourth.isSelected(), "There a blank space in fourth cell");
});

QUnit.test("Dragging consecutive cells must selected all of them", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$third.trigger("mouseenter");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 4, "Need four selected cells here");
    assert.ok(this.$first.isSelected(), "There a blank space in first cell");
    assert.ok(this.$second.isSelected(), "There a blank space in second cell");
    assert.ok(this.$third.isSelected(), "There a blank space in third cell");
    assert.ok(this.$fourth.isSelected(), "There a blank space in fourth cell");
});

QUnit.test("When pressing shift the range selection must not be performed automatically, clicking the second cell must be needed", function (assert) {
    var SHIFT_CODE = 16, keyDown = jQuery.Event("keydown", { keyCode : SHIFT_CODE });

    this.$first.trigger("mousedown").trigger("mouseup").trigger("click");
    $(document).trigger(keyDown);
    this.$fourth.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 1, "Only first cell should be selected");
});

QUnit.test("When cursor retreat to a selected zone, the selection should retreat as well", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 2, "Only two cell should be selected");
    assert.ok(this.$first.isSelected(), "First cell should be selected");
    assert.ok(this.$second.isSelected(), "Second cell should be selected");
    assert.ok(! this.$fourth.isSelected(), "Fourth cell should be unselected");
});

QUnit.test("When cursor returned to the begin of the seleccion only the starting cell should be selected", function (assert) {
    this.$second.trigger("mousedown");
    this.$first.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 1, "Only second cell should be selected");
    assert.ok(this.$second.isSelected(), "Only second cell should be selected");
});

QUnit.test("When cursor leave the grid and return again the range should be from the cell under the cursor to starting point", function (assert) {
    this.$fourth.trigger("mousedown");
    this.$third.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    this.$second.trigger("mouseleave").trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 3, "Three cells should be selected");
    assert.ok(this.$second.isSelected(), "Second cell should be selected");
    assert.ok(this.$third.isSelected(), "Third cell should be selected");
    assert.ok(this.$fourth.isSelected(), "Fourth cell should be selected");
});

QUnit.test("If ctrl is held when cursor retreat to starting point current range should be reduced to one point keeping previous selections", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$last);

    this.$first.trigger("mousedown").trigger("mouseleave");
    this.$second.trigger("mouseenter").trigger("mouseleave");
    this.$first.trigger("mouseenter");

    assert.equal(this.grid.getSelection().length, 2, "Only two cells should be selected");
    assert.ok(this.$first.isSelected(), "First cell should be selected");
    assert.ok(! this.$second.isSelected(), "Second cell should be unselected");
    assert.ok(this.$last.isSelected(), "Last cell should be selected");
});

QUnit.module("Double click", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"), {
            header: 12,
            numeration: 7
        });
        addGridReferences(this);
    }
});

QUnit.test("Double clicking a cell allows whole row selection", function (assert) {
    this.$second.trigger("dblclick");
    assert.equal(this.grid.getSelection().length, 12, "Whole row should be selected");
});

QUnit.test("Double clicking a selected row unselect it completely", function (assert) {
    this.$second.trigger("dblclick");
    this.$second.trigger("dblclick");
    assert.equal(this.grid.getSelection().length, 0, "Whole row should be unselected");
});

QUnit.module("Complex interactions", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("When multiselection is inactive a new rangeselection must unselected all previously selected cells", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$first, this.$second);
    $(document).trigger(shiftUp);

    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$fourth);

    $(document).trigger(shiftDown).trigger(ctrlUp);
    triggerClickRelatedEvents(this.$fifth);

    assert.equal(this.grid.getSelection().length, 2, "Two cells should be selected");
    assert.ok(! this.$first.isSelected(), "First cell should be unselected");
    assert.ok(! this.$second.isSelected(), "Second cell should be unselected");
    assert.ok(this.$fourth.isSelected(), "Fourth cell should be selected");
    assert.ok(this.$fifth.isSelected(), "Fifth cell should be selected");
});

QUnit.test("When multiselection and rangeselection are active simultaneously, the selections must not shrink", function (assert) {
    $(document).trigger(shiftDown).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$third, this.$first, this.$fifth);

    assert.equal(this.grid.getSelection().length, 5, "Five cells should be selected");
    assert.ok(this.$first.isSelected(), "First cell should be selected");
    assert.ok(this.$second.isSelected(), "Second cell should be selected");
    assert.ok(this.$third.isSelected(), "Fourth cell should be selected");
    assert.ok(this.$fourth.isSelected(), "Fourth cell should be selected");
    assert.ok(this.$fifth.isSelected(), "Fifth cell should be selected");
});

QUnit.module("Header component", {
    beforeEach: function () {
        this.grid = new Grid($(".grid"), {
            header: [0, 2, 4, 6, 8],
            cols: 5
        });
    }
});

QUnit.test("header - data", function (assert) {
    assert.deepEqual(this.grid.header.data, [0, 2, 4, 6, 8]);
});

QUnit.test("header - get display", function (assert) {
    this.grid.header.data[2] = 10;
    assert.deepEqual(this.grid.header.display, [0, 2, 4, 6, 8]);
});

QUnit.test("header - get display after draw", function (assert) {
    this.grid.header.data[2] = 10;
    this.grid.header.draw();
    assert.deepEqual(this.grid.header.display, [0, 2, 10, 6, 8]);
});

QUnit.module("Numeration component", {
    beforeEach: function () {
        this.grid = new Grid($(".grid"), {
            numeration: [0, 2, 4, 6, 8],
            cols: 5
        });
    }
});

QUnit.test("numeration - data", function (assert) {
    assert.deepEqual(this.grid.numeration.data, [0, 2, 4, 6, 8]);
});

QUnit.test("numeration - get display", function (assert) {
    this.grid.numeration.data[2] = 10;
    assert.deepEqual(this.grid.numeration.display, [0, 2, 4, 6, 8]);
});

QUnit.test("numeration - get display after draw", function (assert) {
    this.grid.numeration.data[2] = 10;
    this.grid.numeration.draw();
    assert.deepEqual(this.grid.numeration.display, [0, 2, 10, 6, 8]);
});

QUnit.module("Destroying", {
    beforeEach: function () {
        this.grid = new Grid($(".grid"));
    }
});

QUnit.test("Destroy should remove all children of container", function (assert) {
    this.grid.destroy();
    assert.equal(this.grid.container.children().length, 0, "Should be no cells");
});

QUnit.test("Destroy should remove all event handlers attached to the container", function (assert) {
    sinon.stub(this.grid.container, "off");
    this.grid.destroy();
    assert.ok(this.grid.container.off.called);
});

QUnit.module("Some public methods", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("get_range must return the range between to given points of the grid", function (assert) {
    var range = this.grid.getRange(this.$first, this.$fifth);
    assert.ok(range.length, 5);
});

QUnit.test("get_range is also available from prototype", function (assert) {
    assert.equal(typeof Grid.prototype.getRange, "function");
});

QUnit.test("get selection should return a jquery wrapped set", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first, this.$last);

    var $selection = this.grid.getSelection();

    assert.ok(this.$first.is($selection), "first cell should be present in the set");
    assert.ok(this.$last.is($selection), "last cell should be present in the set");
    assert.equal($selection.length, 2), "only two cells should be present";
});

QUnit.module("Abstracting cells", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"), {
            numeration: 20
        });
        addGridReferences(this);
    }
});

QUnit.test("isSelected()", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first);

    assert.ok(this.grid.cell(0).isSelected());
    assert.notOk(this.grid.cell(1).isSelected());
});

QUnit.test("$rows property", function (assert) {
    assert.equal(this.grid.$rows.length, 20);
});

QUnit.test("accessing cells through rows", function (assert) {
    assert.strictEqual(this.grid.row(0).cell(0), this.grid.cell(0));
});

QUnit.test("Select allows to marks cells as selected", function (assert) {
    this.grid.select(0, 4);
    assert.equal(this.grid.getSelection().length, 5);
});
