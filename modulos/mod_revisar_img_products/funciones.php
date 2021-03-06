<?php
	/* Funciones para el tratamiento imagenes
	 * -Funcion DatosImagen para ver tipo y datos de imagen
	 * -Funcion RecortarImagen para recortar
	 *
	 * */

	function ObtenerDirectorios($ruta) 
	{
		// Obtenemos directorios que hay dentro de ruta, menos los resized..
		$Nletras = strlen($ruta);
		$LeerDir = array();
		$instruccion = "find ".$ruta." -mindepth 1 -maxdepth 1 -type d";
		exec($instruccion,$out,$e);
		$d= 0;
		foreach ($out as $rutaDirectorio){
			// Extraemos nombre directorio.
			$nombre=substr($rutaDirectorio, $Nletras);
			if ( $nombre != 'resized'){
				$LeerDir[$d]['Nombre'] = $nombre;
				$d++;
			}
		}

		return $LeerDir;
	}

	function filesProductos($RutaServidor,$Dir_Actual,$DirInstVirtuemart)
	{
		// Obtener las rutas de los ficheros.
		$LeerFiles = array();
		$ruta = $RutaServidor.$DirInstVirtuemart.$Dir_Actual;
		if (is_dir($ruta) === false){
			$LeerFiles['error'] = ' Ruta mal, '.$ruta;
			return $LeerFiles;
		}
		$files = scandir($ruta);
		$f= 0;
		foreach ($files as $file){
			// Filtramos los . y .. que nos coje tb
			if (is_dir($ruta.$file)===false){
				$LeerFiles[$f]['fichero'] = $file;
				$f++;
			}
		}
		return $LeerFiles ;
	}

	function fileNoUtilizados ($BDVirtuemart,$prefijoTabla,$files,$ruta) 
	{
		// Consultamos cuantos ficheros de este directorio no se utilizan.
		// Autores: Guillermo , Ricardo y Olalla ..
		$fileUrlSaved = array();
		$ficheros = array_column($files, 'fichero');
		$i= 0;
		$e= 0;
		// a la $ruta le quitamos / si la trae al principio.
		if ($ruta[0] === '/'){
			$ruta = substr($ruta, 1);
		}
		$sql = "SELECT `file_url`, `virtuemart_media_id` FROM ".$prefijoTabla."_virtuemart_medias WHERE `file_type` = 'product'";
		foreach ($ficheros as $fichero){
			$ficheros[$i] = $ruta.$fichero;
			$i ++;
		}

		$consulta = $BDVirtuemart->query($sql);

		$fileUrlSaved = $consulta->fetch_all(MYSQLI_ASSOC);
		$diffferen = array_diff($ficheros,array_column($fileUrlSaved,'file_url'));
		
		sort($diffferen); // Ordenamos y renumeramos el indice.
		return $diffferen;
	}

	function ObtenerProductos($BDVirtuemart,$prefijoTabla) {
		// Obtenemos:
		// 	1.- ID de producto
		//	2.- Campo 'product_gtin' que es la Referencia Proveedor del Producto ( Multipiezas se utiliza )
		//  3.- ID Multimedia ( Si hay pone 0)
		// Teniendo en cuenta que repite ID producto si hay varias imagenes en virtuemar_product_media
		$Productos = array();
		$campo[1] ="P.virtuemart_product_id";
		$campo[2] ="P.product_gtin";
		$campo[3] ="M.virtuemart_media_id";
		$campo[4] ="ES.product_name";
		$campo[5] ="P.published";

		$tabla[1] = $prefijoTabla."_virtuemart_products P";
		$tabla[2] = $prefijoTabla."_virtuemart_product_medias M";
		$tabla[3] = $prefijoTabla."_virtuemart_products_es_es ES";
		//~ $ConsultaRelacionada = 'SELECT '.$campo[1].','.$campo[2].','.$campo[3].' FROM '.$tabla[1].' left join '.$tabla[2].' On '.$campo[1].'=M.virtuemart_product_id ORDER BY '.$campo[1].' ASC ';
		$ConsultaRelacionada = 'SELECT '.$campo[5].','.$campo[1].','.$campo[2].','.$campo[3].','.$campo[4].' FROM '.$tabla[1].' left join '.$tabla[2].' On '.$campo[1].'=M.virtuemart_product_id  left join '.$tabla[3].' On '.$campo[1].'=ES.virtuemart_product_id ORDER BY '.$campo[1].' ASC ';
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
				/*Array
				(
					[published] => 1 ( Si esta publicada) ..
					[product_id] => 5
					[product_gtin] => D120004
					[product_s_desc] => bRAZO DE ALGO

					[IdMedia] => Array
						(
							[0] => 3978
						)

				)
				*/


				if ($productoR != $fila['virtuemart_product_id']){
					$i++;
					$Productos[$i]['product_id'] = $fila['virtuemart_product_id'];
					$Productos[$i]['product_gtin'] = $fila['product_gtin'];
					$Productos[$i]['product_name'] = $fila['product_name'];
					$Productos[$i]['published'] = $fila['published'];

				}
				if (isset($fila['virtuemart_media_id'])){
					$Productos[$i]['IdMedia'][] = $fila['virtuemart_media_id'];
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



	function ComprobarImagen ($imagen)
	{
		/* En está función enviamos la ruta de la imagen y nos devuelve un array con:
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

	
	function comprobarEstado($ficheros,$HostNombre,$DirImageProdVirtue,$checkID)
	{
		// Se utiliza en vistaMediaProductos.php
		// Funcion para comprobar estado ( Si existe el fichero en el servidor )
		// funcion valida si el nombre del producto es igual a la referencia obtenida
		//~ $resultado = array();
		$ArrayFicheros = array();
		$Nficheros = count($ficheros);
		$i = 1;
		$x = 0; // ya que checkID empieza array 0
		foreach ($ficheros as $fichero){
			// Ahora si existe fichero en imagenes/product
			$Nombrefichero = trim($fichero);
			$fichero_url = $_SERVER['DOCUMENT_ROOT'].$DirImageProdVirtue.$Nombrefichero .'.jpg';
			$resultado[$i]['Check'] = $checkID[$x];
			$resultado[$i]['NombreFichero'] = $Nombrefichero;
			$resultado[$i]['UrlFichero'] = $fichero_url;

			if (file_exists($fichero_url)){
				$resultado[$i]['Existe'] = 'Si';
				//~ $ArrayFicheros[$i]= $fichero_url;
			} else {
				$resultado[$i]['Existe'] = 'No';
				//~ $ArrayFicheros[$i]= $fichero_url;
			}

		$i++;
		$x++;
		}
		// Ahora añadimos cuanto enviamos
		$resultado['NObjetos'] =$i-1;
		return $resultado;


	}
?>
