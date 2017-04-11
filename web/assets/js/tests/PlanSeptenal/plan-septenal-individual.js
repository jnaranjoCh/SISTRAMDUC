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

QUnit.test("container should have data-route attr", function (assert) {
    assert.throws(
        function () {
            var p = new PlanSeptenalIndividual($("<div>"), "x");
        },
        /route/
    );

});

QUnit.test("starting year is mandatory during PlanSeptenalIndividual creation", function (assert) {
    assert.throws(
        function () {
            var p = new PlanSeptenalIndividual($("<div data-route='url'>"), "x");
        },
        /Starting year/
    );
});

QUnit.module("Activity Assignment", {
    beforeEach: function() {
        this.$firstYear = 2010;
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual"), this.$firstYear);

        this.$sabatico_btn = $(".grid-action-btn").first();
        this.$licencia_btn = $(".grid-action-btn").eq(3);
        this.$beca_btn = $(".grid-action-btn").eq(4);
        this.$clear_btn = $(".grid-clear-btn").first();

        this.$first  = this.$plan.grid.cell(0);
        this.$second = this.$plan.grid.cell(1);
        this.$third  = this.$plan.grid.cell(3);
        this.$last   = this.$plan.grid.cell(83);

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("When an action button is clicked selection must not vanish", function (assert) {
    this.$plan.select("01/2010", "01/2010");
    assert.ok(this.$first.isSelected(), "First cell should be selected initially");

    triggerClickRelatedEvents(this.$sabatico_btn);
    assert.equal(this.$plan.getSelection().length, 1, "Only one cell should be selected");
    assert.ok(this.$first.isSelected(), "First cell should be selected");
});

QUnit.test("When an action button is clicked current selected cells must get data-tramite-type and background-color from the pressed button", function (assert) {
    this.$plan.select("01/2010", "02/2010");
    triggerClickRelatedEvents(this.$sabatico_btn);

    var btn_type = this.$sabatico_btn.data("tramite-type"),
        btn_bg = this.$sabatico_btn.css("background-color");

    assert.strictEqual(this.$first.data("tramite-type"), btn_type, "First element should have corresponding data-tramite-type");
    assert.equal(this.$first.css("background-color"), btn_bg, "First element should have the corresponding background-color");

    assert.strictEqual(this.$second.data("tramite-type"), btn_type, "Second element should have corresponding data-tramite-type");
    assert.equal(this.$second.css("background-color"), btn_bg, "Second element should have the corresponding background-color");
});

QUnit.test("When clear button is pressed selected elements should get an undefined data-tramite-type and lose their background color", function (assert) {
    this.$plan.select("01/2010", "02/2010");
    triggerClickRelatedEvents(this.$sabatico_btn);
    triggerClickRelatedEvents(this.$clear_btn);

    assert.notEqual(this.$sabatico_btn.css("background-color"), undefined);

    assert.strictEqual(this.$first.data("tramite-type"), undefined, "First element should not have data-tramite-type");
    assert.strictEqual(this.$second.data("tramite-type"), undefined, "Second element should not have data-tramite-type");
});

QUnit.module("Loading and saving", {
    beforeEach: function() {
        this.$firstYear = 2010;
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual"), this.$firstYear);
        this.sample_state = {
            inicio: 2010,
            fin: 2016,
            tramites: [
                {
                    tipo: PlanSeptenalIndividual.TRAMITES[0].name,
                    periodo: {
                        start: "01/2010",
                        end: "02/2010",
                    }
                },
                {
                    tipo: PlanSeptenalIndividual.TRAMITES[1].name,
                    periodo: {
                        start: "03/2010",
                        end: "03/2010"
                    }
                },
                {
                    tipo: PlanSeptenalIndividual.TRAMITES[2].name,
                    periodo: {
                        start: "12/2016",
                        end: "12/2016",
                    }
                }
            ]
        };

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("getState()", function (assert) {
    this.$plan.assignTramiteToRange(0, this.$plan.getRange("01/2010", "02/2010"));
    this.$plan.assignTramiteToRange(1, this.$plan.getRange("03/2010", "03/2010"));
    this.$plan.assignTramiteToRange(2, this.$plan.getRange("12/2016", "12/2016"));

    assert.deepEqual(this.$plan.getState(), this.sample_state, "Summary must be properly structured");
});

QUnit.test("On success load, setState must be called", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(this.$plan, "setState");
    this.$plan.load({ inicio: 2010 });
    this.server.respond();

    assert.ok(this.$plan.setState.calledOnce);
    this.$plan.setState.restore();
});

QUnit.test("Set state should put the grid in a given state", function (assert) {
    // previously assigned tramites must be cleared
    this.$plan.assignTramiteToRange(0, this.$plan.getRange("04/2010", "05/2010"));
    this.$plan.setState(this.sample_state);

    assert.deepEqual(this.$plan.getState(), this.sample_state);
});

QUnit.test("save()", function (assert) {
    sinon.stub($, "ajax");
    this.$plan.setState(this.sample_state);
    this.$plan.save();

    var ajaxSettings = {
        url: this.$plan.container.data("route"),
        data: this.sample_state,
        method: "POST"
    };
    assert.ok($.ajax.calledWithMatch(ajaxSettings));
    $.ajax.restore();
});

QUnit.test("on save() if status is modifying the request should be PUT instead of POST", function (assert) {
    sinon.stub($, "ajax");
    this.$plan.setState(this.sample_state);
    this.$plan.status = "Modificando";
    this.$plan.save();

    var ajaxSettings = {
        url: this.$plan.container.data("route"),
        data: this.sample_state,
        method: "PUT"
    };
    assert.ok($.ajax.calledWithMatch(ajaxSettings));
    $.ajax.restore();
});

QUnit.test("Summit button must call save method", function (assert) {
    sinon.stub(this.$plan, "save");
    $("#btn-save").appendTo( this.$plan.container ).trigger("click");

    assert.ok(this.$plan.save.calledOnce);
    this.$plan.save.restore();
});

QUnit.test("On successful save, a message should be displayed", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(toastr, "success");
    $("#btn-save").appendTo( this.$plan.container ).trigger("click");

    assert.ok(toastr["success"].calledWith("Los cambios han sido guardados"), "A success message is necessary here");
    toastr["success"].restore();
});

QUnit.test("On server error, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, "error");
    $("#btn-save").appendTo( this.$plan.container ).trigger("click");

    assert.ok(toastr["error"].calledWith("Ocurrió un error. En caso de que el problema persista contacte a soporte"), "An error message is necessary here");
    toastr["error"].restore();
});

QUnit.test("load must receive an object with an id or period", function (assert) {
    assert.throws(
        function () {
            this.$plan.load("");
        },
        /must be an object/
    );

    assert.throws(
        function () {
            this.$plan.load({});
        },
        /id or inicio/
    );

    assert.throws(
        function () {
            this.$plan.load({otherkey: true});
        },
        /id or inicio/
    );
});

QUnit.test("load must request the plan septenal individual to the server", function (assert) {
    sinon.stub($, "ajax");
    var data = { inicio: 2010 };
    this.$plan.load(data);

    var ajaxSettings = {
        url: this.$plan.container.data("route"),
        data: data,
        method: "GET"
    };
    assert.ok($.ajax.calledWithMatch(ajaxSettings));
    $.ajax.restore();
});

QUnit.test("On server error while requesting plan, a message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, "error");
    this.$plan.load({ inicio: 2010 });

    assert.ok(
        toastr["error"].calledWith(
            "Ocurrió un error al intentar cargar el plan septenal. En caso de que el problema persista contacte a soporte"
        ),
        "An error message is necessary here"
    );
    toastr["error"].restore();
});

QUnit.test("plan status must be empty initially", function (assert) {
    assert.strictEqual(this.$plan.status, "");
});

QUnit.test("plan status will be creating if plan does not exist", function (assert) {
    this.server.respondWith([404, {}, ""]);
    this.$plan.load({ inicio: 2010 });
    assert.equal(this.$plan.status, "En creación");
});

QUnit.test("plan status will be modifying if plan exists", function (assert) {
    this.sample_state.status = "Modificando";
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]);
    this.$plan.load({ inicio: 2010 });
    assert.equal(this.$plan.status, "Modificando");
});

