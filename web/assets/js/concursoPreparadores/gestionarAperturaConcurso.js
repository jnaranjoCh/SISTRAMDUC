var id = getParameterByName('id');

$(window).load( function(){
    toastr.clear();
    var text = "";
    $.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/detalle_solicitud_concurso",
		dataType: 'json',
		data: {"id": id},
        success: function(respuesta){
			if (respuesta == "FallaConsultaDetalleSolicitud"){
                text = "Falla al consultar el detalle de esta solicitud.";
                toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
            } else{
            	var i = 0;
            	var contenidoHTML =
					'<dl class="dl-horizontal">'+
						'<dt>Asignatura Solicitante</dt>'+
						'<dd>'+respuesta["AsigSol"][i]+'</dd>'+
						
						'<dt>Número de Plazas</dt>'+
						'<dd>'+respuesta["NroPlz"][i]+'</dd>'+
						
						'<dt>Tema Examen Oral</dt>'+
						'<dd>'+respuesta["ExOral"][i]+'</dd>'+
						
						'<dt>Tema Examen Escrito</dt>'+
						'<dd>'+respuesta["ExEsc"][i]+'</dd>'+
						
						'<dt>Jurado</dt>'+
						'<dd>'+respuesta["Coord"][i]+'</dd>'+
						'<dd>'+respuesta["Ppal1"][i]+'</dd>'+
						'<dd>'+respuesta["Ppal2"][i]+'</dd>'+
						'<dd>'+respuesta["Supl1"][i]+'</dd>'+
						'<dd>'+respuesta["Supl2"][i]+'</dd>'+
					'</dl>';
					
				document.getElementById("juradoCoord").value = respuesta["Coord"][i]
				document.getElementById("juradoPpal1").value = respuesta["Ppal1"][i];
				document.getElementById("juradoPpal2").value = respuesta["Ppal2"][i];
				document.getElementById("juradoSpl1").value = respuesta["Supl1"][i];
				document.getElementById("juradoSpl2").value = respuesta["Supl2"][i];
					
				$('#detallesSolicitudSeleccionada').append(contenidoHTML);
            }
		}
    });
    $.ajax({
		method:"POST",
		url: "/web/app_dev.php/preparadores/validar_recaudo",
		dataType: 'json',
		data: {"idTramite":id, "nombreRecaudo":"AprobarPresupuesto"},
        success: function(respuesta){
			if (respuesta.estado == "NoExisteRecaudo"){
				$('#divAprobarPresupuesto').removeClass("hide");
            }else if (respuesta.estado == "YaExisteRecaudo"){
            	// if(respuesta.valorRecaudo == "Si"){
					$.ajax({
						method:"POST",
						url: "/web/app_dev.php/preparadores/validar_recaudo",
						dataType: 'json',
						data: {"idTramite":id, "nombreRecaudo":"AprobarJurado"},
				        success: function(respuesta){
							if (respuesta.estado == "NoExisteRecaudo"){
								$('#divAprobarJurado').removeClass("hide");
				            }else if (respuesta.estado == "YaExisteRecaudo"){
				            	if(respuesta.valorRecaudo == "Si"){
									$.ajax({
										method:"POST",
										url: "/web/app_dev.php/preparadores/validar_recaudo",
										dataType: 'json',
										data: {"idTramite":id, "nombreRecaudo":"Veredicto"},
								        success: function(respuesta){
											if (respuesta.estado == "NoExisteRecaudo"){
												$('#divVeredicto').removeClass("hide");
								            }else if (respuesta.estado == "YaExisteRecaudo"){
								            	// if(respuesta.valorRecaudo == "Si"){
													$.ajax({
														method:"POST",
														url: "/web/app_dev.php/preparadores/validar_recaudo",
														dataType: 'json',
														data: {"idTramite":id, "nombreRecaudo":"FechaRecepDoc"},
												        success: function(respuesta){
															if (respuesta.estado == "NoExisteRecaudo"){
																$('#divFechaRecepDoc').removeClass("hide");
												            } 
														}
												    });
								            	// }
											}
								        }
								    });
				            	}else if(respuesta.valorRecaudo == "No"){
									$.ajax({
										method:"POST",
										url: "/web/app_dev.php/preparadores/validar_recaudo",
										dataType: 'json',
										data: {"idTramite":id, "nombreRecaudo":"CambioJurado"},
								        success: function(respuesta){
											if (respuesta.estado == "NoExisteRecaudo"){
												$('#divCambiarJurado').removeClass("hide");
								            } 
										}
								    });
				            	}
				            } 
						}
				    });
            	// }
            } 
		}
    });
    
});

