function procesosPendientes() {
	// Aquí lo que hacemos es ejecutar los procesos que tardan
	textoMostrar = "Buscando proceso ....";
	$("#proceso").html(textoMostrar);
	// Iniciamos contador;
	contador = setInterval(ponSegundero, 1000); 
	
	return;
	}

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
function controlCiclo(){
	// Buscamos el indice del array de procesos
	if (ProcesoActual != '') {
		var Nindice = arrayProcesos.indexOf(ProcesoActual);
		// Si el resultado es -1 quiere decir que esta fuera rango.
		if (Nindice >= 0){
			console.log('Proceso Actual:'+ProcesoActual+' indice.'+Nindice);
			Nindice = Nindice + 1
			ProcesoActual = arrayProcesos[Nindice];
		}
		
	} else {
		// Empezamos el primer proceso.
		ProcesoActual = arrayProcesos[0];
	}
	console.log(segundero);
	// Si el segundero esta al maximos permitido (10) o Nindice es menor 0 , quiere decir que termino
	if (segundero > 10  || Nindice< 0) {
		clearInterval(contador);
		$("#proceso").html('Termino ultimo proceso o por tiempo '+segundero +' s');

		alert('termino procesos');
	}
	// Swich de procesos...
	switch(ProcesoActual) {
		case 'ImagenesQueNoseUtilizan':
			ImagenesQueNoseUtilizan();
			break;
		
		
	} 
	
}
	
function ponSegundero(){
	segundero= segundero +1;
	$("#proceso").html('Iniciamos '+ProcesoActual+' tiempo '+segundero +' s');
	// Iniciamos ciclo procesos
	controlCiclo();
	return;
	
}

function ImagenesQueNoseUtilizan(){
	// Funcion para ver se utilizan todos los ficheros del directorio.
	var parametros = {
	 "pulsado" 	: 'ImagenesQueNoseUtilizan',
	 "Nom_Dir_actual":	NomDirActual
			};
	$.ajax({
		data:  parametros,
		url:   'tareas.php',
		type:  'post',
		beforeSend: function () {
				$("#proceso").html('Comprobando si las imagenes del directorio '+NomDirActual+' se utilizan<span><img src="./img/ajax-loader.gif"/></span>');
				
		},
		success:  function (response) {
				// Cuando se recibe un array con JSON tenemos que parseJSON
				//~ var resultado =  $.parseJSON(response)
				$("#proceso").html('Terminado la comprobacion si las imagenes del directorio '+NomDirActual+' se utilizan');
				//arrayConsulta = response;
				//~ if (arrayConsulta['TotalProductos'] > 0 ){
					//~ NEnviado = 0
					//~ ciclo();
				//~ }
		}
	});
	return;
	
}
