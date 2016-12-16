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
// Funcion para controlar lo pulsado (botones y link )
function metodoClick(pulsado){
	    console.log("Inicimos switch de control pulsar");
	    switch(pulsado) {
			case 'LimpiarCopiar':
				console.log('Entro en LimpiarCopiar');
				// Cargamos variable global ar checkID = [];
				LimpiarCopiar ();
				break;
			case 'Redimensionar':
				// Primero deberíamos leer si tiene check marcados
				console.log('Antes redimensionar comprobamos cuantos seleccionado');
				// Comprobamos seleccion
				VerFicheroSeleccionado ()
				console.log('Seleccionado:'+checkID.length);
				if (checkID.length == 0 ){
				var respuestaCheck = confirm( 'No hay ninguno seleccionado, \n ¿ Quieres redimensionar todas ?')
				
				}
				// Funcion para redimensionar
				Redimensionar();
				break;
			case 'ListaFicherosErroneos':
				// Ocultamos listado de ficheros a tratar
				$('#ListadoFicherosTratar').hide(1000);
				$('#ListadoFicherosErroneos').show();
				break;
			default:
				alert('Error pulsado incorrecto');
			}
}
function LimpiarCopiar(){
	var respuestaLimp = confirm('Vamos eliminar todos los ficheros redimensionados');
	if (respuestaConf == true) {;
		alert('Si');
	}
}
// Funcion para redimensionar imagenes
function Redimensionar(){
	// Antes de nada necesito todos los datos ficheros seleccionado para enviar
		
	var respuestaRedi = confirm('Si existe la imagen redimensionada la va sustituir por la nueva,\n ¿ Quiere verdad ?');
	if (respuestaRedi == true)
	{;
		
		// Ahora mandamos tareas realizar.
		 var parametros = {
					'pulsado': 'Redimensionar'
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
				respuesta = response[0];
				$("#procesando").html('RHola');
				console.log(response[0]);
			}
		});
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
					// cant cuenta los que está seleccionado.
					valor = '0';
					valor = $('input[name=checkFic'+i+']').val();
					checkID.push( valor );
					// Ahora tengo hacer array con id...
				}
				
			});
			console.log('ID de Ficheros seleccionado:'+checkID);
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
	if ($CantidadFicheros < 500) 
	{
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
					echo 'Entro ..............................................';
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
		
	}?>

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
				<input type="submit" value="Eliminar Redimensiones" onclick="metodoClick('LimpiarCopiar');"> 
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
								<td><?php echo $imagen['nombre'];?></td>
								<td><?php echo $imagen['extension'];?></td>
								<td><?php echo $imagen['tipoimagen'];?></td>
								<td><span id="<?php echo 'ancho-fic'.$x;?>"><?php echo $imagen['ancho'];?></span></td>
								<td><span id="<?php echo 'alto-fic'.$x;?>"><?php echo $imagen['alto'];?></span></td>
								<?php //Estado ponemos :
								// Si no el fichero es un error.
								// Si ya existe la miniatura
								// Cuando se realiza , se pone hecha.
								$Estado = "";
								if  (!empty($imagen['error'])) 
								{
									$Estado = $imagen['error'];
								}
								?>					
								<td><?php echo $Estado;?></td>

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

