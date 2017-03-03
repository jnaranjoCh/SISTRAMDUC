QUnit.test("Create grid appropriately", function (assert) {
    initGrid($('.grid'), 12, 7);
    assert.equal($(".grid-element").length, 84, "grid must have 84 elements");
});

QUnit.module("Single Selection", {
    beforeEach: function() {
        initGrid( $(".grid").empty() );
        this.$elements = $(".grid-element");
        this.$first = $elements.first();
        this.$last = $elements.last();
    }
});

QUnit.test("Click on an element must add class selected", function (assert) {
    this.$first.trigger("mousedown").trigger("mouseup").trigger("click");
    assert.ok(this.$first.hasClass("selected"), "Clicked element must has class .selected");
});

QUnit.test("Click on an element must remove class selected from all elements", function (assert) {
    this.$elements.addClass("selected");
    this.$first.trigger("mousedown").trigger("mouseup").trigger("click");
    assert.equal(this.$elements.filter(".selected").length, 1, "Class .selected must be only in clicked element");
});

QUnit.test("Holding click over an element should keep it selected", function (assert) {
    this.$first.trigger("mousedown");
    assert.ok(this.$first.hasClass("selected"), "Clicked element must has class .selected");
});

QUnit.module( "Multiselection", {
    beforeEach: function() {
        initGrid($(".grid").empty());
        this.$elements = $(".grid-element");
        this.$first = $elements.first();
        this.$last = $elements.last();
        this.CTRL_CODE = 17;
        ctrlDown = jQuery.Event("keydown", { keyCode : this.CTRL_CODE });
    }
});

QUnit.test("Pressing ctrl key must allow multiselection", function (assert) {
    $(document).trigger(ctrlDown);
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$last.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal($elements.filter(".selected").length, 2, "There should be two selected elements");
});

QUnit.test("Releasing ctrl key must deactivate multiselection", function (assert) {
    $(document).trigger(ctrlDown);
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$last.trigger("mousedown").trigger("mouseup").trigger('click');

    var ctrlUp = jQuery.Event("keyup", { keyCode : this.CTRL_CODE });
    $(document).trigger(ctrlUp);
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal(this.$elements.filter(".selected").length, 1, "There should be two selected elements");
});

QUnit.test("When user is multiselecting class selected must be toggled in clicked elements", function (assert) {
    $(document).trigger(ctrlDown);
    this.$first.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger('click');
    assert.equal(this.$elements.filter(".selected").length, 1, "There should be one selected elements");

    this.$first.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger('click');
    assert.equal(this.$elements.filter(".selected").length, 0, "There should be one selected elements");
});


QUnit.module("Rangeselection", {
    beforeEach: function() {
        this.cols = 12;
        initGrid( $(".grid").empty(), this.cols , 3 );
        this.$elements = $(".grid-element");
        this.$first = $elements.eq(0);
        this.$second = $elements.eq(1);
        this.$third = $elements.eq(2);
        this.SHIFT_CODE = 16;
        this.shiftDown = jQuery.Event("keydown", { keyCode : this.SHIFT_CODE });
        this.shiftUp = jQuery.Event("keyup", { keyCode : SHIFT_CODE });
    }
});

QUnit.test("Pressing shift must allow range selection", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$first.trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');
    this.$third.trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');

    assert.equal(this.$elements.filter(".selected").length, 3, "Three elements should be selected");
});

QUnit.test("Range selection must start in current clicked element if no other element has been clicked before", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$third.trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');

    assert.equal(this.$elements.filter(".selected").length, 1, "Only one element should be selected");
    assert.ok(this.$third.hasClass("selected"), "The starting point of range should be selected");
});

QUnit.test("Range selection must work across different rows", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$third.trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');
    this.$elements.eq(this.cols * 2 + 4).trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');

    assert.equal(this.$elements.filter(".selected").length, 2 * this.cols + 3, 2 * this.cols + 3 + " elements should be selected");
});

QUnit.test("Range selection must work across different rows, backwards", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$elements.eq(this.cols * 2 + 4).trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');
    this.$third.trigger('mouseenter').trigger('mousedown').trigger('click').trigger('mouseup');

    assert.equal(this.$elements.filter(".selected").length, 2 * this.cols + 3, 2 * this.cols + 3 + " elements should be selected");
});

QUnit.test("Stopping shift key pressing must deactivate rangeselection", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$second.trigger("mousedown").trigger("mouseup").trigger('click');

    $(document).trigger(this.shiftUp);
    this.$second.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal(this.$elements.filter(".selected").length, 1, "Only one element should be selected");
});

QUnit.test("After starting rangeselection first bound must be anchored", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$third.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$second.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal($elements.filter(".selected").length, 3, "Three elements should be selected");
});

