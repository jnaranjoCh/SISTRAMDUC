var CTRL_CODE = 17, SHIFT_CODE = 16,
    ctrlDown = jQuery.Event("keydown", { keyCode : CTRL_CODE }),
    ctrlUp = jQuery.Event("keyup", { keyCode : CTRL_CODE }),
    shiftDown = jQuery.Event("keydown", { keyCode : SHIFT_CODE }),
    shiftUp = jQuery.Event("keyup", { keyCode : SHIFT_CODE });

function triggerClickRelatedEvents () {
    $targets = Array.prototype.slice.call(arguments);
    for (var i = 0; i < $targets.length; i++) {
        $targets[i].trigger("mouseenter").trigger("mousedown").trigger("mouseup").trigger("click");
    }
}

function addGridReferences (that) {
    that.$elements = that.grid.$elements;
    that.$first    = that.$elements.eq(0);
    that.$second   = that.$elements.eq(1);
    that.$third    = that.$elements.eq(2);
    that.$fourth   = that.$elements.eq(3);
    that.$fifth    = that.$elements.eq(4);
    that.$last     = that.$elements.last();
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
    assert.equal($(".grid-element").length, 84, "grid must have 84 elements");
});

QUnit.module("Single Selection", {
    beforeEach: function () {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("Click on an element must add class selected", function (assert) {
    triggerClickRelatedEvents(this.$first);
    assert.ok(this.$first.hasClass("selected"), "Clicked element must has class .selected");
});

QUnit.test("Click on an element must remove class selected from all elements", function (assert) {
    this.$elements.addClass("selected");
    triggerClickRelatedEvents(this.$first);

    assert.equal(this.$elements.filter(".selected").length, 1, "Class .selected must be only in clicked element");
});

QUnit.test("Holding click over an element should keep it selected", function (assert) {
    this.$first.trigger("mousedown");
    assert.ok(this.$first.hasClass("selected"), "Clicked element must has class .selected");
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

    assert.equal(this.$elements.filter(".selected").length, 2, "There should be two selected elements");
});

QUnit.test("Releasing ctrl key must deactivate multiselection", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first, this.$last);

    $(document).trigger(ctrlUp);
    triggerClickRelatedEvents(this.$first);

    assert.equal(this.$elements.filter(".selected").length, 1, "There should be two selected elements");
});

QUnit.test("When user is multiselecting class selected must be toggled in clicked elements", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$first);
    assert.equal(this.$elements.filter(".selected").length, 1, "There should be one selected elements");

    triggerClickRelatedEvents(this.$first);
    assert.equal(this.$elements.filter(".selected").length, 0, "There should be one selected elements");
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

    assert.equal(this.$elements.filter(".selected").length, 3, "Three elements should be selected");
});

QUnit.test("Range selection must start in current clicked element if no other element has been clicked before", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third);

    assert.equal(this.$elements.filter(".selected").length, 1, "Only one element should be selected");
    assert.ok(this.$third.hasClass("selected"), "The starting point of range should be selected");
});

QUnit.test("Range selection must work across different rows", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$elements.eq(this.cols * 2 + 4));

    assert.equal(this.$elements.filter(".selected").length, 2 * this.cols + 3, 2 * this.cols + 3 + " elements should be selected");
});

QUnit.test("Range selection must work across different rows, backwards", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$elements.eq(this.cols * 2 + 4), this.$third);

    assert.equal(this.$elements.filter(".selected").length, 2 * this.cols + 3, 2 * this.cols + 3 + " elements should be selected");
});

QUnit.test("Stopping shift key pressing must deactivate rangeselection", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$first, this.$second);

    $(document).trigger(shiftUp);
    triggerClickRelatedEvents(this.$second);

    assert.equal(this.$elements.filter(".selected").length, 1, "Only one element should be selected");
});

QUnit.test("After starting rangeselection first bound must be anchored", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$second, this.$first);

    assert.equal(this.$elements.filter(".selected").length, 3, "Three elements should be selected");
});

