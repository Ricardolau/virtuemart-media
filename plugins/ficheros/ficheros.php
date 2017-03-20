<?php 
/* Crear una funcion que mandando parametros nos devuelva datos del fichero.
 
 * 
 **/
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
?>
