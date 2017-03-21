function metodoClick(pulsado){
	    console.log("Inicimos switch de control pulsar");
	    switch(pulsado) {
			case 'Eliminar':
				console.log('Eliminar');
				// Cargamos variable global ar checkID = [];
				Eliminar ();
				break;
			case 'Redimensionar':
				// Primero deberíamos leer si tiene check marcados
				console.log('Antes redimensionar comprobamos cuantos seleccionado');
				// Comprobamos si tiene selecionado CheckTotasPaginas ya que entonces cambiar el proceso.
				if($('input[name=checkTotalPaginas]').is(':checked')){
				   	if (confirm('Tienes seleccionado operar con todas las paginas \n ¿ Estas seguro ?')){
						alert( ' COnfirmaste.. ahora operar... ');
					} else {
						// No lo confirmo por lo que desmarcamos opcion todas las paginas.
						$('input[name=checkTotalPaginas]').prop("checked", false);
					}
				}
				VerFicheroSeleccionado ();
				if (checkID.length == 0 ){
					alert( 'No hay ninguno seleccionado, \n ¿ Debes seleccionar que ficheros quieres redimensionar ?');
					// Volvemos
					return;			
				}
				// Funcion para redimensionar
				if (checkID.length > 0) {
					// Antes de enviar a redimensionar, debemos saber si son muchos
					// ya que si son muchos debemos hacerlo en varios procesos, no en uno..
					// De momento hacemos uno solo...
					CuantosFicheros = checkID.length
					Redimensionar(CuantosFicheros);
				} else {
						alert ( ' No hay ninguno selecionado, no continuamos ');
				} 
				break;
			case 'ListaFicherosErroneos':
				// Llegamos aquí pulsado en link de Ficheros Erroneos (numero) 
				// Ocultamos listado de ficheros a tratar
				$('#ListadoFicherosTratar').hide(1000);
				$('#ListadoFicherosErroneos').show();
				break;
				
			case 'TodaSeleccion':
				// Quiere decir que pulso check de toda la seleccion.
				MarcarTodas();
				break;
				
			case 'TodasPaginas':
				// Marco todas las paginas.
				if (confirm('¿Estas seguro que quieres SELECCIONAR todas las imagenes de todas paginas?')){
					alert('Debería poner diabled a todas check');	
				} else {
					// No lo confirmo por lo que desmarcamos opcion.
					$('input[name=checkTotalPaginas]').prop("checked", false);

				}
				
				break;
			default:
				alert('Error pulsado incorrecto');
			}
}
function Eliminar(){
	// Antes de nada debemos ver cuanto tenemos seleccionado.
	VerFicheroSeleccionado ();
	console.log('Va preguntar');

	if (checkID.length == 0 ){
				var respuestaEliminar = confirm( 'No hay ninguno seleccionado, \n ¿ Quieres eliminar todos ?');
				if (respuestaEliminar == true) {;
				// Ahora deberiamos crear array de todos los check que aparecen en pantalla.( Tanto como si hay o no paginacion )
				// Si hay paginación debería poder indicar de alguna forma todos o solo la pagina.
				}
	}
	
	if ((checkID.length > 0) || (respuestaEliminar == true)){
		// Quiere decir que hay alguno seleccionado o todos.
		// Antes de enviar , tendríamos que saberlo, es decir saber si son todas o solo 
		// las seleccionadas, ya que el proceso es distinto.
		if (respuestaEliminar == true) {;
			
			var parametros = {
				'pulsado': 'EliminarTodos'
				
				};
			$.ajax({
				data: parametros,
				url: 'tareas.php',
				type: 'post',
				datatype: 'json',
				beforeSend: function () {
					$("#procesando").html('Eliminando todas miniaturas de imagenes');
				},
				success: function (response) {
					$("#procesando").html('Terminando la eliminacion de miniaturas'+response);
					
					console.log(response);
				}
			});
		} else {
			// Esto quiere decir que tiene seleccionado ficheros para eliminar su miniatura.
			for (i = 0; i < checkID.length; i++) {
			console.log(checkID[i]);
			var parametros = {
				'pulsado': 'EliminarUno',
				'checkID': checkID[i],
				'nombreFichero': nombreFichero[i],
				'extensionFichero': extensionFichero[i]
				};
			$.ajax({
				data: parametros,
				url: 'tareas.php',
				type: 'post',
				datatype: 'json',
				beforeSend: function () {
					$("#procesando").html('Eliminando miniatura');
				},
				success: function (response) {
					$("#procesando").html('Se elimino la miniatura ');
					// Ahora cambiamos estado a Sin miniatura.
					nombreCampo = '#estadoFic'+response;
					$(nombreCampo).html('Sin miniatura');
					console.log(response);
				}
			});
			
			
			}
			
		}
		
	}
}
// Funcion para redimensionar imagenes
function Redimensionar(CuantosFicheros){
	// CuantosFIcheros lo maximo lo estipulamos con anterioriodad ... ( 50 ficheros por ejemplo)
	// Si son varios la contestación es para todos.
	var respuestaRedi = confirm('Si existe la imagen redimensionada la va sustituir por la nueva,\n ¿ Quiere verdad ?');
	if (respuestaRedi == true)
	{;
		for (i = 0; i < checkID.length; i++) {
			// Ahora mandamos tareas realizar con cada fichero.
			 var parametros = {
						'pulsado': 'Redimensionar',
						'checkID': checkID[i],
						'nombreFichero': nombreFichero[i],
						'extensionFichero': extensionFichero[i],
						'tipoFichero': tipoFichero[i],
						'altoFichero': altoFichero[i],
						'anchoFichero': anchoFichero[i]
						};
			$.ajax({
				data: parametros,
				url: 'tareas.php',
				type: 'post',
				datatype: 'json',
				beforeSend: function () {
					$("#procesando").html('Redimensionando imagen');
				},
				success: function (response) {
					$("#procesando").html('Procesadas imagenes');
					// Ahora ponemos estado NUEVA
					nombreCampo = '#estadoFic'+response['checkID'];
					$(nombreCampo).html('Nuevo');
					//~ document.getElementById(nombreCampo).innerHTML='Nuevo';
					console.log(response['checkID']);
				}
			});
		}
	}
}

