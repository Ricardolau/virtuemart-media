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
	include './../../modulos/mod_conexion/conexionBaseDatos.php';
?>



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
 
 // Creamos array de ficheros que existene en el directorio
 
 $files = array_filter(glob($RutaServidor.$DirInstVirtuemart."*"), 'is_file');
 //  Files es un array con solo ficheros del directorio que indicamos.
 
 // Ahora buscamos en basedatos la imagen.
 
 //~ echo '<pre>';
 
 
 
 //~ echo 'FICHERO NO ENCONTRADOS MEDIA';
 $x = 0;
 $ficheros = array();
 foreach ( $files as $file ){
		
		$fichero=utf8_encode(basename($file)); // Nombre de fichero con extension ..
		// Ahora tenemos que añadirle directorio de sistema
		$fichero = 'images/stories/virtuemart/product/'.$fichero;
		//~ echo $fichero.'<br/>';
		$consultaImgMedia = $BDVirtuemart->query( "SELECT `virtuemart_media_id`,`file_url` FROM `".$prefijoTabla."_virtuemart_medias` where `file_url`= '".$fichero."'");
		if ($consultaImgMedia->num_rows == 0){
		$x= $x +1;
			// Lo anotamos como error , ya que puede que exista, pero el nombre tenga caracteres extraños y no lo encuentre por eso. 
			$ficheros[$x]['error'] = 'No existe en media';
			$ficheros[$x]['Ruta'] = $file;
		} else {
			// Quiere decir que existe en media ..
			$id_media = $consultaImgMedia->fetch_assoc();
			$id_media = $id_media['virtuemart_media_id']; //obtenemos id que vamos buscar en product_media
			// Ahora buscamos en product_media a ver si existe...
			$consultaImgProd = $BDVirtuemart->query( "SELECT * FROM `".$prefijoTabla."_virtuemart_product_medias` WHERE `virtuemart_media_id` =".$id_media);
			if ($consultaImgProd->num_rows == 0){
				// Quiere decir que no existe en producto.
				$x= $x +1;
				$ficheros[$x]['aviso'] = 'No encuenta ID_media:'.$id_media.' en product_media';
				$ficheros[$x]['Ruta'] = $file; 
				$ficheros[$x]['IDmedia']= $id_media;
				//~ echo $fichero. 'ID de media'.$id_media['virtuemart_media_id'].'<br/>';
			}
		
	
			
		}
		
	}
 
 //~ print_r($ficheros);
 
 //~ echo '</pre>';
 
?>

	<div class="container">
		<div class="col-md-8">
			<h1>Imagenes utilizadas en virtuemart ( product) </h1>
			<p>El objetivo es saber que imagenes hay en el directorio de virtuemart/product que no se utilizan en los productos.</p>
			
			<h2>Imagenes que no se utiliza</h2>
			<p> Listado de imagenes que no se encuentrar en tabla product_media.</p>
			<table class="table">
				<thead>
					<tr>
						<th>IDImagen</th>
						<th>Nombre fichero</th>
						<th>IDMedia</th>
					</tr>
				
				</thead>
				<?php
				$x=0;
				foreach ($ficheros as $fichero)
				{
					if (isset($fichero['aviso'])){
						$x= $x+1;
						echo '<tr>';
						echo '<td>'.$x.'</td><td>'.basename($fichero['Ruta']).'</td><td>'.$fichero['IDmedia'].'</td>';
						echo '</tr>';
					}
				}
				?>
			</table>
		</div>
		<div class="col-md-4">
			<h3>Pasos que realizamos</h3>
			<ul>
			<li>Creamos array de ficheros que hay en directorio virtuemart/product</li>
			<li>Buscamos el campo `virtuemart_media_id` en la tabla `$prefijoTabla_virtuemart_medias` que contenga direccion del fichero en el campo 'file_url'.</li>
			<li>Buscamos el id 'vituemart_media_id' en la tabla `$prefijoTabla_virtuemart_product_medias`  para saber si se usa en algún producto, si no se usa entonces </li>
				<ul>
				<li> Comprobamos si existe miniatura.</li>
				<li> ELiminamos imagen y miniatura si existe ( de momento solo mostramos en pantalla)</li>
				</ul>
			</ul>		
			<h2>Listado de ficheros erroneos</h2>
			<p> Estos ficheros no se pueden eliminar directamente.</p>
			<p> Es mejor buscar mano estos ficheros y comprobar si realmente no existem en la tabla media, antes de borrarlos</p>
			<?php
			$x= 0;
			foreach ($ficheros as $fichero)
			{
				if (isset($fichero['error'])){
					$x= $x+1;
					//~ setlocale(LC_CTYPE, 'POSIX');
					//~ setlocale(LC_ALL, 'es_ES');
					//~ $textoFichero = iconv('UTF-8', 'ASCII//TRANSLIT', $fichero['Ruta']);
					//~ $textoFichero = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $fichero['Ruta']);
					$textoFichero =$fichero['Ruta'];
					//~ $textoFichero = iconv( 'utf-8','ISO-8859-1', $textoFichero);
					//~ foreach(mb_list_encodings() as $chr){
						//~ echo  $x.'- '.mb_convert_encoding($textoFichero, 'UTF-8', $chr)." : ".$chr."<br>";   
					//~ } 
					echo $x.'- '.basename($textoFichero).'<br/>';
				}
			}
			?>
			
			
			
		</div>
	
	</div>
	
</body>
</html>

