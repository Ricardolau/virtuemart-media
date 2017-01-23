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
 
 // Inicializamos varibles
 $ficheros = array ();
 //Creamos array de ficheros que existene en el directorio
   $files = filesProductos($RutaServidor,$DirInstVirtuemart);
 // Ahora de momento no lo hacemos, pero esto debe hacer con AJAX , ya que pueden ser muchas imagenes y tendremos que ir añadiendo 
 // a medida que se van procesando, para no bloquear el servidor o la web.
   $ficheros = Datosficheros( $files, $BDVirtuemart,$prefijoTabla );
 
 //  Files es un array con solo ficheros del directorio que indicamos.
 
 // Ahora buscamos en basedatos la imagen.
 
 //~ echo '<pre>';
 
 
 
 //~ echo 'FICHERO NO ENCONTRADOS MEDIA';
 
 
 //~ print_r($ficheros);
 
 //~ echo '</pre>';
 
?>

	<div class="container">
		<div class="col-md-8">
			<h1>Analizamos las imagenes de productos de virtuemart</h1>
			<p>El objetivo es saber: </p>
			<ul> 
			<li> Saber cuantas ficheros existen en el directorio asignado para las de los productos <span class="label label-default">Ficheros existentes</span></li>
			<li> Saber cuantas ficheros no se encuentran tabla virtuemart_media. <span class="label label-default">Ficheros no encontrados</span></li>
			<li> Saber cuales no se utilizan.<span class="label label-default">Imagenes no utiliza</span></li>
			</ul> 
			<h4>Procesos</h4>
			<div style="float:left;margin-left:20px;">Ficheros existentes <span class="label label-default"><?php echo count($files);?></span></div>
			<?php 
			// Queda pendiente ver como contar los ficheros no encontrados y descontarlos en las Imagenes no utilizadas.
			?>
			<div style="float:left;margin-left:20px;">Ficheros no encontrados <span class="label label-default"><?php echo count($files);?></span></div>
			<div style="float:left;margin-left:20px;">Imagenes no utilizas <span class="label label-default"><?php echo count($ficheros);?></span></div>
			
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
			
			
			
			
		</div>
		<div class="col-md-12">
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
				//Con el siguiente bucle ordeno por id
				foreach ( $ficheros as $clave => $fichero ) {
					$Ruta[$clave] = $fichero['Ruta'];
					$ID[$clave] = $fichero['IDmedia'];

				}
				// Ordenamos por ID ascendente.
				array_multisort($ID, SORT_ASC, $ficheros);
				
				
				
				// de momento lo dejo asi ,pero no es la correcta.
				//~ natsort($ficheros);
				
				
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
			<div class="col-md-12">
			<h2>Listado de ficheros no encontrados en tablas virtuemart_media</h2>
			<p> Estos ficheros no son los que vamos eliminar del servidor, ya que no existen la tabla de virtuemart_media, por lo entiendo que ya no existe la miniatura y que no se utiliza en la tabla productos.</p>
			<div class="alert alert-warning">
			<strong>Atención</strong>
			<p> Un motivo por el que aparezca aqui el fichero, puede ser que el nombre tenga algún caracter extraño y por eso no lo encontro en busqueda en tabla virtuemart_media.</p>
			<p class="text-center"><strong>Así que procede con prudencia a la hora borrar ficheros de este listado.</strong></p>
			</div>
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

