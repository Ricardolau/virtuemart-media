function comprobarProductos() {
	// Script que utilizamos para ejecutar funcion de php.
	var parametros = {
	 "pulsado" 	: 'comprobarProductos'
			};
	$.ajax({
		data:  parametros,
		url:   'tareas.php',
		type:  'post',
		beforeSend: function () {
				$("#resultado").html('Comprobando productos....<span><img src="./img/ajax-loader.gif"/></span>');
				$("#PSImagen").html('<img src="./img/ajax-loader.gif"/>');
				$("#PMImagen").html('<img src="./img/ajax-loader.gif"/>');

		},
		success:  function (response) {
				// Cuando se recibe un array con JSON tenemos que parseJSON
				//~ var resultado =  $.parseJSON(response)
				$("#proceso").html('Terminado la comprobacion de productos');
				$("#PSImagen").html(response.SinIDMedia);
				$("#PMImagen").html(response.ErrorImagen);
		}
	});
}
