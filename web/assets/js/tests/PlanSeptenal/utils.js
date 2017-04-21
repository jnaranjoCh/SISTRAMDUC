function triggerClickRelatedEvents () {
    $targets = Array.prototype.slice.call(arguments);
    for (var i = 0; i < $targets.length; i++) {
        $targets[i].trigger("mouseenter").trigger("mousedown").trigger("mouseup").trigger("click");
    }
}

// a stub for toastr
var toastr = {
    error: function () {},
    success: function () {},
    info: function () {},
    warning: function () {}
};