$('#enviarAprobarPresupuesto').click(function (){ 
	
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsPresupuesto");
	
	toastr.clear();

    for(var i=0;i<optionsRadios.length;i++)
    {
        if(optionsRadios[i].checked)
            optionSelected = optionsRadios[i].value;
    }
	
	if(optionSelected == ""){
        falla = true;
        text = "Debe seleccionar una opción.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"AprobarPresupuesto", "valorRecaudo":optionSelected},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divAprobarPresupuesto').addClass("hide");
        		}
        }});
    }
});

$('#enviarAprobarJurado').click(function (){ 
	var falla = false;
	var text = "";
	var optionSelected = "";
	var optionsRadios = document.getElementsByName("optionsJurado");
	
	toastr.clear();

    for(var i=0;i<optionsRadios.length;i++)
    {
        if(optionsRadios[i].checked)
            optionSelected = optionsRadios[i].value;
    }
	
	if(optionSelected == ""){
        falla = true;
        text = "Debe seleccionar una opción.";
        toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
    }else{
		$.ajax({
            method: "POST",
            data: {"idTramite":id, "nombreRecaudo":"AprobarJurado", "valorRecaudo":optionSelected},
            url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
            dataType: 'json',
            beforeSend: function(){
                $("#cargando").modal("show");
            },
	        success: function(respuesta){
        		if (respuesta.estado == "Insertado") {
        		    $("#cargando").modal("hide");
        		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
    				$('#divAprobarJurado').addClass("hide");
    				if (optionSelected == "No") {
    					$('#divCambiarJurado').removeClass("hide");
            		}else if (optionSelected == "Si") {
            			$('#divVeredicto').removeClass("hide");
            		}
        		}
        }});
    }
});

// $('#enviarAprobarJurado').click(function (){ 
// 	var falla = false;
// 	var text = "";
// 	var optionSelected = "";
// 	var optionsRadios = document.getElementsByName("optionsJurado");
	
// 	toastr.clear();

//     for(var i=0;i<optionsRadios.length;i++)
//     {
//         if(optionsRadios[i].checked)
//             optionSelected = optionsRadios[i].value;
//     }
	
// 	if(optionSelected == ""){
//         falla = true;
//         text = "Debe seleccionar una opción.";
//         toastr.error(text, "Error!", {"timeOut": "0","extendedTImeout": "0"});
//     }else{
// 		$.ajax({
//             method: "POST",
//             data: {"idTramite":id, "nombreRecaudo":"AprobarJurado", "valorRecaudo":optionSelected},
//             url: "/web/app_dev.php/preparadores/agregar_recaudo_solicitud",
//             dataType: 'json',
//             beforeSend: function(){
//                 $("#cargando").modal("show");
//             },
// 	        success: function(respuesta){
//         		if (respuesta.estado == "Insertado") {
//         		    $("#cargando").modal("hide");
//         		    toastr.success(respuesta.mensaje, "Éxito!", {"timeOut": "0","extendedTImeout": "0"});
//     				$('#divAprobarJurado').addClass("hide");
//     				if (optionSelected == "No") {
//     					$('#divCambiarJurado').removeClass("hide");
//     					document.getElementById("juradoCoord").value = respuesta.jurados["Coord"][0];
//     					document.getElementById("juradoPpal1").value = respuesta.jurados["Ppal1"][0];
//     					document.getElementById("juradoPpal2").value = respuesta.jurados["Ppal2"][0];
//     					document.getElementById("juradoSpl1").value = respuesta.jurados["Supl1"][0];
//     					document.getElementById("juradoSpl2").value = respuesta.jurados["Supl2"][0];
//             		}else if (optionSelected == "Si") {
//             			$('#divVeredicto').removeClass("hide");
//             		}
//         		}
//         }});
//     }
// });

//Date picker
$('#datepicker').datepicker({
  autoclose: true
});

/**
 * @param String name
 * @return String
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}