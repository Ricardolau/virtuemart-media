<?php
	/* Funciones para el tratamiento imagenes
	 * -Funcion DatosImagen para ver tipo y datos de imagen
	 * -Funcion RecortarImagen para recortar
	 * 
	 * */
function DatosImagen ($imagen)
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
				
				//Obtenemos las dimensiones, la ruta es absoluta. 
				$dim = getimagesize($RutaImagen);
				
				if (!empty($dim))
				{
					// Tipoimagen
					$dif = $dim[0]-$dim[1];
					
					if($dif == 0){
							// Es una imagen cuadrada
							$Tipoimagen = 'C';
					}
					if ($dif > 0){
							// Es una imagen panoramica
							$Tipoimagen = 'P';
					}
					if ($dif < 0){
							// Es una imagen vertical
							$Tipoimagen = 'V';
					}	
					
					$respuesta = array(
						'nombre'		=> $NombreFichero,
						'extension'		=> $extFichero,
						'rutafichero' 	=> $RutaImagen,
						'ancho' 		=> $dim[0],
						'alto' 			=> $dim[1],    
						'tipofichero' 	=> $type,
						'tipoimagen' 	=> $Tipoimagen
						
					);
					 
					
				}
			} else {
				$respuesta = array(
					'nombre'	=> $NombreFichero,
					'error' => "No es una imagen"
				);

				}// Cierro if comprueba si es imagen
		} else {
				// Aquí no debería llegar nunca..
				$respuesta = array(
					'nombre'	=> $NombreFichero,
					'error' => "No existe fichero"
				);
		}	

		return $respuesta;
	}	

function RecortarImagenC ($imagen,$destino,$sufijo, $ImgAltoCfg, $ImgAnchoCfg)
	{
			/* Recuerda que esta funcion recorta una imagen tanto sea vertical como panoramica y la 
			 * convierte en cuadrado.
			 * Luego la redimensiona a la medida que le indicamos.
			 *  
			 * IMAGEN : array 
			 * - [nombre] => Nombre imagen con extension...
			 * - [rutafichero] => Ruta completa ... /home/ricardo/www/pruebas/tratamientoFotos/BancoFotos/portada1.jpg
			 * - [ancho] => Ancho de la imagen px.
			 * - [alto] => Alto en px de la imagen.
			 * - [tipofichero] =>1,2,3,4,5,6 ( gif,jpg,png y más extensiones pero no la utilizo) ver funcion exif_imagetype()
             * - [tipoimagen] => Puede ser C ( Cuadrado ), P (Panoramica), V (Vertical)
			 * 
			 * DESTINO: String -> Contiene directorio destino.
			 * SUFIJO: String-> Contienre el sufijo que añade al nombre.
			 * 			 * 
			 **/
			// Ahora calculamos cual es el cuadrado más grande posible.
			// que no los indica el lado más corto.
			$NuevaMedida = $imagen['ancho'];;
			
			if ($imagen['tipoimagen'] == 'P'){
				// Lado más corto es el alto
				$NuevaMedida = $imagen['alto'];
			}
			
			// Ahora calculamos Centro de la imagen.
			$xAncho = $imagen['ancho']/2;
			$yAlto 	= $imagen['alto']/2;
			// Ahora calculamos el punto inicio de la imagen original.
			// LLamo punto inicio , punto x, y donde empezamos a cortar.
			// Esa cordena esta justo encima y a la izquierda del punto inicio
			// con una distancia que es la mitad de la nueva medida de la imagen.
				$CorteX = $xAncho - ($NuevaMedida/2);
				$CorteY = $yAlto - ($NuevaMedida/2);
				
			// Ahora creamos la imagen con la nueva medida. ( en memoria solo)
			$thumbail = imagecreatetruecolor($NuevaMedida, $NuevaMedida);
			// Ahora según el tipo de fichero utilizamos una imagecreatejpeg, imagecreatepng, imagecreategif
			// Esta instrucción crea una imagen igual a la original.
			
			$type = $imagen['tipofichero'];
			
			switch ($type) {
					case 1 :
						$copiaOrigen = imageCreateFromGif($imagen['rutafichero']);
						break;
					case 2 :
						$imagen1= $imagen['rutafichero'];
						$copiaOrigen = imagecreatefromjpeg($imagen1);
						break;
					case 3 :
						$copiaOrigen = imageCreateFromPng($imagen['rutafichero']);
						break;
			}
			
				// Ponemos fondo blanco
				$white = imagecolorallocate($thumbail, 255, 255, 255);
				imagefill($thumbail, 0, 0, $white);
				imagecopy($thumbail , $copiaOrigen, 0,0,$CorteX,$CorteY,$NuevaMedida,$NuevaMedida);
			//~ } else {
				//~ // Si la imagen es cuadrada
				//~ // Entonces $thumbail es copiaOrigen, ya que no se recorta.
				//~ $thumbail = $copiaOrigen ;
				//~ 
			//~ }
			// Ahora escalamos la imagen a la medida de configuracion
			$thumbail = imagescale($thumbail, $ImgAnchoCfg, $ImgAltoCfg,  IMG_BICUBIC);
			// Ahora creamos la nueva ruta destino
			$RutaImagenNueva = $destino.$imagen['nombre'].$sufijo.'.'.$imagen['extension'];
			//~ echo 'RutaImagenNueva : '.$RutaImagenNueva.'<br/>';
			
			//~ header("Content-type: image/jpeg");
			//~ echo imagejpeg($thumbail);
			// Ahora creamos la imagen recortada.
			switch ($type) {
					case 1 :
						imagegif($thumbail, $RutaImagenNueva);
						break;
					case 2 :
						imagejpeg($thumbail, $RutaImagenNueva,70);

						break;
					case 3 :
						// Desactivar la mezcla alfa y establecer la bandera alfa
						
						//~ imageAlphaBlending($thumbail,true);
						//~ imageSaveAlpha($thumbail, true);
									
						// Guardamos en fichero
						imagepng($thumbail, $RutaImagenNueva,9);
						break;
			
		
			}
			// liberar la imagen de la memoria
			imagedestroy($thumbail);
	}

function EliminarTodos ($DestinoRe) {
		// Eliminamos todas las miniaturas.
		$salida = shell_exec("rm ".$DestinoRe."*");
		
		return ;
		
}
	
function EliminarUno ($imagen) {
		// Eliminamo uno a uno las miniaturas.
		$salida = shell_exec("rm '".$imagen."'");
		
		return;
		
}




	
?>



