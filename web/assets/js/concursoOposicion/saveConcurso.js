$('#limpiarConcurso').click(function (){ 

	document.getElementById('aperturaConcurso').reset();

	$('#spanfechaConcurso').addClass("hide");
	$('#spancedula').addClass("hide");
	$('#spancedula2').addClass("hide");
	$('#spanarea').addClass("hide");

	toastr.clear();
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}