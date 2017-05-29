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

	toastr.clear();
});