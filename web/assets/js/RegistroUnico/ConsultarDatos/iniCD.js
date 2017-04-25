$( window ).load(function() {
    $("#tableUsers").DataTable( {
          "ajax": routes["registro_obteneremails_ajax"],
          "columns": [
		        { "data": "Email" },
		        { "data": "Estatus" }
	       ],
	       "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
    });
});