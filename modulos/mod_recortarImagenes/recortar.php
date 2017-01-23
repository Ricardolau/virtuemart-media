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
var tipoFichero=[];
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
				VerFicheroSeleccionado ()
				if (checkID.length == 0 ){
				var respuestaCheck = confirm( 'No hay ninguno seleccionado, \n ¿ Quieres redimensionar todas ?')
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
		} else {
			var parametros = {
				'pulsado': 'EliminarUno',
				'checkID': checkID[i],
				'nombreFichero': nombreFichero[i],
				'extensionFichero': extensionFichero[i],
				'tipoFichero': tipoFichero[i],
				'altoFichero': altoFichero[i],
				'anchoFichero': anchoFichero[i]
				};
		}
		console.log('Va ejecutar ajax');
		$.ajax({
				data: parametros,
				url: 'tareas.php',
				type: 'post',
				datatype: 'json',
				beforeSend: function () {
					$("#procesando").html('Eliminando miniaturas de imagenes');
				},
				success: function (response) {
					$("#procesando").html('Terminando la eliminacion de miniaturas'+response);
					
					console.log(response);
				}
			});
		
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
// Funcion para leer los check que se seleccionaron
function VerFicheroSeleccionado (){
		$(document).ready(function()
		{
			// Contamos check están activos.... 
			checkID = [] ; // Reiniciamos varible global.
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
	//Saber cuanto fichero hay en origen
	$CantidadFicheros = count(scandir($RutaServidor.$DirImagOriginales));
	// Variables reiniciadas
	$ficheros = array();
	$ficheroserroneos = array ();
	//~ if ($CantidadFicheros < 500) 
	//~ {
	// Si hay menos 500 archivos continuamos sino no
	?>
		<?php
		 // Variable de inicio y entorno:

		$sufijo = '_'.$ImgAltoCfg.'x'.$ImgAnchoCfg;
		 
		// Recuerda que header.php incluimos el fichero de configuración 
			
		// Incluimos fichero funciones
		 include 'funciones.php';
		 
		 // Creamos array de imagenes, con las siguiente extructura:
		 //  [NumeroImagen] Array
		 //           [nombre] => nombre.extension
		 //           [ancho] => 500
		 //           [alto] => 375
		 //           [tipoimagen] => 'C' cuadrada, 'P' panoramica, 'V' verticalimage/jpeg
		 //           [tipofichero] => 1,2,3,4,5,6 ( gif,jpg,png y más extensiones pero no la utilizo) ver funcion exif_imagetype()
		 //
		 
		 $files = array_filter(glob($RutaServidor.$DirImagOriginales."*"), 'is_file');
		 //  Files es un array con solo ficheros del directorio que indicamos.
		 $x=0;// Contador para imagenes correctas.
		 $y=0;// Contador para ficheros o imagenes erroneas.
		 // Creamos array que vamos utiliza ( $ficheros ) y ($ficheroserroneos)
		 
		 
		 foreach ($files as $file){
			 // Llamamos a funcion con ruta fichero ...
			 // donde comprueba 
			 $DatosImagen = DatosImagen($file);
			 // Si la imagen es cuadrada o el fichero no es una imagen no la añadimos
			switch (true){
				case (!empty($DatosImagen['error'])):
					// Hay el parametro error entonces continuamos con foreach.
					$y= $y+1;
					$ficheroserroneos [$y] = $DatosImagen;
					continue;
				default:
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
			<div class="col-md-6">
				<h3>Parametros que tiene por defecto</h3>
				<ul>
				<li><strong>Nombre de servidor:</strong> <?php echo $NombreServidor;?></li>
				<li><strong>Ruta de servidor:</strong> <?php echo $RutaServidor;?></li>
				<li><strong>Directorio de imagenes Originales:</strong><?php echo $DirImagOriginales;?></li>
				<li><strong>Directorio destino de redimensiones:</strong><?php echo $DestinoRe ;?></li>
				<li><strong>La medida final de la imagen:</strong> <?php echo $sufijo;?></li>
				</ul>
			</div>
			<div class="col-md-6">
			<h3>Recuerda que:</h3>
			<ul>
			<li>Convierte las imagenes <strong>Panoramicas y verticales a cuadradas</strong>, desde el centroy luego las redimensiona, para ello las recorta en el centro.</li>
			<li>La imagenes cuadradas solo las redimensiona a las medidas que le indicamos en configuracion</li>
			</ul>
			
			</div>
		</div>
		<div class="col-md-4">
			<h2>Listado de ficheros erroneos</h2>
			<p> Revisamos si el fichero es una imagen y si es gif, jpg o png, si no es entonces lo registramos como un fichero erroneo.</p>
			<?php 
			echo ' IMAGENES RECORTADAS '.'<br>';
			echo 'Imagenes cuadradas:'.$c.'<br>';
			echo 'Imagenes vertical:'.$v.'<br>';
			echo 'Imagenes panoramicas:'.$p.'<br>';
			?>
			<h4>Proceso</h4>
			<div id="procesando">INACTIVO</div>
			
			
		</div>
		<div class="col-md-12">
			<div style="float:left;margin-left:20px;">Ficheros encontrados <span class="label label-default"><?php echo count($files);?></span></div>
			<div style="float:left;margin-left:20px;">Imagenes <span class="label label-default"><?php echo count($ficheros);?></span></div>
			<div style="float:left;margin-left:20px;">
				<a href="#ListadoFicherosErroneos" onclick="metodoClick('ListaFicherosErroneos');">No imagenes <span class="badge"><?php echo count($ficheroserroneos);?></span></a>
			</div>
			<div><hr></div>
			<div id="ListadoFicherosTratar">
			<h2>Listado de imagenes a tratar</h2>

			<div class="col-md-6">
				<input type="submit" value="Redimensionar" onclick="metodoClick('Redimensionar');"> 
			</div>
			<div class="col-md-6">
				<input type="submit" value="Eliminar Redimensiones" onclick="metodoClick('Eliminar');"> 
			</div>
			<?php // Funcion para recortar imagen
			// RecortarImagenC ($imagen,$DestinoRe,$sufijo,$ImgAltoCfg, $ImgAnchoCfg)
			?>
			<div class="ficheros-tratar">
			<table class="table">
				<thead>
					<tr>
						<th>Id</th>
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
							
								$x= $x+1;
								?>
								<tr>
								<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $x;?>" value="<?php echo $x;?>"></td>
								<td><span id="nombreFic<?php echo $x;?>"><?php echo $imagen['nombre'];?></span></td>
								<td><span id="extensionFic<?php echo $x;?>"><?php echo $imagen['extension'];?></span></td>
								<td><span id="tipoFic<?php echo $x;?>"><?php echo $imagen['tipoimagen'];?></span></td>
								<td><span id="anchoFic<?php echo $x;?>"><?php echo $imagen['ancho'];?></span></td>
								<td><span id="altoFic<?php echo $x;?>"><?php echo $imagen['alto'];?></span></td>
								
								<td><span id="estadoFic<?php echo $x;?>">Sin comprobar</span></td>

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

