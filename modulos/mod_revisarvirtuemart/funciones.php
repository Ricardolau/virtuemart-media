<?php
	/* Funciones para el tratamiento imagenes
	 * -Funcion DatosImagen para ver tipo y datos de imagen
	 * -Funcion RecortarImagen para recortar
	 * 
	 * */

	function filesProductos($RutaServidor,$DirImageProdVirtue)
	{	
	$files = array_filter(glob($RutaServidor.$DirImageProdVirtue."*"), 'is_file');
	return $files ;
	}
	
	
	/* Funcion que busca en tabla de medios virtuemart si el fichero existe o no.*/
	function Datosficheros($files,$BDVirtuemart,$prefijoTabla) {
	$contarError = 0;	
	$x = 0;
	$ficheros = array();
		foreach ( $files as $file ){
			
			$fichero=utf8_encode(basename($file)); // Nombre de fichero con extension ..
			// Ahora tenemos que añadirle directorio de sistema
			$fichero = 'images/stories/virtuemart/product/'.$fichero;
			//~ echo $fichero.'<br/>';
			$consultaImgMedia = $BDVirtuemart->query( "SELECT `virtuemart_media_id`,`file_url` FROM `".$prefijoTabla."_virtuemart_medias` where `file_url`= '".$fichero."'");
			if ($consultaImgMedia->num_rows == 0){
				$x++;
				$contarError++;
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
		$ficheros['NFicherosNoEncontrados'] = $contarError ;
		return $ficheros;	
		
	}
	function ObtenerProductos($BDVirtuemart,$prefijoTabla) {
		$Productos = array();
		// Consulta para obtener ID y Referencia Proveedor
		$campos ="`virtuemart_product_id`,`product_gtin`";
		$tabla = "_virtuemart_products";
		$whereC = " ";
		$Consulta = "SELECT ".$campos." FROM `".$prefijoTabla."_virtuemart_products` ".$whereC;
		$Query = $BDVirtuemart->query($Consulta);
		$i = 0;
		if ($Query->num_rows > 0){
			$Productos['TotalProductos'] = $Query->num_rows;	
			while ($fila = $Query->fetch_assoc()) {
				$i++;
				$Productos[$i]['product_id'] = $fila['virtuemart_product_id'];
				$Productos[$i]['product_gtin'] = $fila['product_gtin'];
			}
		
		
		} else { 
			$Productos['ErrorConsulta'] = $Consulta;
		}
		return $Productos;
	}
	
	
	
	
	function ProductosImagenMal($Productos,$BDVirtuemart,$prefijoTabla,$DirInstVirtuemart,$RutaServidor) {
		
		if ($Productos['TotalProductos']){
			// Quiere decir que envio productos.
			$i = 0;
			// Montamos array con id media....todos los productos.
			$campos = "`virtuemart_product_id`, `virtuemart_media_id`";
			$tabla = "_virtuemart_product_medias";
			for ( $i=1 ; $i <= $Productos['TotalProductos']; $i++) {
				// Ahora buscamos tabla product_medias el id de media.
				$whereC = " WHERE virtuemart_product_id=".$Productos[$i]['product_id'];
				$Consulta2 = $BDVirtuemart->query("SELECT ".$campos." FROM ".$prefijoTabla.$tabla.$whereC);
				if ($Consulta2->num_rows >0){
					// Quiere decir que encontro ID de media
					while ($fila2 = $Consulta2->fetch_assoc()) {
					   $Productos[$i]['media_id'] = $fila2['virtuemart_media_id'];	
					} 
				} else {
					// Quiere decir que no HAY ID de media
						$Productos[$i]['media_id'] = 0;	
				}
			}
		}
		// Ahora contamos productos que no tiene asignado imagen
		$i= 0 ;
		$conImagenes = 0;
		$ErrorImagenes = 0 ;
		$campos = "`virtuemart_media_id`,`file_mimetype`,`file_url`";
		$tabla = "_virtuemart_medias";
		foreach ($Productos as $producto) {
			$i++;
			if ($producto['media_id'] > 0 ) {
				$conImagenes++;
				// Ahora obtenemo url de imagen
				$whereC = " WHERE virtuemart_media_id=".$producto['media_id'];
				$Consulta = $BDVirtuemart->query("SELECT ".$campos." FROM ".$prefijoTabla.$tabla.$whereC);
				//~ $Productos[$i]['consulta'] = $whereC;
				if ($Consulta->num_rows >0){
					// Quiere decir que obtuvo resultados.
					$Productos[$i]['NImagenes'] = $Consulta->num_rows;
					// Ahora comprobamos si es correcto el tipo.
					while ($fila = $Consulta->fetch_assoc()) {
						// Ahora solo falta consultar si existe el fichero.
						$ImagenRuta = $RutaServidor.$DirInstVirtuemart.'/'.$fila['file_url'];
						$resultado = ComprobarImagen($ImagenRuta);
						$Productos[$i]['comprobarImagen'] = $resultado;
						// Ahora añadimos contador de productos con error en imagenes
						if ($resultado == 'Error' ){
							$ErrorImagenes++;
						}
					}
				} else {
					$Productos[$i]['NImagenes'] = 0;
				}
			}
			
		}
		
		
		$Productos['ConIDMedia'] = $conImagenes;
		$Productos['SinIDMedia'] = $Productos['TotalProductos']-$Productos['ConIDMedia'];
		$Productos['ErrorImagen'] = $ErrorImagenes;

		return $Productos;


	}
	
	
	
	function ComprobarImagen ($imagen)
	{	
		/* En está función enviamos la ruta de la imagen y nos devuelve un array con: 
		* 	ancho 			-> 	pixel de ancho.
		* 	alto			-> 	pixel de alto.
		* 	tipoimagen	 	=> 'C' cuadrada, 'P' panoramica, 'V' vertical
		* 	tipofichero 	=> 1,2,3,4,5,6 ( gif,jpg,png y más extensiones pero no la utilizo) ver funcion exif_imagetype()
 		* 	error			-> 	Si no exite el fichero que enviamos.
		* 						Si no es una imagen.
		*/
		$RutaImagen = $imagen;
		$info = pathinfo($RutaImagen); // Creamos array para quitar extension
		$extFichero = '.'.$info['extension'];
		$NombreFichero = basename($RutaImagen,$extFichero);
		// Si existe la el fichero...
		if (file_exists($RutaImagen)){
			$type = exif_imagetype($RutaImagen); 
			if ( $type == 1 || $type == 2 || $type == 3 ){
				$respuesta = 'Correcta tipo imagen';

				}// Cierro if comprueba si es imagen
		} else {
				// Aquí no debería llegar nunca..
				$respuesta = 'Error';
		}	

		return $respuesta;
	}
	
	
	
	function ObtenerDatosficheros($files,$BDVirtuemart,$prefijoTabla,$RutaServidor,$DirInstVirtuemart) {
	// Que imagenes hay virtuemart_media_id y no se utilizan en virtuemart_product_medias, pero que 
	// esa imagen este en directorio imagenes de productos.
	$IdArray = array();
	$ArrayIDMedia = array();
	//~ $Consulta = 'SELECT `virtuemart_media_id`,`file_url` FROM `xcv7n_virtuemart_medias` WHERE `file_type`="product" ORDER BY `virtuemart_media_id` ASC';
	$ConsultaRelacionada = 'SELECT M.virtuemart_media_id,M.file_url,P.virtuemart_product_id FROM `xcv7n_virtuemart_product_medias` P inner join `xcv7n_virtuemart_medias` M On M.virtuemart_media_id=P.virtuemart_media_id WHERE M.file_type="product" ORDER BY M.virtuemart_media_id ASC ';
	$MediaProducts = $BDVirtuemart->query($ConsultaRelacionada);
	$i = 0;
	while ($MediaProduct = $MediaProducts->fetch_assoc()){
		$IdArray[$i] = $MediaProduct ;
		$i++;
		
	}
	// Ahora tenemos un $IdArray con los ficheros que existen en productos.
	// Ejemplo array:
	// [0] => Array
    //   (
    //      [virtuemart_media_id] => 3
    //      [file_url] => images/stories/virtuemart/product/A110205.jpg
    //      [virtuemart_product_id] => 207
    //  )
	// Donde $RutaServidor.$DirInstVirtuemart./file_url es igual  array $files con  [1] => /home/antonio/www/multipiezas/images/stories/virtuemart/product/A110205.jpg
	$i=0;
	foreach ($files as $file ){
		while ($MediaProduct = $MediaProducts->fetch_assoc()){
			$url = $RutaServidor.$DirInstVirtuemart.'/'.$MediaProduct['file_url'];
			if ($file== $url) { 
			$IdArray[$i]['encontrado'] ='si';
			break;
			};
		$i++;
		}
		
	}
		
	
	
	
	return $IdArray;	
		
	}
	
?>