QUnit.test("on successful save plan status will be modifying", function (assert) {
    this.sample_state.status = "Modificando";
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]);
    this.$plan.save();
    assert.equal(this.$plan.status, "Modificando");
});

QUnit.test("if plan septenal colectivo is 'in creation' then plan septenal individual should be loaded", function (assert) {
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify({status: "En creación"})]);
    sinon.stub(PlanSeptenalIndividual.prototype, "load");
    this.container = $(".plan-septenal-individual");
    attemptToLoadPlanIndividual(this, 2018);

    assert.ok(PlanSeptenalIndividual.prototype.load.calledWith({inicio: 2018 }));
    PlanSeptenalIndividual.prototype.load.restore();
});

QUnit.test("if plan septenal colectivo is already created then plan septenal individual should not be loaded", function (assert) {
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify({status: "Creado"})]);
    sinon.stub(PlanSeptenalIndividual.prototype, "load");
    attemptToLoadPlanIndividual(this, 2018);

    assert.notOk(PlanSeptenalIndividual.prototype.load.called);
    PlanSeptenalIndividual.prototype.load.restore();
});

QUnit.test("if plan septenal colectivo does not exist then plan septenal individual should not be loaded", function (assert) {
    this.server.respondWith([404, {}, ""]);
    sinon.stub(PlanSeptenalIndividual.prototype, "load");
    attemptToLoadPlanIndividual(this, 2018);

    assert.notOk(PlanSeptenalIndividual.prototype.load.called);
    PlanSeptenalIndividual.prototype.load.restore();
});

