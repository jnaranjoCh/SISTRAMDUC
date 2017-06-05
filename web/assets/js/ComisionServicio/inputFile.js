$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
            $('.image-preview').popover('show');
        },
        function () {
            $('.image-preview').popover('hide');
        }
    );
});

$(function() {
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("Examinar");
    });
    // Create the preview image
    $(".image-preview-input input:file").change(function (){

        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("Cambiar");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
        }
        reader.readAsDataURL(file);
    });
});


$(document).on('click', '#close-preview2', function(){
    $('.image-preview2').popover('hide');
    // Hover befor close the preview
    $('.image-preview2').hover(
        function () {
            $('.image-preview2').popover('show');
        },
        function () {
            $('.image-preview2').popover('hide');
        }
    );
});

$(function() {
    // Clear event
    $('.image-preview-clear2').click(function(){
        $('.image-preview2').attr("data-content","").popover('hide');
        $('.image-preview-filename2').val("");
        $('.image-preview-clear2').hide();
        $('.image-preview-input2 input:file').val("");
        $(".image-preview-input-title2").text("Examinar");
    });
    // Create the preview image
    $(".image-preview-input2 input:file").change(function (){

        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title2").text("Cambiar");
            $(".image-preview-clear2").show();
            $(".image-preview-filename2").val(file.name);
        }
        reader.readAsDataURL(file);
    });
});

$(document).on('click', '#close-preview3', function(){
    $('.image-preview3').popover('hide');
    // Hover befor close the preview
    $('.image-preview3').hover(
        function () {
            $('.image-preview3').popover('show');
        },
        function () {
            $('.image-preview3').popover('hide');
        }
    );
});

$(function() {
    // Clear event
    $('.image-preview-clear3').click(function(){
        $('.image-preview3').attr("data-content","").popover('hide');
        $('.image-preview-filename3').val("");
        $('.image-preview-clear3').hide();
        $('.image-preview-input3 input:file').val("");
        $(".image-preview-input-title3").text("Examinar");
    });
    // Create the preview image
    $(".image-preview-input3 input:file").change(function (){

        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title3").text("Cambiar");
            $(".image-preview-clear3").show();
            $(".image-preview-filename3").val(file.name);
        }
        reader.readAsDataURL(file);
    });
});
