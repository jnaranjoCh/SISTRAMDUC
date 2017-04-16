sinon.assert.fail = function (message) {
    QUnit.assert.pushResult({
        result: false,
        actual: null,
        expected: null,
        message: message
    });
}

sinon.assert.pass = function (message) {
    QUnit.assert.pushResult({
        result: true,
    });
}

QUnit.assert.calledWith = function (spy) {
    sinonAssert.call(this, spy, "calledWith", arguments);
}

QUnit.assert.calledWithMatch = function (spy) {
    sinonAssert.call(this, spy, "calledWithMatch", arguments);
}

function sinonAssert (spy, assertion, argums) {
    var args = [].slice.call(argums, 1);

    this.pushResult({
        result: spy[assertion].apply(spy, args),
        actual: spy.getCall(0).args, // this should be the matching call instead, but I don't know how to get it
        expected: args,
        message: spy.printf("%n") + " call does not match expected arguments"
    });
}
