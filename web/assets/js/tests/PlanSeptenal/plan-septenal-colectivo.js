QUnit.module("Request opening of creation process", {
    beforeEach: function () {
        this.plan_creation = $("#plan-septenal-colectivo-creation");
        this.start_creation_btn = this.plan_creation.find("#start-creation-btn");

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("startCreationProcess() should make an ajax request", function (assert) {
    sinon.stub($, "ajax");
    planSeptenalColectivo.startCreationProcess("url", {});

    assert.ok($.ajax.calledWithMatch({ url: "url", data: {}, method: "POST" }));
    $.ajax.restore();
});

QUnit.test("getFormData()", function (assert) {
    var data = getFormData();
    assert.equal(typeof data.inicio, "number");
});

QUnit.test("click on #start-creation-btn should call startCreationProcess", function (assert) {
    var data = getFormData(), url = this.plan_creation.find("form").attr("action");;
    sinon.stub(planSeptenalColectivo, "startCreationProcess");
    this.start_creation_btn.trigger("click");

    assert.ok(planSeptenalColectivo.startCreationProcess.calledWithMatch(url, data));
    planSeptenalColectivo.startCreationProcess.restore()
});

QUnit.test("click on #start-creation-btn should prevent default action", function (assert) {
    var click_event = jQuery.Event("click");
    click_event.preventDefault = sinon.spy();
    this.start_creation_btn.trigger(click_event);

    assert.ok(click_event.preventDefault.called);
});

QUnit.test("on successful startCreationProcess a success message will be shown", function (assert) {
    this.server.respondWith([200, {}, ""]);
    sinon.stub(toastr, "success");
    planSeptenalColectivo.startCreationProcess("url", {});

    assert.ok(toastr.success.calledWith("Proceso iniciado satisfactoriamente."));
    toastr.success.restore();
});

QUnit.test("on successful startCreationProcess form must be hidden and progress html be visible", function (assert) {
    this.server.respondWith([200, {}, ""]);
    assert.notOk(this.plan_creation.find("#creation-progress").is(':visible'));

    planSeptenalColectivo.startCreationProcess("url", {});

    assert.notOk(this.plan_creation.find("form").is(':visible'));
    assert.ok(this.plan_creation.find("#creation-progress").is(':visible'));
});

QUnit.test("on successful startCreationProcess _loadPlanesIndividualesTable should be invoked", function (assert) {
    sinon.stub(planSeptenalColectivo, "_loadPlanesIndividualesTable");
    this.server.respondWith([200, {}, ""]);

    planSeptenalColectivo.startCreationProcess("url", {});
    assert.ok(planSeptenalColectivo._loadPlanesIndividualesTable.called);
    planSeptenalColectivo._loadPlanesIndividualesTable.restore();
});

QUnit.test("on client error while starting creation process a message should be displayed", function (assert) {
    var message = '"something went wrong"';
    this.server.respondWith([400, { "Content-Type": "application/json" }, message]);
    sinon.stub(toastr, "error");
    planSeptenalColectivo.startCreationProcess("url", {});

    assert.ok( toastr.error.calledWith(message.slice(1, -1)) );
    toastr.error.restore();
});

QUnit.test("on server error while starting creation process message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, "error");
    planSeptenalColectivo.startCreationProcess("url", {});

    assert.ok(
        toastr.error.calledWith("Ocurrió un error. En caso de que el problema persista contacte a soporte")
    );
    toastr.error.restore();
});

QUnit.test("getPlanSeptenalColectivoOfNextYear() should perform an ajax request", function (assert) {
    sinon.stub($, "ajax");
    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url");
    var ajaxSettings = {
        url: "url",
        data: { inicio: parseInt($("#start_year").val()) },
        method: "GET"
    };

    assert.ok($.ajax.calledWithMatch(ajaxSettings));
    $.ajax.restore();
});

QUnit.test("initial state before getPlanSeptenalColectivoOfNextYear() call", function (assert) {
    assert.notOk(this.plan_creation.find("form").is(":visible"));
    assert.notOk(this.plan_creation.find("#creation-progress").is(':visible'));
});

QUnit.test("on successful getPlanSeptenalColectivoOfNextYear()", function (assert) {
    sinon.stub(planSeptenalColectivo, "_loadPlanesIndividualesTable");
    this.server.respondWith([200, {}, JSON.stringify( {status: "En creación"} )]);

    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url", {});

    assert.notOk(this.plan_creation.find("form").is(":visible"), "creation form should be hidden");
    assert.ok(this.plan_creation.find("#creation-progress").is(':visible'), "creation progress should be visible");

    var config = { "ajax": "/plan-septenal-individual/get-all?inicio=" + $("#start_year").val() };
    assert.ok(planSeptenalColectivo._loadPlanesIndividualesTable.calledWith(config));
    planSeptenalColectivo._loadPlanesIndividualesTable.restore();
});

QUnit.test("on notFound getPlanSeptenalColectivoOfNextYear()", function (assert) {
    this.server.respondWith([404, {}, ""]);

    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url", {});

    assert.ok(this.plan_creation.find("form").is(":visible"));
    assert.notOk(this.plan_creation.find("#creation-progress").is(':visible'));
});

QUnit.test("on client error while starting creation process received error message should be displayed", function (assert) {
    var message = '"something went wrong"';
    this.server.respondWith([400, { "Content-Type": "application/json" }, message]);
    sinon.stub(toastr, "error");
    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url", {});

    assert.ok( toastr.error.calledWith(message.slice(1, -1)) );
    toastr.error.restore();
});

QUnit.test("on server error while starting creation process message should be displayed", function (assert) {
    this.server.respondWith([500, {}, ""]);
    sinon.stub(toastr, "error");
    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url", {});

    assert.ok(
        toastr.error.calledWith("Ocurrió un error. En caso de que el problema persista contacte a soporte")
    );
    toastr.error.restore();
});

QUnit.module("Managing creation process", {
    beforeEach: function () {
        this.plan_creation = $("#plan-septenal-colectivo-creation");
        this.start_creation_btn = this.plan_creation.find("#start-creation-btn");

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("_loadPlanesIndividualesTable should initialize datatable", function (assert) {
    var inicio = parseInt($("#start_year").val()),
        config = { "ajax": "/plan-septenal-individual/get-all?inicio=" + inicio };

    sinon.stub($.fn, "DataTable");

    planSeptenalColectivo._loadPlanesIndividualesTable(config);

    assert.ok($.fn.DataTable.calledWith(config));
    $.fn.DataTable.restore();
});

QUnit.module("Managing creation process", {
    beforeEach: function () {
        this.plan_creation = $("#plan-septenal-colectivo-creation");
        this.start_creation_btn = this.plan_creation.find("#start-creation-btn");

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;

        var content = {
            data: [
                [1, "Tony", 5, "Modificando"],
                [2, "Alfred", 3, "Esperando aprobación"],
                [3, "Beth", 8, "Esperando aprobación"]
            ]
        };

        this.server.respondWith([200, {}, JSON.stringify(content)]);
        planSeptenalColectivo._loadPlanesIndividualesTable({ ajax: "endpoint"});
    },
    afterEach: function() {
        this.server.restore();
    }
});

QUnit.test("view button must exist per plan inside DataTables", function (assert) {
    assert.equal($(".btn-view-plan").length, 3, "three view buttons must exist");
});

QUnit.test("View button should request plan septenal individual", function (assert) {
    var viewer = planSeptenalColectivo.details_viewer;
    sinon.stub(viewer.planIndividual, "load");

    $(".btn-view-plan").first().trigger("click");
    assert.ok(viewer.planIndividual.load.calledWith({id: 1}));

    $(".btn-view-plan").eq(1).trigger("click");
    assert.ok(viewer.planIndividual.load.calledWith({id: 2}));

    viewer.planIndividual.load.restore();
});

QUnit.test("On success a modal should display plan individual", function (assert) {
    planSeptenalColectivo.details_viewer.planIndividual.load({id: 1})
    sinon.stub(planSeptenalColectivo.details_viewer, "modal");

    $(".btn-view-plan").first().trigger("click");

    assert.ok(planSeptenalColectivo.details_viewer.modal.called);
    planSeptenalColectivo.details_viewer.modal.restore();
});

QUnit.test("approve button must exist per plan that is waiting for approval", function (assert) {
    assert.equal($(".btn-approve-plan").length, 2, "two approve buttons must exist");
});

QUnit.test("click on approve button must make an ajax request", function (assert) {
    sinon.stub($, "ajax");
    this.server.respondWith([200, {}, JSON.stringify({})]);
    var ajaxSettings = {
        url: "/plan-septenal-individual/approve",
        data: { id: 2 }
    };

    $(".btn-approve-plan").eq(0).trigger("click");
    assert.ok($.ajax.calledWithMatch(ajaxSettings));

    $(".btn-approve-plan").eq(1).trigger("click");
    ajaxSettings.data.id = 3;
    assert.ok($.ajax.calledWithMatch(ajaxSettings));

    $.ajax.restore();
});

QUnit.test("click on approve button must make an ajax request", function (assert) {
    sinon.stub($, "ajax");
    this.server.respondWith([200, {}, JSON.stringify({})]);
    var ajaxSettings = {
        url: "/plan-septenal-individual/approve",
        method: "POST",
        data: { id: 2 }
    };

    $(".btn-approve-plan").eq(0).trigger("click");
    assert.ok($.ajax.calledWithMatch(ajaxSettings));

    $(".btn-approve-plan").eq(1).trigger("click");
    ajaxSettings.data.id = 3;
    assert.ok($.ajax.calledWithMatch());

    $.ajax.restore();
});

QUnit.test("click on approve button must make an ajax request", function (assert) {
    sinon.stub(planSeptenalColectivo.datatable.ajax, "reload");
    this.server.respondWith([200, {}, JSON.stringify({})]);

    $(".btn-approve-plan").eq(0).trigger("click");
    assert.ok(planSeptenalColectivo.datatable.ajax.reload.called);
    planSeptenalColectivo.datatable.ajax.reload.restore();
});
