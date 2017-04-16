QUnit.module("Plan Septenal Colectivo Previewer", {
    beforeEach: function () {
        this.planContainer = $("#plan-colectivo-dialog .modal-body .row");
        this.plan1 = {owner: "John", status: "creating",  tramites: []};
        this.plan2 = {owner: "Beth", status: "modifying", tramites: []};
        this.plan3 = {owner: "Bob",  status: "waiting",   tramites: []};
    }
});

QUnit.test("render method needs a container as first parameter", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render();
        },
        /container \(first argument\) is mandatory/
    );
});

QUnit.test("render method needs an object with plan colectivo data as second parameter", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer);
        },
        /data of plan colectivo \(second argument\) is mandatory/
    );
});

QUnit.test("render method second parameter must have properties year and tramites", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, {});
        },
        /year and planes are mandatory properties of data/
    );
});

QUnit.test("render method, planes property of data must be an array", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: "" });
        },
        /data.planes must be an array/
    );
});

QUnit.test("render methods, planes elements must be objects", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [""] });
        },
        /data.planes elements must be objects/
    );
});

QUnit.test("render methods, planes cannot be empty", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [] });
        },
        /data.planes cannot be empty/
    );
});

QUnit.test("planes must have the form '{owner: \"owner\", status: \"status\", tramites: []}'", function (assert) {
    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [{}] });
        },
        /data.planes elements must be objects of the form {owner: "owner", status: "status", tramites: \[\]}/
    );

    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [{owner: ""}] });
        },
        /data.planes elements must be objects of the form {owner: "owner", status: "status", tramites: \[\]}/
    );

    assert.throws(
        function () {
            PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [{owner: "", status: ""}] });
        },
        /data.planes elements must be objects of the form {owner: "owner", status: "status", tramites: \[\]}/
    );

    // this should not throw an exception
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [{owner: "", status: "", tramites: []}] });
});

QUnit.test("after render container should have a table element", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1] });

    assert.equal(this.planContainer.children("table").length, 1);
});

QUnit.test("after render container table should has as many rows as plans", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, {
        year: 2000,
        planes: [this.plan1, this.plan2, this.plan3]
    });
    assert.equal(this.planContainer.find("table tbody tr").length, 3);
});

QUnit.test("rows should have 85 cells each one", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });

    var rows = this.planContainer.find("table tbody tr");
    assert.equal(rows.eq(0).children("td").length, 85);
    assert.equal(rows.eq(1).children("td").length, 85);
});

QUnit.test("table should have a head with 8 headers", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });

    var headers = this.planContainer.find("table thead td");
    assert.equal(headers.length, 8);
});

QUnit.test("table should have a headers that display years ????", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });

    var thead = this.planContainer.find("table thead");
    assert.equal(thead.html(), '<tr><td>Profesores</td><td colspan="12">2000</td><td colspan="12">2001</td><td colspan="12">2002</td><td colspan="12">2003</td><td colspan="12">2004</td><td colspan="12">2005</td><td colspan="12">2006</td></tr>');
});

QUnit.test("table should display owner names in first column", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });

    var rows = this.planContainer.find("table tbody tr");

    assert.equal(rows.eq(0).children("td").eq(0).html(), this.plan1.owner);
    assert.equal(rows.eq(1).children("td").eq(0).html(), this.plan2.owner);
});

QUnit.test("render must be idempotent", function (assert) {
    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });
    var firstBuiltHtml = this.planContainer.html();

    PlanColectivoPreViewer.render(this.planContainer, { year: 2000, planes: [this.plan1, this.plan2] });
    var secondBuiltHtml = this.planContainer.html();

    assert.equal(secondBuiltHtml, firstBuiltHtml);
});

QUnit.test("render must asign tramites to cells", function (assert) {
    this.plan2.tramites = [
        {
            tipo: TRAMITES[0].name,
            periodo: { start: "01/2010", end: "03/2010" }
        },
        {
            tipo: TRAMITES[1].name,
            periodo: { start: "03/2011", end: "03/2011" }
        }
    ];

    PlanColectivoPreViewer.render(this.planContainer, { year: 2010, planes: [this.plan1, this.plan2] });

    var rgb_sabatico = "rgb(0, 167, 208)",  // "rgb(0, 167, 208)" > "#00a7d0"
        rgb_licencia = "rgb(48, 187, 187)"; // "rgb(48, 187, 187)" > "#30bbbb"

    var rows = this.planContainer.find("table tbody tr");
    assert.equal(rows.eq(1).children("td").eq(1).css("background-color"), rgb_sabatico);
    assert.equal(rows.eq(1).children("td").eq(2).css("background-color"), rgb_sabatico);
    assert.equal(rows.eq(1).children("td").eq(15).css("background-color"), rgb_licencia);
});
