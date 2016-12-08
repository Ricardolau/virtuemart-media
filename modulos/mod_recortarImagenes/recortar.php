<?php
/* El objetivo es recortar imagenes que no sean cuadradas.
 * Recortando al tamaño más grande posible.
 * Creando una imagen cuadrada en una carpeta que le indiquemos.
 */
 
 

//~ echo '<pre>';
//~ print_r($Imagenes);
//~ echo '</pre>';

?>

<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
?>
<!-- Script acciones de usuario -->
<script>
function metodoClick(pulsado){
	    console.log("Inicimos switch de control pulsar");
	    switch(pulsado) {
			case 'LimpiarCopiar':
				console.log('Entro en LimpiarCopiar');
				// Cargamos variable global ar checkID = [];
				LimpiarCopiar ();
				break;
			case 'OtraAccion':
				// Obtenemos puesto en input de Buscar
				console.log('OtraAccion');
				break;
			default:
				alert('Error pulsado incorrecto');
			}
}
function LimpiarCopiar(){
	var respuestaConf = confirm('Copiar y Limpiar directorios trabajo');
	if (respuestaConf == true) {;
		alert('Si');
	}
}
</script>
</head>
<body>
<?php 
	include './../../header.php';
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
 foreach ($files as $file){
	 // Llamamos a funcion con ruta fichero ...
	 $DatosImagen = DatosImagen($file);
	 // Si la imagen es cuadrada o el fichero no es una imagen no la añadimos
	switch (true){
		case (!empty($DatosImagen['error'])):
			// Hay el parametro error entonces continuamos con foreach.
			$y= $y+1;
			$FilesErroneos [$y] = $DatosImagen;
			continue;
		default:
			$x= $x+1;
			$Imagenes [$x] = $DatosImagen;
	}
 }
?>

	<div class="container">
		<div class="col-md-8">
			<h1>Vamos recortar y redimensionar imagenes </h1>
			<p>La imagenes vamos a tratar son las imagenes que tenemos en el directorio <strong><?php echo $DirImagOriginales;?></strong>.NO tratamos directamente las imagenes virtuemart.</p>
			<p> Recortamos las imagenes tanto si son "Panoramicas o Verticales" en cuadradas.</p>
			<div class="alert alert-info">
				<strong>RECUERDA:</strong>
				<p>No se trata las imagenes que ya exista imagen redimensionada en la ruta destino:<strong><?php echo $DirImagRecortadas;?></strong>.</p>
				</div>
			<h3>Parametros que tiene por defecto</h3>
			<p><strong>Nombre de servidor:</strong> <?php echo $NombreServidor;?></p>
			<p><strong>Ruta de servidor:</strong> <?php echo $RutaServidor;?></p>
			<p><strong>La medida final de la imagen:</strong> <?php echo $sufijo;?></p>
			<p><strong>Ficheros encontrado en destino:</strong> <?php echo count($files);?></p>
			<p><strong>Imagenes a tratar:</strong> <?php echo count($Imagenes);?><br/>
			Aquellas ficheros que son imagenes , que no son cuadradas ya.
			</p>
			<p><input type="submit" value="Actualiza y limpiar Directorios destino y origen" onclick="metodoClick('LimpiarCopiar');"> 
			<br/>Eliminar las imagenes que hay en directorios trabajo ( destino y origen) y copias las imagenes que tenemos en la instalación virtuemart local.</p>
			<h2>Listado de ficheros erroneos</h2>
			<p> Revisamos si el fichero es una imagen y si es gif, jpg o png, si no es entonces lo registramos como un fichero erroneo.</p>
			<p>Hemos encontrado <?php echo count($FilesErroneos);?> que listamos a continuación:</p>
			<?php
			foreach ($FilesErroneos as $ficheroError)
			{
			echo '<p><strong>'.$ficheroError['nombre'].$ficheroError['extension'].'</strong>';
			echo $ficheroError['error'].'</p>';
			}
			?>
		</div>
		<div class="col-md-4">
						<h2>Listado de imagenes a tratar</h2>
			<p> Un total imagenes a tratar de <?php echo count($Imagenes);?></p>
			<?php
			$c=0;
			$p=0;
			$v=0;
			foreach ($Imagenes as $imagen)
			{
			?>	
	<!--
				<p><strong><?php echo $imagen['nombre'].$imagen['extension'];?></strong>
	-->
				<?php 
					 if  (empty($imagen['error'])) 
					{?>
						<?php // Ahora vamos a recortar solo aquellas imagenes que no existan ya en directorio de minuaturas ( recortadas)
							if (!file_exists($RutaServidor.$DirImagRecortadas.$imagen['nombre'].$sufijo.$imagen['extension'])) {
								 // No existe la imagen recortada, ejecutamos funcion de recortar.
								 // La funcion recortar se llama RecortarImagenC
								 // enviando array de imagen, el nuevo destino.
								 $DestinoRe =	$RutaServidor.$DirImagRecortadas;
								 // y el sufijo , que pusimos en datos inciales.
								 if ($imagen['tipoimagen'] == 'C'){
											$c=$c+1;
								}
								 if ($imagen['tipoimagen'] == 'V'){
											$v=$v+1;
								}
								 if ($imagen['tipoimagen'] == 'P'){
											$p=$p+1;
								}

								 RecortarImagenC ($imagen,$DestinoRe,$sufijo);?>
	<!--
								<span> Recorte y redimensionada </span>
	-->
							<?php
							} else {?>
							<p><strong><?php echo $imagen['nombre'].$imagen['extension'];?></strong>
							<?php
							echo '<span>NO RECORTA , POR EXISTE MINIATURA </span>';

							}
							?>
						<?php
					} else {
					echo '<span>NO es correcta la imagen, formato incorrecto. </span>';
					}?>
				</p>
			<?php
			}?>
			<?php 
			echo ' IMAGENES RECORTADAS ';
			echo 'Imagenes cuadradas:'.$c.'<br>';
			echo 'Imagenes vertical:'.$v.'<br>';
			echo 'Imagenes panoramicas:'.$p.'<br>';
			?>
		</div>
	
	</div>
	
</body>
</html>

