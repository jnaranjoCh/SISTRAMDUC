QUnit.module("PlanSeptenalColectivoTable", {
    beforeEach: function () {
        this.$table = $("#plan-colectivo-dialog table");
    }
});

QUnit.test("render method needs a container as first parameter", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoTable();
        },
        /container \(first argument\) is mandatory/
    );
});

QUnit.test("render method needs an object with plan colectivo data as second parameter", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoTable(this.$table);
        },
        /data of plan colectivo \(second argument\) is mandatory/
    );
});

QUnit.test("render method second parameter must have properties inicio and tramites", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoTable(this.$table, {});
        },
        /inicio and planes are mandatory properties of data/
    );
});

QUnit.test("render method, planes property of data must be an array", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: "" });
        },
        /data.planes must be an array/
    );
});

QUnit.test("render method, planes property of data must be an array", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: "" });
        },
        /data.planes must be an array/
    );
});

QUnit.module("Table rendering", {
    beforeEach: function () {
        this.$table = $("#plan-colectivo-dialog table");
        this.plans = [
            {owner: {id: 1, nombre: "John"}, status: "creating",  tramites: []},
            {owner: {id: 2, nombre: "Beth"}, status: "modifying", tramites: []},
            {owner: {id: 3, nombre: "Bob" }, status: "waiting",   tramites: []}
        ];
    }
});

QUnit.test("render should add class plan-table to container", function (assert) {
    assert.notOk(this.$table.hasClass("plan-table"));

    var table = new PlanSeptenalColectivoTable(this.$table, {
        inicio: 2000,
        planes: this.plans
    });

    assert.ok(this.$table.hasClass("plan-table"));
});

QUnit.test("after render container table should has as many rows as plans", function (assert) {
    var table = new PlanSeptenalColectivoTable(this.$table, {
        inicio: 2000,
        planes: this.plans
    });

    assert.equal(this.$table.find("tbody tr").length, 3);
});

QUnit.test("rows should have 85 cells each one", function (assert) {
    new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: [this.plans[1], this.plans[2]] });

    var rows = this.$table.find("tbody tr");
    assert.equal(rows.eq(0).children("td").length, 85);
    assert.equal(rows.eq(1).children("td").length, 85);
});

QUnit.test("table should have a head with 8 headers", function (assert) {
    new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: [this.plans[1], this.plans[2]] });

    var headers = this.$table.find("thead td");
    assert.equal(headers.length, 8);
});

QUnit.test("table headers should display years", (assert) {
    new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: [this.plans[1], this.plans[2]] });

    var thead = this.$table.find("thead");
    assert.equal(thead.html(), '<tr><td>Profesores</td><td colspan="12">2000</td><td colspan="12">2001</td><td colspan="12">2002</td><td colspan="12">2003</td><td colspan="12">2004</td><td colspan="12">2005</td><td colspan="12">2006</td></tr>');
});

QUnit.test("table should display owner names in first column", function (assert) {
    new PlanSeptenalColectivoTable(this.$table, {
        inicio: 2000,
        planes: this.plans
    });

    var first_col = this.$table.find("tbody tr td:first-child");

    assert.equal(first_col.eq(0).html(), this.plans[0].owner.nombre);
    assert.equal(first_col.eq(1).html(), this.plans[1].owner.nombre);
    assert.equal(first_col.eq(2).html(), this.plans[2].owner.nombre);
});

QUnit.test("render should not append but rebuild", function (assert) {
    var table = new PlanSeptenalColectivoTable(this.$table, { inicio: 2000, planes: [this.plans[1], this.plans[2]] });
    var firstBuiltHtml = this.$table.html();

    table.setState({ inicio: 2000, planes: [this.plans[1], this.plans[2]] });
    var secondBuiltHtml = this.$table.html();

    assert.equal(secondBuiltHtml, firstBuiltHtml);
});

