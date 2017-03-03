QUnit.module("Initialization", {
    beforeEach: function() {
        this.$plan = initPlanSeptenalIndividual( $(".plan-septenal-individual").empty() );
    }
});

QUnit.test("Nine activity options must exists", function (assert) {
    assert.equal(this.$plan.find(".grid-action-btn").length, 9, "must be 9");
});

QUnit.module("Activity Assignment", {
    beforeEach: function() {
        this.$plan = initPlanSeptenalIndividual( $(".plan-septenal-individual").empty(), 2010);
        this.$sabatico_btn = $('.grid-action-btn').first();
        this.$beca_btn = $('.grid-action-btn').eq(4);
        this.$clear_btn = $('.grid-clear-btn').first();

        this.$elements = $(".grid-element");
        this.$first = $elements.first();
        this.$second = $elements.eq(1);
        this.$last = $elements.last();
    }
});

QUnit.test("When an action button is clicked selection must not vanish", function (assert) {
    this.$first.trigger('mousedown').trigger('mouseup').trigger("click");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected initially");

    this.$sabatico_btn.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger("click");
    assert.equal(this.$elements.filter('.selected').length, 1, "Only one element should be selected");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
});

QUnit.test("When an action button is clicked current selected elements must get data-tramite-type from the pressed button", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger('click');
    var type = this.$sabatico_btn.data('tramite-type');

    assert.strictEqual(this.$first.data('tramite-type'), type, "First element should have corresponding data-tramite-type");
    assert.strictEqual(this.$second.data('tramite-type'), type, "Second element should have corresponding data-tramite-type");
});

QUnit.test("When clear button is pressed selected elements should get an undefined data-tramite-type", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger('click');

    this.$clear_btn.trigger("click");

    assert.strictEqual(this.$first.data('tramite-type'), undefined, "First element should not have data-tramite-type");
    assert.strictEqual(this.$second.data('tramite-type'), undefined, "Second element should not have data-tramite-type");
});

QUnit.test("getSummary", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");

    this.$sabatico_btn.trigger("click");

    this.$first.removeClass("selected");
    this.$second.removeClass("selected");


    this.$last.addClass("selected");
    this.$beca_btn.trigger("click");

    var summary = getSummary(),
        expectedSummary = [
            {
                periodo: {
                    start: '01/2010',
                    end: '02/2010',
                },
                tipo: 'Sabático'
            },
            {
                periodo: {
                    start: '12/2016',
                    end: '12/2016',
                },
                tipo: 'Beca'
            }
        ];

    assert.deepEqual(summary, expectedSummary, "Summary must contain two tramites with its corresponding date ranges");
});
