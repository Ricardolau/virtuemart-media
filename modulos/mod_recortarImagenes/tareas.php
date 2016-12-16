<?php
/* Fichero de tareas a realizar.
 * 
 * 
 * Con el switch al final y variable $pulsado
 *     	$pulsado = 'borrar'					-> Ejecuta borrar($nombretabla, $BDImportRecambios);
 
 * 
 * 
 *  */

// creo que esta recogida de datos debe estar antes swich y solo pulsado.
$pulsado = $_POST['pulsado'];


// Incluimos funciones
include ("./funciones.php");


 
 switch ($pulsado) {
    case 'Redimensionar':
		// Debería saber que fichero quiero redimensionar.
		// comprobamos que check esta marcado.
		
		
		$respuesta [0] = 'Debería saber que fichero';
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($respuesta);
		
		break;
    
    
}
 

 
?>