QUnit.test("After starting rangeselection first bound must be always anchored if selection change direction previous selection must be turned off", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$third, this.$second, this.$first);

    triggerClickRelatedEvents(this.$fifth);

    assert.equal(this.$elements.filter(".selected").length, 3, "Three elements should be selected");
    assert.ok(! this.$first.hasClass("selected"), "First element should be unselected");
    assert.ok(! this.$second.hasClass("selected"), "Second element should be unselected");
    assert.ok(this.$third.hasClass("selected"), "Third element should be selected");
    assert.ok(this.$fourth.hasClass("selected"), "Fourth element should be selected");
    assert.ok(this.$fifth.hasClass("selected"), "Fifth element should be selected");
});

QUnit.module("Unselecting elements", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
    }
});

QUnit.test("A click outside of the grid should unselect selected elements", function (assert) {
    var $elements = $(".grid-element"), $target = $elements.first();
    triggerClickRelatedEvents($target, $("#qunit-fixture"));

    assert.equal($elements.filter(".selected").length, 0, "There should be no selected elements");
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

    assert.equal(this.$elements.filter(".selected").length, 2, "There should be two selected elements");
});

QUnit.test("Releasing the click should stop dragging selection", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$second.trigger("mouseup").trigger("click");
    this.$third.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 2, "There should be two selected elements");

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    $("#qunit-fixture").trigger("mouseup");
    this.$third.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 2, "There should be two selected elements");
});

QUnit.test("New selections using dragging range selection should turn off previous selections", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    this.$third.trigger("mouseenter").trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(! this.$first.hasClass("selected"), "First element should be unselected");
    assert.ok(! this.$second.hasClass("selected"), "Second element should be unselected");

    assert.ok(this.$third.hasClass("selected"), "Third element should be selected");
    assert.ok(this.$fourth.hasClass("selected"),"Fourth element should be selected");
});

QUnit.test("New selections using dragging range selection should keep previous selections when control key is being held", function (assert) {
    var CTRL_CODE = 17, keyDown = jQuery.Event("keydown", { keyCode : CTRL_CODE });

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    $(document).trigger(keyDown);

    this.$fourth.trigger("mouseenter");
    this.$fifth.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(this.$first.hasClass("selected"), "First element should be selected");
    assert.ok(this.$second.hasClass("selected"), "Second element should be selected");
    assert.ok(! this.$third.hasClass("selected"), "Third element should be unselected");
    assert.ok(this.$fourth.hasClass("selected"),"Fourth element should be selected");
    assert.ok(this.$fifth.hasClass("selected"), "Fifth element should be selected");

    assert.equal(this.$elements.filter(".selected").length, 4, "There should be 4 selected elements");
});

QUnit.test("Dragging selections must not have gaps of unselected elements", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 4, "Need four selected elements here");
    assert.ok(this.$first.hasClass("selected"), "There a blank space in first element");
    assert.ok(this.$second.hasClass("selected"), "There a blank space in second element");
    assert.ok(this.$third.hasClass("selected"), "There a blank space in third element");
    assert.ok(this.$fourth.hasClass("selected"), "There a blank space in fourth element");
});

QUnit.test("Dragging consecutive elements must selected all of them", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$third.trigger("mouseenter");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 4, "Need four selected elements here");
    assert.ok(this.$first.hasClass("selected"), "There a blank space in first element");
    assert.ok(this.$second.hasClass("selected"), "There a blank space in second element");
    assert.ok(this.$third.hasClass("selected"), "There a blank space in third element");
    assert.ok(this.$fourth.hasClass("selected"), "There a blank space in fourth element");
});

QUnit.test("When pressing shift the range selection must not be performed automatically, clicking the second element must be needed", function (assert) {
    var SHIFT_CODE = 16, keyDown = jQuery.Event("keydown", { keyCode : SHIFT_CODE });

    this.$first.trigger("mousedown").trigger("mouseup").trigger("click");
    $(document).trigger(keyDown);
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 1, "Only first element should be selected");
});

QUnit.test("When cursor retreat to a selected zone, the selection should retreat as well", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 2, "Only two element should be selected");
    assert.ok(this.$first.hasClass("selected"), "First element should be selected");
    assert.ok(this.$second.hasClass("selected"), "Second element should be selected");
    assert.ok(! this.$fourth.hasClass("selected"), "Fourth element should be unselected");
});

