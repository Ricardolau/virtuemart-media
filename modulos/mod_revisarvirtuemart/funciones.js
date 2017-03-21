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
				$("#proceso").html('Terminado la comprobacion de productos y obtener ID de media');
				arrayConsulta = response;
				//~ if (arrayConsulta['TotalProductos'] > 0 ){
					//~ NEnviado = 0
					//~ ciclo();
				//~ }
		}
	});
	return;
}
function ciclo(){
	if (NEnviado <= arrayConsulta['TotalProductos']){
		var ItemsEnviar = [];
		for (i = 0; i < 1200; i++) {  
			if (NEnviado <= arrayConsulta['TotalProductos']){
				// Montamos array para enviar por AJAX
				ItemsEnviar[i] = arrayConsulta[NEnviado];
				NEnviado = NEnviado + 1;
			}
		}
		// Ahora ejecutamos ajax ProductosImagen
		var parametros = {
			'pulsado': 'ProductosImagen',
			'ArrayEnviado':ItemsEnviar
			
		};
		$.ajax({
			data: parametros,
			url: 'tareas.php',
			type: 'post',
			datatype: 'json',
			beforeSend: function () {
				textoMostrar = "Comprobando Imagenes de Productos";
				$("#proceso").html(textoMostrar);

			},
			success: function (response) {
				alert("estoy en respuesta");
				textoMostrar = "Terminamos este bloque productos";
				$("#proceso").html(textoMostrar);
				console.log('Resultado')
				console.log('Total enviado'+ response['countArray']);
				console.log('Total arrayConsulta' +arrayConsulta['TotalProductos']);
				console.log(response.toSource());
			}
		});
		
		
		

	
	}
}