QUnit.test("After starting rangeselection first bound must be always anchored if selection change direction previous selection must be turned off", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$third.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$second.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');

    var $fourth = this.$elements.eq(3);
    var $fifth = this.$elements.eq(4);

    $fifth.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal($elements.filter(".selected").length, 3, "Three elements should be selected");
    assert.ok(! this.$first.hasClass('selected'), "First element should be unselected");
    assert.ok(! this.$second.hasClass('selected'), "Second element should be unselected");
    assert.ok(this.$third.hasClass('selected'), "Third element should be selected");
    assert.ok($fourth.hasClass('selected'), "Fourth element should be selected");
    assert.ok($fifth.hasClass('selected'), "Fifth element should be selected");
});

QUnit.module("Unselecting elements", {
    beforeEach: function() {
        initGrid( $(".grid").empty() );
    }
});

QUnit.test("A click outside of the grid should unselect selected elements", function (assert) {
    var $elements = $(".grid-element"), $target = $elements.first();

    $target.trigger("mousedown").trigger("mouseup").trigger("click");
    $('#qunit-fixture').trigger("mousedown").trigger("mouseup").trigger("click");

    assert.equal($elements.filter(".selected").length, 0, "There should be no selected elements");
});

QUnit.module("Dragging", {
    beforeEach: function() {
        initGrid( $(".grid").empty() );
        this.$elements = $(".grid-element");
        this.$first = $elements.eq(0);
        this.$second = $elements.eq(1);
        this.$third = $elements.eq(2);
        this.$fourth = $elements.eq(3);
        this.$fifth = $elements.eq(4);
        this.$last = $elements.last();
    }
});

QUnit.test("Click holding and moving mouse should perform dragging selection", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 2, "There should be two selected elements");
});

QUnit.test("Releasing the click should stop dragging selection", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$second.trigger("mouseup").trigger("click");
    this.$third.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 2, "There should be two selected elements");

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    $('#qunit-fixture').trigger('mouseup');
    this.$third.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 2, "There should be two selected elements");
});

QUnit.test("New selections using dragging range selection should turn off previous selections", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    this.$third.trigger("mouseenter").trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(! this.$first.hasClass('selected'), "First element should be unselected");
    assert.ok(! this.$second.hasClass('selected'), "Second element should be unselected");

    assert.ok(this.$third.hasClass('selected'), "Third element should be selected");
    assert.ok(this.$fourth.hasClass('selected'),"Fourth element should be selected");
});

QUnit.test("New selections using dragging range selection should keep previous selections when control key is being held", function (assert) {
    var CTRL_CODE = 17, keyDown = jQuery.Event("keydown", { keyCode : CTRL_CODE });

    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter").trigger("mouseup").trigger("click");

    $(document).trigger(keyDown);

    this.$fourth.trigger("mouseenter");
    this.$fifth.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
    assert.ok(this.$second.hasClass('selected'), "Second element should be selected");
    assert.ok(! this.$third.hasClass('selected'), "Third element should be unselected");
    assert.ok(this.$fourth.hasClass('selected'),"Fourth element should be selected");
    assert.ok(this.$fifth.hasClass('selected'), "Fifth element should be selected");

    assert.equal(this.$elements.filter('.selected').length, 4, "There should be 4 selected elements");
});

QUnit.test("Dragging selections must not have gaps of unselected elements", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 4, "Need four selected elements here");
    assert.ok(this.$first.hasClass('selected'), "There a blank space in first element");
    assert.ok(this.$second.hasClass('selected'), "There a blank space in second element");
    assert.ok(this.$third.hasClass('selected'), "There a blank space in third element");
    assert.ok(this.$fourth.hasClass('selected'), "There a blank space in fourth element");
});

QUnit.test("Dragging consecutive elements must selected all of them", function (assert) {
    this.$first.trigger("mousedown");
    this.$second.trigger("mouseenter");
    this.$third.trigger("mouseenter");
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 4, "Need four selected elements here");
    assert.ok(this.$first.hasClass('selected'), "There a blank space in first element");
    assert.ok(this.$second.hasClass('selected'), "There a blank space in second element");
    assert.ok(this.$third.hasClass('selected'), "There a blank space in third element");
    assert.ok(this.$fourth.hasClass('selected'), "There a blank space in fourth element");
});

QUnit.test("When pressing shift the range selection must not be performed automatically, clicking the second element must be needed", function (assert) {
    var SHIFT_CODE = 16, keyDown = jQuery.Event("keydown", { keyCode : SHIFT_CODE });

    this.$first.trigger("mousedown").trigger("mouseup").trigger("click");
    $(document).trigger(keyDown);
    this.$fourth.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 1, "Only first element should be selected");
});

QUnit.test("When cursor retreat to a selected zone, the selection should retreat as well", function (assert) {
    this.$first.trigger("mousedown");
    this.$fourth.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 2, "Only two element should be selected");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
    assert.ok(this.$second.hasClass('selected'), "Second element should be selected");
    assert.ok(! this.$fourth.hasClass('selected'), "Fourth element should be unselected");
});