QUnit.test("if plan septenal colectivo does not exist a message should be displayed saying so", function (assert) {
    var app = {
        container: $("<div class='container'>")
    };
    this.server.respondWith([404, {}, ""]);
    attemptToLoadPlanIndividual(app, 2018);
    assert.ok(/El plan septenal colectivo aún no existe./.exec(app.container.html()));
});

QUnit.module("Availability of actions and asking for approval", {
    beforeEach: function() {
        this.$firstYear = 2010;
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual"), this.$firstYear);

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;

        this.sample_state =  {
            inicio: this.$firstYear,
            fin: this.$firstYear + 6,
            tramites: []
        };
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("actions availability when creating plan", function (assert) {
    this.server.respondWith([404, { "Content-Type": "application/json" }, "message"]);
    this.$plan.load({ inicio: 2010 });

    assert.strictEqual($("#btn-request-approval").prop("disabled"), true, "btn-request-approval should be disabled");
    assert.strictEqual($("#btn-request-approval").is(":visible"), true, "btn-request-approval should be visible");

    assert.strictEqual($("#btn-save").prop("disabled"), false, "btn-save should be enabled");
    assert.strictEqual($("#btn-save").is(":visible"), true, "btn-save should be visible");
});

QUnit.test("actions availability when modifying plan", function (assert) {
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]);
    this.$plan.load({ inicio: 2010 });

    assert.strictEqual($("#btn-request-approval").prop("disabled"), false, "btn-request-approval should be enabled");
    assert.strictEqual($("#btn-request-approval").is(":visible"), true, "btn-request-approval should be visible");

    assert.strictEqual($("#btn-save").prop("disabled"), false, "btn-save should be enabled");
    assert.strictEqual($("#btn-save").is(":visible"), true, "btn-save should be visible");
});

QUnit.test("on successful save actions availability should be updated accordingly", function (assert) {
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]);
    this.$plan.save();

    assert.strictEqual($("#btn-request-approval").prop("disabled"), false, "btn-request-approval should be enabled");
    assert.strictEqual($("#btn-request-approval").is(":visible"), true, "btn-request-approval should be visible");

    assert.strictEqual($("#btn-save").prop("disabled"), false, "btn-save should be enabled");
    assert.strictEqual($("#btn-save").is(":visible"), true, "btn-save should be visible");
});

QUnit.test("askForApproval should make an ajax request", function (assert) {
    sinon.stub($, "ajax");
    this.$plan.askForApproval();

    var ajaxSettings = {
        url: "/plan-septenal-individual/ask-for-approval",
        data: { inicio: this.$plan.starting_year },
        method: "PUT"
    };
    assert.ok($.ajax.calledWithMatch(ajaxSettings));
    $.ajax.restore();
});

QUnit.test("When the user ask for approval successfully the status of the plan should change to waiting for approval", function (assert) {
    this.server.respondWith([200, {}, ""]);
    this.$plan.askForApproval();

    assert.equal(this.$plan.status, "Esperando aprobación");
});

QUnit.test("When the user ask for approval unsuccessfully the status of the plan should not change", function (assert) {
    var last_status = this.$plan.status;
    this.server.respondWith([400, {}, ""]);
    this.$plan.askForApproval();

    assert.equal(this.$plan.status, last_status);
});