QUnit.test("render must asign tramites to cells", function (assert) {
    this.plans[2].tramites = [
        {
            tipo: TRAMITES[0].name,
            periodo: { start: "01/2010", end: "03/2010" }
        },
        {
            tipo: TRAMITES[1].name,
            periodo: { start: "03/2011", end: "03/2011" }
        }
    ];

    var table = new PlanSeptenalColectivoTable(this.$table, { inicio: 2010, planes: [this.plans[1], this.plans[2]] });

    var rgb_sabatico = "rgb(0, 167, 208)",  // "rgb(0, 167, 208)" > "#00a7d0"
        rgb_licencia = "rgb(48, 187, 187)"; // "rgb(48, 187, 187)" > "#30bbbb"

    var rows = this.$table.find("tbody tr");
    assert.equal(rows.eq(1).children("td").eq(1).css("background-color"), rgb_sabatico);
    assert.equal(rows.eq(1).children("td").eq(2).css("background-color"), rgb_sabatico);
    assert.equal(rows.eq(1).children("td").eq(15).css("background-color"), rgb_licencia);
});

QUnit.test("table should keep track of its state", function (assert) {
    var state = { inicio: 2010, planes: this.plans };
    var table = new PlanSeptenalColectivoTable(this.$table, state);

    assert.deepEqual(table.state, state);
});

QUnit.test("setState", function (assert) {
    var state = { inicio: 2010, planes: this.plans };
    var table = new PlanSeptenalColectivoTable(this.$table, state);

    var other_state = { inicio: 2015, planes: this.plans};
    table.setState(other_state);

    assert.deepEqual(table.state, other_state);
});

QUnit.module("PlanSeptenalColectivoPreviewer", {
    beforeEach: function () {
        this.previewerContainer = $("#plan-colectivo-dialog");
        this.plans = [
            {owner: "John", status: "creating",  tramites: []},
            {owner: "Beth", status: "modifying", tramites: []},
            {owner: "Bob",  status: "waiting",   tramites: []}
        ];

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("render method needs a container as first parameter", function (assert) {
    assert.throws(
        function () {
            new PlanSeptenalColectivoPreviewer();
        },
        /container \(first argument\) is mandatory/
    );
});

QUnit.test("load should request plan colectivo", function (assert) {
    sinon.stub($, "ajax");

    var previewer = new PlanSeptenalColectivoPreviewer(this.previewerContainer);
    previewer.load({inicio: 2018});

    var ajaxSetting = {
        url: routes["plan-septenal-colectivo"],
        method: "GET",
        data: {inicio: 2018}
    };
    assert.calledWithMatch($.ajax, ajaxSetting);
    $.ajax.restore();
});

QUnit.test("load should put plan into table on success", function (assert) {
    var plan_colectivo = { inicio: 2018, planes: this.plans };
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(plan_colectivo)]);

    var previewer = new PlanSeptenalColectivoPreviewer(this.previewerContainer);
    previewer.load({inicio: 2018});

    assert.deepEqual(previewer.table.state, plan_colectivo);
});

QUnit.test("load should show dialog on success", function (assert) {
    var plan_colectivo = { inicio: 2018, planes: this.plans };
    this.server.respondWith([200, { "Content-Type": "application/json" }, JSON.stringify(plan_colectivo)]);
    sinon.stub($.prototype, "modal");

    var previewer = new PlanSeptenalColectivoPreviewer(this.previewerContainer);
    previewer.load({inicio: 2018});

    assert.calledWith($.prototype.modal, "show");
    $.prototype.modal("restore");
});

QUnit.test("load should show a message on failure", function (assert) {
    sinon.stub(toastr, "error");
    this.server.respondWith([500, { "Content-Type": "application/json" }, ""]);

    var previewer = new PlanSeptenalColectivoPreviewer(this.previewerContainer);
    previewer.load({inicio: 2018});

    assert.calledWith(toastr.error, SERVER_ERROR_MESSAGE);
    toastr.error.restore();
});
