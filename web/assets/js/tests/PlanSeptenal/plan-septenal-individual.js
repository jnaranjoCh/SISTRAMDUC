function triggerClickRelatedEvents () {
    $targets = Array.prototype.slice.call(arguments);
    for (var i = 0; i < $targets.length; i++) {
        $targets[i].trigger("mouseenter").trigger("mousedown").trigger("mouseup").trigger("click");
    }
}

QUnit.module("Initialization", {
    beforeEach: function() {
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual") , 2010);
    }
});

QUnit.test("Nine activity options must exists", function (assert) {
    assert.equal(this.$plan.container.find(".grid-action-btn").length, 9, "must be 9");
});

QUnit.test("container is mandatory during PlanSeptenalIndividual creation", function (assert) {
    assert.throws(
        function () {
            var p = new PlanSeptenalIndividual();
        },
        /Container/
    );
});

QUnit.test("starting year is mandatory during PlanSeptenalIndividual creation", function (assert) {
    assert.throws(
        function () {
            var p = new PlanSeptenalIndividual($('<div>'), 'x');
        },
        /Starting year/
    );
});

QUnit.module("Activity Assignment", {
    beforeEach: function() {
        this.$firstYear = 2010;
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual"), this.$firstYear);
        // maybe I should expose the controls
        this.$sabatico_btn = $('.grid-action-btn').first();
        this.$licencia_btn = $('.grid-action-btn').eq(3);
        this.$beca_btn = $('.grid-action-btn').eq(4);
        this.$clear_btn = $('.grid-clear-btn').first();

        this.$first  = this.$plan.grid.cell(0);
        this.$second = this.$plan.grid.cell(1);
        this.$third  = this.$plan.grid.cell(3);
        this.$last   = this.$plan.grid.cell(83);

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;

        this.sample_state =  {
            inicio: this.$firstYear,
            fin: this.$firstYear + 6,
            tramites: [
                {
                    tipo: TRAMITES[0].name,
                    periodo: {
                        start: '01/2010',
                        end: '02/2010',
                    }
                },
                {
                    tipo: TRAMITES[1].name,
                    periodo: {
                        start: '03/2010',
                        end: '03/2010'
                    }
                },
                {
                    tipo: TRAMITES[2].name,
                    periodo: {
                        start: '12/2016',
                        end: '12/2016',
                    }
                }
            ]
        };
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("When an action button is clicked selection must not vanish", function (assert) {
    this.$plan.select('01/2010', '01/2010');
    assert.ok(this.$first.isSelected(), "First cell should be selected initially");

    triggerClickRelatedEvents(this.$sabatico_btn);
    assert.equal(this.$plan.getSelection().length, 1, "Only one cell should be selected");
    assert.ok(this.$first.isSelected(), "First cell should be selected");
});

QUnit.test("When an action button is clicked current selected cells must get data-tramite-type and background-color from the pressed button", function (assert) {
    this.$plan.select('01/2010', '02/2010');
    triggerClickRelatedEvents(this.$sabatico_btn);

    var btn_type = this.$sabatico_btn.data('tramite-type'),
        btn_bg = this.$sabatico_btn.css('background');

    assert.strictEqual(this.$first.data('tramite-type'), btn_type, "First element should have corresponding data-tramite-type");
    assert.equal(this.$first.css('background'), btn_bg, "First element should have the corresponding background-color");

    assert.strictEqual(this.$second.data('tramite-type'), btn_type, "Second element should have corresponding data-tramite-type");
    assert.equal(this.$second.css('background'), btn_bg, "Second element should have the corresponding background-color");
});

QUnit.test("When clear button is pressed selected elements should get an undefined data-tramite-type and lose their background color", function (assert) {
    this.$plan.select('01/2010', '02/2010');
    triggerClickRelatedEvents(this.$sabatico_btn);
    triggerClickRelatedEvents(this.$clear_btn);

    assert.strictEqual(this.$first.data('tramite-type'), undefined, "First element should not have data-tramite-type");
    assert.notEqual(this.$first.css('background'), this.$sabatico_btn.css('background'), "First element should not have background-color of tramite");
    assert.strictEqual(this.$second.data('tramite-type'), undefined, "Second element should not have data-tramite-type");
    assert.notEqual(this.$second.css('background'), this.$sabatico_btn.css('background'), "Second element should not have background-color of tramite");
});

QUnit.test("getState()", function (assert) {
    this.$plan.assignTramiteToRange(0, this.$plan.getRange('01/2010', '02/2010'));
    this.$plan.assignTramiteToRange(1, this.$plan.getRange('03/2010', '03/2010'));
    this.$plan.assignTramiteToRange(2, this.$plan.getRange('12/2016', '12/2016'));

    assert.deepEqual(this.$plan.getState(), this.sample_state, "Summary must be properly structured");
});

QUnit.test("On success load, setState must be called", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(this.$plan, 'setState');
    this.$plan.load();
    this.server.respond();

    assert.ok(this.$plan.setState.calledOnce);
    this.$plan.setState.restore();
});

QUnit.test("Set state should put the grid in a given state", function (assert) {
    // previously assigned tramites must be cleared
    this.$plan.assignTramiteToRange(0, this.$plan.getRange('04/2010', '05/2010'));
    this.$plan.setState(this.sample_state);

    assert.deepEqual(this.$plan.getState(), this.sample_state);
});

QUnit.test("save()", function (assert) {
    sinon.stub($, 'ajax');
    this.$plan.setState(this.sample_state);
    this.$plan.save();

    assert.ok($.ajax.calledWithMatch({ url: this.$plan.container.data('route'), data: this.sample_state, method: 'POST' }), "Ajax request must be correctly formed");
    $.ajax.restore();
});

QUnit.test("Summit button must call save method", function (assert) {
    sinon.stub(this.$plan, 'save');
    $('<button type="submit">').appendTo( this.$plan.container ).trigger('click');

    assert.ok(this.$plan.save.calledOnce);
    this.$plan.save.restore();
});

QUnit.test("On successful save, a message should be displayed", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(toastr, 'success');
    $('<button type="submit">').appendTo( this.$plan.container ).trigger('click');

    assert.ok(toastr["success"].calledWith("Los cambios han sido guardados"), "A success message is necessary here");
    toastr['success'].restore();
});

QUnit.test("On server error, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, 'error');
    $('<button type="submit">').appendTo( this.$plan.container ).trigger('click');

    assert.ok(toastr["error"].calledWith("Ocurrió un error. En caso de que el problema persista contacte a soporte"), "An error message is necessary here");
    toastr['error'].restore();
});

QUnit.test("load must request the plan septenal individual to the server", function (assert) {
    sinon.stub($, 'ajax');
    var data = {
        inicio: 2010,
        fin: 2016
    };
    this.$plan.load(data.inicio, data.fin);

    assert.ok($.ajax.calledWithMatch({ url: this.$plan.container.data('route'), data: data, method: 'GET' }), "Ajax request must be correctly formed");
    $.ajax.restore();
});

QUnit.test("On successful get, a message should be displayed", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(toastr, 'success');
    this.$plan.load(2010, 2016);

    assert.ok(toastr["success"].calledWith("Plan septenal cargado satisfactoriamente"), "A success message is necessary here");
    toastr['success'].restore();
});

QUnit.test("On server error while requestinng plan, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, 'error');
    this.$plan.load(2010, 2016);

    assert.ok(
        toastr["error"].calledWith(
            "Ocurrió un error al intentar cargar el plan septenal. En caso de que el problema persista contacte a soporte"
        ),
        "An error message is necessary here"
    );
    toastr['error'].restore();
});