QUnit.test("When the user ask for approval unsuccessfully a message should be displayed", function (assert) {
    sinon.stub(toastr, "error");
    this.server.respondWith([404, { "Content-Type": "application/json" }, JSON.stringify(["not found message"])]);
    this.$plan.askForApproval();

    assert.ok(toastr["error"].calledWith("not found message"), "An error message is necessary");
    toastr["error"].restore();
});

QUnit.test("When the user ask for approval unsuccessfully a message should be displayed", function (assert) {
    sinon.stub(toastr, "success");
    this.server.respondWith([200, {}, ""]);
    this.$plan.askForApproval();

    assert.ok(toastr["success"].calledWith("El plan septenal está en espera por aprobación."), "A success message is necessary");
    toastr["success"].restore();
});

QUnit.test("on successful ask for approval, editing and actions should be disabled", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(this.$plan, "disableEditing");

    this.$plan.askForApproval();

    assert.ok(this.$plan.disableEditing.called);
    assert.strictEqual($("#btn-request-approval").prop("disabled"), true, "btn-request-approval should be disabled");
    assert.strictEqual($("#btn-save").prop("disabled"), true, "btn-save should be disabled");

    this.$plan.disableEditing.restore();
});

QUnit.test("click on btn-request-approval should call askForApproval", function (assert) {
    sinon.stub(this.$plan, "askForApproval");
    $("#btn-request-approval").trigger("click");

    assert.ok(this.$plan.askForApproval.called);
    this.$plan.askForApproval.restore();
});

QUnit.test("on loading, if plan septenal is waiting for approval, editing and actions should be disabled", function (assert) {
    this.sample_state.status = "Esperando aprobación";
    this.server.respondWith(
        [200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]
    );
    sinon.stub(this.$plan, "disableEditing");

    this.$plan.load({ inicio: 2010 });

    assert.ok(this.$plan.disableEditing.called);
    assert.strictEqual($("#btn-request-approval").prop("disabled"), true, "btn-request-approval should be disabled");
    assert.strictEqual($("#btn-save").prop("disabled"), true, "btn-save should be disabled");

    this.$plan.disableEditing.restore();
});

QUnit.test("on loading, if plan septenal is waiting for approval status must match current status", function (assert) {
    this.sample_state.status = "Esperando aprobación";
    this.server.respondWith(
        [200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]
    );
    this.$plan.load({ inicio: 2010 });

    assert.equal($("#status").text(), this.sample_state.status);
});

QUnit.test("on loading, if plan septenal is approved all actions should be disabled as well as editing", function (assert) {
    this.sample_state.status = "Aprobado";
    this.server.respondWith(
        [200, { "Content-Type": "application/json" }, JSON.stringify(this.sample_state)]
    );
    sinon.stub(this.$plan, "disableEditing");

    this.$plan.load({ inicio: 2010 });

    assert.ok(this.$plan.disableEditing.called);
    assert.ok($("#btn-request-approval").prop("disabled"), "btn-request-approval should be disabled");
    assert.ok($("#btn-save").prop("disabled"), "btn-save should be disabled");

    this.$plan.disableEditing.restore();
});

QUnit.test("status should be visible to user", function (assert) {
    var status = this.$plan.getStatus();
    assert.strictEqual($("#status").text(), status);
});

QUnit.module("Disable and enable editing", {
    beforeEach: function() {
        this.$firstYear = 2010;
        this.$plan = new PlanSeptenalIndividual( $(".plan-septenal-individual"), this.$firstYear);
    }
});

QUnit.test("disable method should prevent manual editing", function (assert) {
    this.$plan.disableEditing();

    triggerClickRelatedEvents(this.$plan.grid.cell(0));
    triggerClickRelatedEvents(this.$plan.grid.cell(1));

    assert.notOk(this.$plan.grid.enabled, "grid should not be enabled");
    assert.equal(0, this.$plan.getSelection().length, "nothing should be selected");
    assert.notOk($(".grid-clear-btn").is(":visible"), "clear button should be invisible");
});

QUnit.test("disable method should clear current selection", function (assert) {
    triggerClickRelatedEvents(this.$plan.grid.cell(0));
    triggerClickRelatedEvents(this.$plan.grid.cell(1));

    this.$plan.disableEditing();
    assert.equal(0, this.$plan.getSelection().length, "nothing should be selected");
});
