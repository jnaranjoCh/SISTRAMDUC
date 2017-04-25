$( window ).load(function() {
    $("#tableUsers").DataTable( {
          "ajax": "/web/app_dev.php/consulta/enviar-emails",
          "columns": [
		        { "data": "Email" },
		        { "data": "Estatus" }
	       ],
	       "language": {
            	"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
    });
});