function MarcarTodas() {
	// Esta funcion hace es seleccionar o deseleccionar todas las imagenes de la pagina actual
	// si lo tenemos marcado checkTotal, entonces desmarcamos todos.
	checkID = [] ; // Reiniciamos varible global.
	var i = 0;
	var valor= false;
	if($('input[name=checkTotal]').is(':checked')){
		valor = true;
		// Opcion de todas paginas, la activamos
 		$("#checkTotalPaginas").css("display", "block");
		$('input[name=checkTotalPaginas]').prop("disabled", false);

	 } else {
		// Quiere decir que esta marcado, por lo que desmarcamos.
		valor = false;
		// Opcion de todas paginas, bloqueamos
		$("#checkTotalPaginas").css("display", "none");
		$('input[name=checkTotalPaginas]').prop("disabled", true);
		$('input[name=checkTotalPaginas]').prop("checked", false);


		
	 }
	$(".rowCheckFichero").each(function(){ 
		i++;
		//todos los que sean de la clase row1
		$('input[name=checkFic'+i+']').prop("checked", valor);
		console.log('Entro en cambio '+'checkFic'+i);
	});
	
	
	
}
// Funcion para leer los check que se seleccionaron
function VerFicheroSeleccionado (){
		$(document).ready(function()
		{
			// Contamos check están activos.... 
			// Reiniciamos varibles globales.
			checkID = [] ;
			nombreFichero = [];
			extensionFichero = [];
			tipoFichero= [];
			anchoFichero = [];
			altoFichero = [];
			// variable funcion para bucle.			
			var i= 0;
			// Con la funcion each hace bucle todos los que encuentra..
			$(".rowCheckFichero").each(function(){ 
				i++;
				//todos los que sean de la clase row1
				if($('input[name=checkFic'+i+']').is(':checked')){
					// Solo entramos en los que están seleccionado.
					// Ahora tengo hacer array :
					// IDimagenen
					// 		Nombre
					// 		Extension
					// 		Tipo
					// 		Alto
					//		Ancho
					//		Estado

					valor = '0';
					valor = $('input[name=checkFic'+i+']').val();
					checkID.push( valor );
					
					valor = '0';
					nombreCampo = 'nombreFic'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					nombreFichero.push( valor );
					
					valor = '0';
					nombreCampo = 'extensionFic'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					extensionFichero.push( valor );
					
					valor = '0';
					nombreCampo = 'tipoFic'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					tipoFichero.push( valor );
					
					valor = '0';
					nombreCampo = 'anchoFic'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					anchoFichero.push( valor );
					
					valor = '0';
					nombreCampo = 'altoFic'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					altoFichero.push( valor );
					
					
					
				}
				
			});
			console.log('ID de Ficheros seleccionadas:'+checkID);
			console.log('Nombre de Ficheros seleccionadas:'+nombreFichero);
			console.log('Extension de Ficheros seleccionadas:'+extensionFichero);
			console.log('Tipo de Ficheros seleccionadas:'+tipoFichero);
			console.log('Ancho imagen seleccionadas:'+anchoFichero);
			console.log('Alto de Ficheros seleccionadas:'+altoFichero);

			
			return;
		});
}
