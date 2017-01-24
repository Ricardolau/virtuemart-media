<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
?>
<!-- Script acciones de usuario -->
<script>
// Declaramos variables globales javascript
var checkID = [];
var nombreFichero = [];
var extensionFichero = [];
var tipoFichero = [];
var anchoFichero = [];
var altoFichero = [];

// Funcion para controlar lo pulsado (botones y link )
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
				// Comprobamos seleccion
				VerFicheroSeleccionado ();
				if (checkID.length == 0 ){
					var respuestaCheck = confirm( 'No hay ninguno seleccionado, \n ¿ Quieres redimensionar todas ?');
					
					if (respuestaCheck == true ) {
					// Queremos redimensionar todas, los vamos hacer es:
					// Lo primero que debemos hacer saber si estamos en la pagina de inicio.
					// Luego Marcartodas() para redimensionar, hacerlo..
					// y luego pasara a la pagina siguiente
					// Así hasta el final.
					MarcarTodas();
						
					}
				
				
				// Ahora deberiamos crear array de todos los check que aparecen en pantalla.( Tanto como si hay o no paginacion )
				// Si hay paginación debería poder indicar de alguna forma todos o solo la pagina.
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
			default:
				alert('Error pulsado incorrecto');
			}
}
function Eliminar(){
	// Antes de nada debemos ver cuanto tenemos seleccionado.
	VerFicheroSeleccionado ()
	console.log('Va preguntar');

	if (checkID.length == 0 ){
				var respuestaEliminar = confirm( 'No hay ninguno seleccionado, \n ¿ Quieres eliminar todos ?')
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
		// Quiere decir que esta marcado, por lo que desmarcamos.
		valor = true;
	 } else {
		// Quiere decir que marcamos todos.
		valor = false;
		
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



</script>
</head>
<body>
<?php 
	include './../../header.php';
	include 'funciones.php';
	// Variables reiniciadas
	$ficheros = array();
	$ficheroserroneos = array ();
	$sufijo = '_'.$ImgAltoCfg.'x'.$ImgAnchoCfg;
	?>
	<?php
	//Saber cuantos fichero hay en origen sin importar si son imagenes.
	$CantidadFicheros = count(scandir($RutaServidor.$DirImagOriginales));
	$CantidadMiniaturas = count(scandir($RutaServidor.$DirImagRecortadas));
	$LimitePagina = 50;
	// Cargamos fichero de paginado, esto debería se una función donde enviamos 
	// 	total items
	//  limite paginacion 
	// 	pagina actual ¿?
	// 	url 
	// NOTA: De momento es un fichero.. 
	// 	Este nos devuelve $HtmlPG que lo imprimimos donde queramos.
	include 'paginado.php';
	// Los datos solo vamos a CARGAR y MOSTRAR LOS DE LA PAGINA.
	// Ahora tenemos crear array de imagenes, con las siguiente extructura:
		 //  [NumeroImagen] Array
		 //           [nombre] => nombre.extension
		 //           [ancho] => 500
		 //           [alto] => 375
		 //           [tipoimagen] => 'C' cuadrada, 'P' panoramica, 'V' verticalimage/jpeg
		 //           [tipofichero] => 1,2,3,4,5,6 ( gif,jpg,png y más extensiones pero no la utilizo) ver funcion exif_imagetype()
		 //			  [comprobacion] => Es el estado en la tabla, con el indicamos
		 //									"Existe Miniatura" 
		 //									"No existe Miniatura"
		 //							Los siguiente estados , lo ponemos en la tabla cuando:
		 // 								"Eliminado Miniatura"
		 //									"Nuevo" cuando la acabamos de crear.
	 $files = array_filter(glob($RutaServidor.$DirImagOriginales."*"), 'is_file');
	 // Files es un array con solo ficheros del directorio que indicamos.
	 // Esto implica que no todas son imagenes, listamos al final de la tabla.
	 $x=0;// Contador para imagenes correctas.
	 $y=0;// Contador para ficheros o imagenes erroneas.
	 // Creamos array que vamos utiliza ( $ficheros ) y ($ficheroserroneos)
	 $final		=$paginas['Actual'] * 50;
	 $inicio	=$final-$LimitePagina;
	 // Recorremos files son los de pagina que estamos.
	 for ($i = $inicio; $i < $final; $i++) {
		$file = $files[$i];
		 // Llamamos a funcion con ruta fichero ...
		 // donde comprueba 
		 $DatosImagen = DatosImagen($file);
		 // El fichero no es una imagen no la añadimos
		switch (true){
			case (!empty($DatosImagen['error'])):
				// Hay el parametro error entonces continuamos con foreach.
				$y= $y+1;
				$ficheroserroneos [$y] = $DatosImagen;
				continue;
				
			case ($DatosImagen['extension'] == '.jpeg'):
				// Si la extension es .jpeg entonces lo marcamos como fichero erroneo.
				$y= $y+1;
				$ficheroserroneos [$y] = $DatosImagen;
				continue;
			default:
				// Es una imagen
				// Ahora comprobamos que su estado.
				$fileMiniatura =$RutaServidor.$DirImagRecortadas.$DatosImagen['nombre'].$sufijo.$DatosImagen['extension'];
				
				if (file_exists ( $fileMiniatura)) {
					$Estado = 'Existe Miniatura';
				} else {
					$Estado = 'No existe Miniatura';

				}
				
				$DatosImagen['estado'] = $Estado;
				// Añadimos a array $ficheros.	
				$x= $x+1;
				$ficheros [$x] = $DatosImagen;
				// Ahora sabemos que exist
		}
		//~ echo '<pre>';
		//~ print_r($DatosImagen);
		//~ echo '</pre>';
	 }
	 
	 
		 $DestinoRe =	$RutaServidor.$DirImagRecortadas;
		
	//~ }?>

	<div class="container">
		
		<div class="col-md-8">
			<h1>Vamos recortar y redimensionar imagenes </h1>
			<p>La imagenes vamos a tratar son las imagenes que tenemos en el <strong>directorio origen</strong> que asignamos en configuracion.Recomiendo no poner el directorio de virtuemart directamente.</p>
			<div class="col-md-7">
				<h3>Parametros que tiene por defecto</h3>
				<ul>
				<li><strong>Nombre de servidor:</strong> <?php echo $NombreServidor;?></li>
				<li><strong>Ruta de servidor:</strong> <?php echo $RutaServidor;?></li>
				<li><strong>Directorio de imagenes Originales:</strong><?php echo $DirImagOriginales;?></li>
				<li><strong>Directorio destino de redimensiones:</strong><?php echo $DestinoRe ;?></li>
				<li><strong>La medida final de la imagen:</strong> <?php echo $sufijo;?></li>
				</ul>
			</div>
			<div class="col-md-5">
			<h3>Recuerda que:</h3>
			<ul>
			<li>Convierte las imagenes <strong>Panoramicas y verticales a cuadradas</strong>, desde el centroy luego las redimensiona, para ello las recorta en el centro.</li>
			<li>La imagenes cuadradas solo las redimensiona a las medidas que le indicamos en configuracion</li>
			</ul>
			
			</div>
		</div>
		<div class="col-md-4">
			<h2>Procesando</h2>
			<p>Aquí indicaremos lo que estamos realizando y hemos comprobado. El problema que encuentro, es como hacerlos si AJAX o con antes de cargar el html</p>
			<p>El primer caso es interesante para ganar rapidez, el problema es que se va repetir cada vez que recarguemos la pagina.</p>
			<div>Ficheros encontrados en Origen: <span class="label label-default"><?php echo count($files);?></span></div>
			<div>Ficheros encontrados en Redimension: <span class="label label-default"><?php echo $CantidadMiniaturas;?></span></div>

			<h4>Proceso</h4>
			<p>Aquí debería estar barra proceso.</p>
			<div id="procesando">INACTIVO</div>
			
			
		</div>
		<div class="col-md-12">
			
			<div style="float:left;margin-left:20px;">Imagenes <span class="label label-default"><?php echo count($ficheros);?></span></div>
			
			<div style="float:left;margin-left:20px;">
				<a href="#ListadoFicherosErroneos" onclick="metodoClick('ListaFicherosErroneos');">No imagenes <span class="badge"><?php echo count($ficheroserroneos);?></span></a>
			</div>
			<div><hr></div>
			<div id="ListadoFicherosTratar">
			<h2>Listado de imagenes a tratar</h2>

			<div class="col-md-6">
				<input type="submit" value="Crear Miniatura" onclick="metodoClick('Redimensionar');"> 
			</div>
			<div class="col-md-6">
				<input type="submit" value="Eliminar Miniaturas" onclick="metodoClick('Eliminar');"> 
			</div>
			<?php  // Mostramos el paginado. 
			echo $htmlPG; 
			?>

			<div class="ficheros-tratar">
			<table class="table">
				<thead>
					<tr>
						<th>Id
						<input type="checkbox" name="checkTotal" value="0" onchange="metodoClick('TodaSeleccion');">
						</th>
						<th>Nombre Fichero</th>
						<th>extension</th>
						<th>Tipo</th>
						<th>Alto</th>
						<th>Ancho</th>
						<th>Estado</th>
					</tr>
					</thead>
					<tbody>
						<?php
										
						$x=0;
						foreach ($ficheros as $imagen)
						{
							if  (empty($imagen['error'])) 
							{
								$class="";
								// Vamos utilizar class, para cambiar el colo de fila, si se produce alguno de estos casos:
								// 		- Si no es png,jpg,gif , ya virtuemarta genera un error.
								// 		- Si no tiene miniatura.
								
								$x= $x+1;
								?>
								<tr>
								<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $x;?>" value="<?php echo $x;?>"></td>
								<td><span id="nombreFic<?php echo $x;?>"><?php echo $imagen['nombre'];?></span></td>
								<td><span id="extensionFic<?php echo $x;?>"><?php echo $imagen['extension'];?></span></td>
								<td><span id="tipoFic<?php echo $x;?>"><?php echo $imagen['tipoimagen'];?></span></td>
								<td><span id="anchoFic<?php echo $x;?>"><?php echo $imagen['ancho'];?></span></td>
								<td><span id="altoFic<?php echo $x;?>"><?php echo $imagen['alto'];?></span></td>
								
								<td><span id="estadoFic<?php echo $x;?>"><?php echo $imagen['estado'];?></span></td>

								</tr>
							<?php
							}
						}?>
								
				</tbody>
			</table>
			</div>
			</div>
			<!-- Ahora mostramos ficheros erroneos. -->
			<div  id="ListadoFicherosErroneos">
			<h2>Listado ficheros erroneos</h2>
			<p> Revisamos si el fichero es una imagen (gif, jpg o png), si es otra extension entonces lo registramos como un fichero erroneo.</p>
			<?php
			foreach ($ficheroserroneos as $ficheroError)
			{
			echo '<pre>';
			print_r($ficheroError);
			echo '</pre>';
			}
			?>
			
			
			
			
			</div>
			
			
			
			
			
			
		</div>
		
	
	</div>
	
</body>
</html>