QUnit.test("When cursor returned to the begin of the seleccion only the starting element should be selected", function (assert) {
    this.$second.trigger("mousedown");
    this.$first.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 1, "Only second element should be selected");
    assert.ok(this.$second.hasClass('selected'), "Only second element should be selected");
});

QUnit.test("When cursor leave the grid and return again the range should be from the element under the cursor to starting point", function (assert) {
    this.$fourth.trigger("mousedown");
    this.$third.trigger("mouseenter");
    this.$second.trigger("mouseenter");

    this.$second.trigger("mouseleave").trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 3, "Three elements should be selected");
    assert.ok(this.$second.hasClass('selected'), "Second element should be selected");
    assert.ok(this.$third.hasClass('selected'), "Third element should be selected");
    assert.ok(this.$fourth.hasClass('selected'), "Fourth element should be selected");
});

QUnit.test("If ctrl is held when cursor retreat to starting point current range should be reduced to one point keeping previous selections", function (assert) {
    var CTRL_CODE = 17, keyDown = jQuery.Event("keydown", { keyCode : CTRL_CODE });

    $(document).trigger(keyDown);
    this.$last.trigger("mousedown").trigger("mouseup").trigger("click");

    this.$first.trigger("mousedown").trigger("mouseleave");
    this.$second.trigger("mouseenter").trigger("mouseleave");
    this.$first.trigger("mouseenter");

    assert.equal(this.$elements.filter('.selected').length, 2, "Only two elements should be selected");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
    assert.ok(! this.$second.hasClass('selected'), "Second element should be unselected");
    assert.ok(this.$last.hasClass('selected'), "Last element should be selected");
});

QUnit.module("Double click", {
    beforeEach: function() {
        initGrid( $(".grid").empty(), 12 );
        this.$elements = $(".grid-element");
        this.$second = $elements.eq(1);
    }
});

QUnit.test("Double clicking an element allows whole row selection", function (assert) {
    this.$second.trigger("dblclick");
    assert.equal(this.$elements.filter('.selected').length, 12, "Whole row should be selected");
});

QUnit.test("Double clicking a selected row unselect it completely", function (assert) {
    this.$second.trigger("dblclick");
    this.$second.trigger("dblclick");
    assert.equal(this.$elements.filter('.selected').length, 0, "Whole row should be selected");
});


QUnit.module("Complex interactions", {
    beforeEach: function() {
        initGrid( $(".grid").empty() );
        this.$elements = $(".grid-element");
        this.$first = $elements.eq(0);
        this.$second = $elements.eq(1);
        this.$third = $elements.eq(2);
        this.$fourth = $elements.eq(3);
        this.$fifth = $elements.eq(4);
        this.$last = $elements.last();
        this.CTRL_CODE = 17;
        this.ctrlDown = jQuery.Event("keydown", { keyCode : this.CTRL_CODE });
        this.ctrlUp = jQuery.Event("keyup", { keyCode : CTRL_CODE });
        this.SHIFT_CODE = 16;
        this.shiftDown = jQuery.Event("keydown", { keyCode : this.SHIFT_CODE });
        this.shiftUp = jQuery.Event("keyup", { keyCode : SHIFT_CODE });
    }
});

QUnit.test("When multiselection is inactive a new rangeselection must unselected all previously selected elements", function (assert) {
    $(document).trigger(this.shiftDown);
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$second.trigger("mousedown").trigger("mouseup").trigger('click');
    $(document).trigger(this.shiftUp);

    $(document).trigger(this.ctrlDown);
    this.$fourth.trigger("mousedown").trigger("mouseup").trigger('click');

    $(document).trigger(this.shiftDown).trigger(this.ctrlUp);
    this.$fifth.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal(this.$elements.filter(".selected").length, 2, "Two elements should be selected");
    assert.ok(! this.$first.hasClass('selected'), "First element should be unselected");
    assert.ok(! this.$second.hasClass('selected'), "Second element should be unselected");
    assert.ok(this.$fourth.hasClass('selected'), "Fourth element should be selected");
    assert.ok(this.$fifth.hasClass('selected'), "Fifth element should be selected");
});

QUnit.test("When multiselection and rangeselection are active simultaneously, the selections must not shrink", function (assert) {
    $(document).trigger(this.shiftDown).trigger(this.ctrlDown);
    this.$third.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$first.trigger("mousedown").trigger("mouseup").trigger('click');
    this.$fifth.trigger("mousedown").trigger("mouseup").trigger('click');

    assert.equal(this.$elements.filter(".selected").length, 5, "Five elements should be selected");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
    assert.ok(this.$second.hasClass('selected'), "Second element should be selected");
    assert.ok(this.$third.hasClass('selected'), "Fourth element should be selected");
    assert.ok(this.$fourth.hasClass('selected'), "Fourth element should be selected");
    assert.ok(this.$fifth.hasClass('selected'), "Fifth element should be selected");
});
