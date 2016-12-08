<?php
	/* Fichero para limpiar ficheros que hay en directorio tratamiento imagenes
	 * y luego copiar las imagenes que hay productos de la instalacion local Joomla (Virtuemart)
	 * 
	 * */
// Eliminanos imagenes de directorios
$salida = shell_exec('ls -lart');
echo "<pre>$salida</pre>";
// Copiamos imagenes de virtuemart/producto en directorio Origen

// Y redireccionamos para carga nuevamente recortaimagenes.php


	
?>



