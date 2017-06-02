$('#limpiarConcurso').click(function (){ 

	document.getElementById('aperturaConcurso').reset();

	$('#fa').removeClass("has-error");
	$('#fi').removeClass("has-error");
	$('#fe').removeClass("has-error");

	toastr.clear();
});

function justNumbers(e){

	var keynum = window.event ? window.event.keyCode : e.which;

	if (keynum == 8 || keynum == 46){
		return true;
	}
	else return /\d/.test(String.fromCharCode(keynum));
}