QUnit.test("When cursor returned to the begin of the seleccion only the starting element should be selected", function (assert) {
    this.$second.trigger("mousedown");
    this.$first.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 1, "Only second element should be selected");
    assert.ok(this.$second.hasClass("selected"), "Only second element should be selected");
});

QUnit.test("When cursor leave the grid and return again the range should be from the element under the cursor to starting point", function (assert) {
    this.$fourth.trigger("mousedown");
    this.$third.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    this.$second.trigger("mouseleave").trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 3, "Three elements should be selected");
    assert.ok(this.$second.hasClass("selected"), "Second element should be selected");
    assert.ok(this.$third.hasClass("selected"), "Third element should be selected");
    assert.ok(this.$fourth.hasClass("selected"), "Fourth element should be selected");
});

QUnit.test("If ctrl is held when cursor retreat to starting point current range should be reduced to one point keeping previous selections", function (assert) {
    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$last);

    this.$first.trigger("mousedown").trigger("mouseleave");
    this.$second.trigger("mouseenter").trigger("mouseleave");
    this.$first.trigger("mouseenter");

    assert.equal(this.$elements.filter(".selected").length, 2, "Only two elements should be selected");
    assert.ok(this.$first.hasClass("selected"), "First element should be selected");
    assert.ok(! this.$second.hasClass("selected"), "Second element should be unselected");
    assert.ok(this.$last.hasClass("selected"), "Last element should be selected");
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

QUnit.test("Double clicking an element allows whole row selection", function (assert) {
    this.$second.trigger("dblclick");
    assert.equal(this.$elements.filter(".selected").length, 12, "Whole row should be selected");
});

QUnit.test("Double clicking a selected row unselect it completely", function (assert) {
    this.$second.trigger("dblclick");
    this.$second.trigger("dblclick");
    assert.equal(this.$elements.filter(".selected").length, 0, "Whole row should be unselected");
});

QUnit.module("Complex interactions", {
    beforeEach: function() {
        this.grid = new Grid($(".grid"));
        addGridReferences(this);
    }
});

QUnit.test("When multiselection is inactive a new rangeselection must unselected all previously selected elements", function (assert) {
    $(document).trigger(shiftDown);
    triggerClickRelatedEvents(this.$first, this.$second);
    $(document).trigger(shiftUp);

    $(document).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$fourth);

    $(document).trigger(shiftDown).trigger(ctrlUp);
    triggerClickRelatedEvents(this.$fifth);

    assert.equal(this.$elements.filter(".selected").length, 2, "Two elements should be selected");
    assert.ok(! this.$first.hasClass("selected"), "First element should be unselected");
    assert.ok(! this.$second.hasClass("selected"), "Second element should be unselected");
    assert.ok(this.$fourth.hasClass("selected"), "Fourth element should be selected");
    assert.ok(this.$fifth.hasClass("selected"), "Fifth element should be selected");
});

QUnit.test("When multiselection and rangeselection are active simultaneously, the selections must not shrink", function (assert) {
    $(document).trigger(shiftDown).trigger(ctrlDown);
    triggerClickRelatedEvents(this.$third, this.$first, this.$fifth);

    assert.equal(this.$elements.filter(".selected").length, 5, "Five elements should be selected");
    assert.ok(this.$first.hasClass("selected"), "First element should be selected");
    assert.ok(this.$second.hasClass("selected"), "Second element should be selected");
    assert.ok(this.$third.hasClass("selected"), "Fourth element should be selected");
    assert.ok(this.$fourth.hasClass("selected"), "Fourth element should be selected");
    assert.ok(this.$fifth.hasClass("selected"), "Fifth element should be selected");
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
    assert.equal(this.grid.container.children().length, 0, "Should be no elements");
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

    $selection = this.grid.getSelection();

    assert.ok(this.$first.is($selection), "first element should be present in the set");
    assert.ok(this.$last.is($selection), "last element should be present in the set");
    assert.equal($selection.length, 2), "only two elements should be present";
});
