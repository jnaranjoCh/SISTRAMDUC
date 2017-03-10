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
        this.$firstYear = 2010;
        this.$plan = initPlanSeptenalIndividual( $(".plan-septenal-individual").empty(), this.$firstYear);
        this.$sabatico_btn = $('.grid-action-btn').first();
        this.$licencia_btn = $('.grid-action-btn').eq(3);
        this.$beca_btn = $('.grid-action-btn').eq(4);
        this.$clear_btn = $('.grid-clear-btn').first();

        this.$elements = $(".grid-element");
        this.$first = $elements.first();
        this.$second = $elements.eq(1);
        this.$third = $elements.eq(2);
        this.$last = $elements.last();

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("When an action button is clicked selection must not vanish", function (assert) {
    this.$first.trigger('mousedown').trigger('mouseup').trigger("click");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected initially");

    this.$sabatico_btn.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger("click");
    assert.equal(this.$elements.filter('.selected').length, 1, "Only one element should be selected");
    assert.ok(this.$first.hasClass('selected'), "First element should be selected");
});

QUnit.test("When an action button is clicked current selected elements must get data-tramite-type and background-color from the pressed button", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger("click");
    var type = this.$sabatico_btn.data('tramite-type');

    assert.strictEqual(this.$first.data('tramite-type'), type, "First element should have corresponding data-tramite-type");
    assert.equal(this.$first.css('background'), this.$sabatico_btn.css('background'), "First element should have the corresponding background-color");

    assert.strictEqual(this.$second.data('tramite-type'), type, "Second element should have corresponding data-tramite-type");
    assert.equal(this.$second.css('background'), this.$sabatico_btn.css('background'), "Second element should have the corresponding background-color");
});

QUnit.test("When clear button is pressed selected elements should get an undefined data-tramite-type and lose their background color", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger("click");

    this.$clear_btn.trigger('mouseenter').trigger('mousedown').trigger('mouseup').trigger("click");

    assert.strictEqual(this.$first.data('tramite-type'), undefined, "First element should not have data-tramite-type");
    assert.notEqual(this.$first.css('background'), this.$sabatico_btn.css('background'), "First element should not have background-color of tramite");
    assert.strictEqual(this.$second.data('tramite-type'), undefined, "Second element should not have data-tramite-type");
    assert.notEqual(this.$second.css('background'), this.$sabatico_btn.css('background'), "Second element should not have background-color of tramite");
});

QUnit.test("getSummary()", function (assert) {
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger("click");
    this.$first.removeClass("selected");
    this.$second.removeClass("selected");

    this.$third.addClass("selected");
    this.$licencia_btn.trigger("click");
    this.$third.removeClass("selected");

    this.$last.addClass("selected");
    this.$beca_btn.trigger("click");

    var expectedSummary = {
            inicio: this.$firstYear,
            fin: this.$firstYear + 6,
            tramites: [
                {
                    tipo: 'Sabático',
                    periodo: {
                        start: '01/2010',
                        end: '02/2010',
                    }
                },
                {
                    tipo: 'Licencia Remunerada',
                    periodo: {
                        start: '03/2010',
                        end: '03/2010'
                    }
                },
                {
                    tipo: 'Beca',
                    periodo: {
                        start: '12/2016',
                        end: '12/2016',
                    }
                }
            ]
        };

    assert.deepEqual(getSummary(), expectedSummary, "Summary must be properly structured");
});

QUnit.test("Set state should put the grid in a given state", function (assert) {
    var state = {
        inicio: 2000,
        fin: 2006,
        tramites: [
            {
                tipo: 'Sabático',
                periodo: {
                    start: '01/2000',
                    end: '02/2000',
                }
            },
            {
                tipo: 'Licencia Remunerada',
                periodo: {
                    start: '03/2000',
                    end: '03/2000'
                }
            },
            {
                tipo: 'Beca',
                periodo: {
                    start: '12/2006',
                    end: '12/2006',
                }
            }
        ]
    }

    setState(state, $plan);

    assert.deepEqual(getSummary(), state);
});

QUnit.test("saveChanges()", function (assert) {
    sinon.stub($, 'ajax');
    this.$first.addClass("selected");
    this.$second.addClass("selected");
    this.$sabatico_btn.trigger("click");

    var data = {
        inicio: this.$firstYear,
        fin: this.$firstYear + 6,
        tramites: [
            {
                tipo: 'Sabático',
                periodo: {
                    start: '01/2010',
                    end: '02/2010',
                }
            }
        ]
    };

    saveChanges();

    assert.ok($.ajax.calledWithMatch({ url: $plan.data('route'), data: data, method: 'POST' }), "Ajax request must be correctly formed");

    $.ajax.restore();
});

QUnit.test("Summit button must call saveChanges method", function (assert) {
    assert.expect(1);

    var real_saveChanges = window.saveChanges;

    window.saveChanges = function () { assert.ok(1); }
    $('<button type="submit">').appendTo( this.$plan ).trigger('click');

    window.saveChanges = real_saveChanges;
    // this shouldn't trigger the assertion because the function was restored
    $('<button type="submit">').appendTo( this.$plan ).trigger('click');
});

QUnit.test("On successful save, a message should be displayed", function (assert) {
    this.server.respondWith([200, {}, ""]);

    sinon.stub(toastr, 'success');

    $('<button type="submit">').appendTo( this.$plan ).trigger('click');

    assert.ok(toastr["success"].calledWith("Los cambios han sido guardados"), "A success message is necessary here");

    toastr['success'].restore();
});

QUnit.test("On server error, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);

    sinon.stub(toastr, 'error');

    $('<button type="submit">').appendTo( this.$plan ).trigger('click');

    assert.ok(toastr["error"].calledWith("Ocurrió un error. En caso de que el problema persista contacte a soporte"), "An error message is necessary here");

    toastr['error'].restore();
});

QUnit.test("getPlan must request the plan septenal individual to the server", function (assert) {
    sinon.stub($, 'ajax');

    var data = {
        inicio: 2010,
        fin: 2016
    };

    getPlan(data.inicio, data.fin);

    assert.ok($.ajax.calledWithMatch({ url: $plan.data('route'), data: data, method: 'GET' }), "Ajax request must be correctly formed");

    $.ajax.restore();
});

QUnit.test("On successful get, a message should be displayed", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(toastr, 'success');

    getPlan(2010, 2016);

    assert.ok(toastr["success"].calledWith("Plan septenal cargado satisfactoriamente"), "A success message is necessary here");

    toastr['success'].restore();
});

QUnit.test("On server error while requestinng plan, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, 'error');

    getPlan(2010, 2016);

    assert.ok(
        toastr["error"].calledWith(
            "Ocurrió un error al intentar cargar el plan septenal. En caso de que el problema persista contacte a soporte"
        ),
        "An error message is necessary here"
    );

    toastr['error'].restore();
});
