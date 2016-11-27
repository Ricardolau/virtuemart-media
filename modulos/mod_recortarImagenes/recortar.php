<?php
/* El objetivo es recortar imagenes que no sean cuadradas.
 * Recortando al tamaño más grande posible.
 * Creando una imagen cuadrada en una carpeta que le indiquemos.
 */
 // Variable de inicio y entorno:
 
 $sufijo = '_401x401';
 

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
</head>
<body>
<?php 
	include './../../header.php';
?>
<?php
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
			<p>La imagenes las recorta, convirtiendo en cuadradas, tanto si son "Panoramicas o Verticales".</p>
			<p> A continuacion las redimensiona a los parametros que le indicamos.</p>
			<h3>Parametros que tiene por defecto</h3>
			<p><strong>Nombre de servidor:</strong> <?php echo $NombreServidor;?></p>
			<p><strong>Ruta de servidor:</strong> <?php echo $RutaServidor;?></p>
			<p><strong>Directorio de Origen:</strong> <?php echo $DirImagOriginales;?><p>
			<p><strong>Directorio de destino:</strong> <?php echo $DirImagRecortadas;?><p>
			<p><strong>La medida final de la imagen:</strong> <?php echo $sufijo;?></p>
			<p>( este directorio, no es la ruta completa, es apartir del dominio o nombre de servidor.)</p>
			</p>
			<p>Recuerda que el script revisa si existe la imagen redimensionada en el directorio destino, por lo que si quiere redimensionar todas las imagenes solo tienes que eliminar las imagenes del destino.</p>
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

