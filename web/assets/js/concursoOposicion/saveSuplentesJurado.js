$('#limpiarJurado').click(function (){ 

	document.getElementById('juradosSave').reset();

	for (var i = 1; i <= 3; i++) {
		
		$('#spancedula'+i).addClass("hide");
		$('#spannombre'+i).addClass("hide");
		$('#spanapellido'+i).addClass("hide");
		$('#spanfacultad'+i).addClass("hide");
		$('#spanuniversidad'+i).addClass("hide");
		$('#spanarea'+i).addClass("hide");
	}

	$("#lista").html(opcion);

	if(personal)
        $("#personal").click();

    if(jurado2)
        $("#jurado2").click();

    if(jurado3)
        $("#jurado3").click();

    personal = false;
	jurado2 = false;
	jurado3 = false;

	toastr.clear();
});