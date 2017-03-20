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
	
	function ObtenerProductos($BDVirtuemart,$prefijoTabla) {
		// Obtenemos:
		// 	1.- ID de producto
		//	2.- Referencia Proveedor del Producto ( Multipiezas se utiliza ) 
		//  3.- ID Multimedia ( Si hay pone 0)
		// Teniendo en cuenta que repite ID producto si hay varias imagenes en virtuemar_product_media
		$Productos = array();
		$campo[1] ="P.virtuemart_product_id";
		$campo[2] ="P.product_gtin";
		$campo[3] ="M.virtuemart_media_id";
		$tabla[1] = $prefijoTabla."_virtuemart_products P";
		$tabla[2] = $prefijoTabla."_virtuemart_product_medias M";
		$ConsultaRelacionada = 'SELECT '.$campo[1].','.$campo[2].','.$campo[3].' FROM '.$tabla[1].' left join '.$tabla[2].' On '.$campo[1].'=M.virtuemart_product_id ORDER BY '.$campo[1].' ASC ';
		$Query = $BDVirtuemart->query($ConsultaRelacionada);
		$i = 0;
		//~ $Productos['ErrorConsulta'] = $ConsultaRelacionada;
		if ($Query->num_rows > 0){
			$y = 0;
			$productoR = '';
			while ($fila = $Query->fetch_assoc()) {
				// Hay que tener en cuenta que devuelve filas de dos tablas, de products y product_media
				//, donde si un product tiene dos imagenes entonces devuelve dos filas,
				//  con el mismo id de producto y con distinto media_id
				// Listado va por orden de Idproducto
				// Ejemplo montamos.:
				/*[2886] => Array
					(
					[product_id] => 2914
					[product_gtin] =>  M110831
					[Imagenes] => Array
							(
							[0] => Array
							(
								[virtuemart_media_id] => 2257
							)

							[1] => Array
							(
								[virtuemart_media_id] => 5617
							)
		            )
				*/
				
				
				if ($productoR != $fila['virtuemart_product_id']){
					$i++;
					$Productos[$i]['product_id'] = $fila['virtuemart_product_id'];
					$Productos[$i]['product_gtin'] = $fila['product_gtin'];
				}
				if (isset($fila['virtuemart_media_id'])){
					$Productos[$i]['Imagenes'][]['virtuemart_media_id'] = $fila['virtuemart_media_id'];
				} else {
					$y = $y +1; // Contador Productos que no tiene imagen asignada.
				}
				$productoR = $fila['virtuemart_product_id'];

			}
		} else { 
			$Productos['ErrorConsulta'] = $ConsultaRelacionada;
		}
		$Productos['TotalProductos'] = $i;	
		$Productos['SinIdMedia'] = $y;
		return $Productos;
	}
	
	
	
	
	function ProductosImagenMal($Productos,$BDVirtuemart,$prefijoTabla,$DirInstVirtuemart,$RutaServidor) {
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
	
	function ObtenerDatosMedia($files,$BDVirtuemart,$prefijoTabla,$RutaServidor,$DirInstVirtuemart,$prefijoTabla) 
	{
		// Objetivo:
		//  1.- Cuanto registros hay virtuemart_media typo product.
		//  2.- Cuales de estos la URL indicada no se encuentra.
		//  3.- Y cuales existen.
		$IdArray = array();
		$tabla = array();
		$tabla[1] = $prefijoTabla.'_virtuemart_product_medias';
		$tabla[2] = $prefijoTabla.'_virtuemart_medias';
		//  1.- Cuanto registros hay virtuemart_media typo product.
		$consulta = "SELECT * FROM ".$tabla[2].' WHERE file_type="product"';
		$MediaProducts = $BDVirtuemart->query($consulta);
		$IdArray['NRegProducto']= $MediaProducts->num_rows ; // Cantidad registros que hay media que son productos
		
		//  2.- Cuales de estos la URL indicada no se encuentra.
		$ConsultaRelacionada = 'SELECT M.virtuemart_media_id,M.file_url,if( P.virtuemart_product_id IS NULL , 0, P.virtuemart_product_id ) AS virtuemart_product_id FROM '.$tabla[2].' M LEFT JOIN '.$tabla[1].' P On M.virtuemart_media_id=P.virtuemart_media_id WHERE M.file_type="product" ORDER BY M.virtuemart_media_id ASC ';
		$MediaProducts = $BDVirtuemart->query($ConsultaRelacionada);
		$i = 0;
		while ($MediaProduct = $MediaProducts->fetch_assoc()){
<<<<<<< HEAD
			$url = $RutaServidor.$DirInstVirtuemart.'/'.$MediaProduct['file_url'];
			if ($file== $url) { 
			$IdArray[$i]['encontrado'] ='si';
			break;
			};
		$i++;
=======
			$IdArray[$i] = $MediaProduct ;
			$i++;
			
>>>>>>> b2757bf239071565abb6894324404597f9c8ea99
		}
		// Ahora tenemos un $IdArray con los ficheros que existen en productos.
		// Ejemplo array:
		// [0] => Array
		//   (
		//      [virtuemart_media_id] => 3
		//      [file_url] => images/stories/virtuemart/product/A110205.jpg
		//      [virtuemart_product_id] => 207
		//  )
		
		//  3.- Y cuales existen de verdad.
		$i=0;
		foreach ($IdArray as $ID){
			$i++;
			$encontrado = 'no';
			$url = $RutaServidor.$DirInstVirtuemart.'/'.$ID['file_url'];
			$IdArray[$i]['Url'] = $url;
			foreach ($files as $file )	{
				if ($file== $url) { 
				$encontrado ='si';
				break;
				}
			}
		$IdArray[$i]['Existe'] = $encontrado;
		
		
		}	
		//~ $IdArray['Consulta'] = $ConsultaRelacionada;
		
		$IdArray['NumeroFiles']= count($files);// Cantidad de ficheros encontrados.
		return $IdArray;	
	}	
	
	// Funcion para copiar imagen
	// Url de origen http://www.abantos-autoparts.com/tienda/fotos/
	function recibe_imagen($nombrefichero,$HostNombre){
		$nombrefichero = trim($nombrefichero);
		$url_origen = "http://www.abantos-autoparts.com/tienda/fotos/".$nombrefichero;
		$archivo_destino= $_SERVER['DOCUMENT_ROOT'].$HostNombre.'/BancoFotos/'.$nombrefichero.'.jpg';
		$imagen = file_get_contents($url_origen);
		$fs_archivo = file_put_contents($archivo_destino,$imagen);
		
		
		return $archivo_destino;
	} 

	// Funcion para comprobar estado ( Si existe el fichero en el servidor )
	function comprobarEstado($ficheros,$HostNombre,$DirImageProdVirtue){
		$resultado = array();
		$ArrayFicheros = array();
		$Nficheros = count($ficheros);
		$i = 1;
		foreach ($ficheros as $fichero){
			// Ahora si existe fichero en imagenes/product
			$fichero_url = $_SERVER['DOCUMENT_ROOT'].$DirImageProdVirtue.$fichero.'.jpg';
			if (file_exists($fichero_url)){
				$resultado[$i] = ' Existe';
				$ArrayFicheros[$i]= $fichero_url;
			} else {
				$resultado[$i] = 'No Existe';
				$ArrayFicheros[$i]= $fichero_url;

			} 
			
		$i++;
		}
		
		return $resultado;
		
		
	}




?>




