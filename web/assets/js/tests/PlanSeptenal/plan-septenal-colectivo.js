QUnit.module("Request opening of creation process", {
    beforeEach: function () {
        this.plan_creation = $("#plan-septenal-colectivo-creation");
        this.start_creation_btn = this.plan_creation.find("#start-creation-btn");

        this.server = sinon.fakeServer.create();
        this.server.respondImmediately = true;
    }
});

QUnit.test("startCreationProcess() should make an ajax request", function (assert) {
    sinon.stub($, "ajax");
    planSeptenalColectivo.startCreationProcess("url", {});

    assert.ok($.ajax.calledWithMatch({ url: "url", data: {}, method: "POST" }), "Ajax request must be correctly formed");
    $.ajax.restore();
});

QUnit.test("getFormData()", function (assert) {
    var data = getFormData();

    assert.equal(typeof data.inicio, "number");
    assert.equal(typeof data.fin, "number");
    assert.equal(data.fin, data.inicio + 6);
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
    var inicio = parseInt($("#start_year").val());

    assert.ok($.ajax.calledWithMatch({ url: "url", data: {inicio: inicio, fin: inicio + 6}, method: "GET" }), "Ajax request must be correctly formed");
    $.ajax.restore();
});

QUnit.test("initial state before getPlanSeptenalColectivoOfNextYear() call", function (assert) {
    assert.notOk(this.plan_creation.find("form").is(":visible"));
    assert.notOk(this.plan_creation.find("#creation-progress").is(':visible'));
});

QUnit.test("on successful getPlanSeptenalColectivoOfNextYear()", function (assert) {
    this.server.respondWith([200, {}, JSON.stringify( {status: "En creación"} )]);

    planSeptenalColectivo.getPlanSeptenalColectivoOfNextYear("url", {});

    assert.notOk(this.plan_creation.find("form").is(":visible"));
    assert.ok(this.plan_creation.find("#creation-progress").is(':visible'));
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
