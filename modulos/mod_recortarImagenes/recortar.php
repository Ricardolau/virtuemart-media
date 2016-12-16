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
 // Creamos array que vamos utiliza ( $ficheros ) y ($ficheroserroneos)
 $ficheros = array();
 $ficheroserroneos = array ();
 
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
 }
 $DestinoRe =	$RutaServidor.$DirImagRecortadas;
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
			
		</div>
		<div class="col-md-4">
			<h2>Listado de ficheros erroneos</h2>
			<p> Revisamos si el fichero es una imagen y si es gif, jpg o png, si no es entonces lo registramos como un fichero erroneo.</p>
			<p>Hemos encontrado <?php echo count($FilesErroneos);?> que listamos a continuación:</p>
			<?php 
			echo ' IMAGENES RECORTADAS '.'<br>';
			echo 'Imagenes cuadradas:'.$c.'<br>';
			echo 'Imagenes vertical:'.$v.'<br>';
			echo 'Imagenes panoramicas:'.$p.'<br>';
			?>
			<?php
			foreach ($ficheroserroneos as $ficheroError)
			{
			echo '<p><strong>'.$ficheroError['nombre'].$ficheroError['extension'].'</strong>';
			echo $ficheroError['error'].'</p>';
			}
			?>
			
		</div>
		<div class="col-md-12">
			<p><strong>Ficheros encontrado en destino:</strong> <?php echo count($files);?></p>
			<p><strong>Imagenes a tratar:</strong> <?php echo count($Imagenes);?><br/>
			Aquellas ficheros que son imagenes , que no son cuadradas ya.
			</p>
			<p><input type="submit" value="Actualiza y limpiar Directorios destino y origen" onclick="metodoClick('LimpiarCopiar');"> 
			<br/>Eliminar las imagenes que hay en directorios trabajo ( destino y origen) y copias las imagenes que tenemos en la instalación virtuemart local.</p>
			<h2>Listado de imagenes a tratar</h2>
			<p> Un total imagenes a tratar de <?php echo count($ficheros);?></p>
			<p> Directorio destino:<?php echo $DestinoRe;?></p>
			<?php // Funcion para recortar imagen
			// RecortarImagenC ($imagen,$DestinoRe,$sufijo,$ImgAltoCfg, $ImgAnchoCfg)
			?>
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
								<td><?php echo $x;?></td>
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
	
</body>
</html>

