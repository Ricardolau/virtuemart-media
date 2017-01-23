<?php
	/* Funciones para el tratamiento imagenes
	 * -Funcion DatosImagen para ver tipo y datos de imagen
	 * -Funcion RecortarImagen para recortar
	 * 
	 * */

	function filesProductos($RutaServidor,$DirInstVirtuemart)
	{	
	$files = array_filter(glob($RutaServidor.$DirInstVirtuemart."*"), 'is_file');
	return $files ;
	}
	
	function Datosficheros($files,$BDVirtuemart,$prefijoTabla) {
		
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
		return $ficheros;	
		
	}
	
	
?>



