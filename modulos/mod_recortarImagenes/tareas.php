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


// Incluimos funciones y configuracion
include ("./../../configuracion.php");
include ("./funciones.php");


 
 switch ($pulsado) {
    case 'Redimensionar':
		// Ahora creamos los datos que vamos enviar a la funcion redimensionar de php.
		$destino =	$RutaServidor.$DirImagRecortadas;
		$imagen['checkID'] = $_POST['checkID'];
		$imagen['nombre'] = $_POST['nombreFichero'];
		$imagen['extension'] = substr($_POST['extensionFichero'], -3);
		$imagen['rutafichero'] = $RutaServidor.$DirImagOriginales.$imagen['nombre'].$_POST['extensionFichero'];
		$imagen['alto'] = $_POST['altoFichero'];
		$imagen['ancho'] = $_POST['anchoFichero'];
		$imagen['tipoimagen'] = $_POST['tipoFichero'];
		// El tipo fichero :
		// 	1 - Gif
		//  2 - jpg o Jpeg
		//  3 - png
		if ($imagen['extension'] == 'gif') {
			$imagen['tipofichero'] = 1;
		}
		if ($imagen['extension'] == 'jpg') {
			$imagen['tipofichero'] = 2;
		}
		if ($imagen['extension'] == 'png') {
			$imagen['tipofichero'] = 3;
		}
			
		$sufijo = '_'.$ImgAltoCfg.'x'.$ImgAnchoCfg;
		// Llamamos a la funcion de redimension
		RecortarImagenC ($imagen,$destino,$sufijo, $ImgAltoCfg, $ImgAnchoCfg);
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($imagen);
		
		break;
	case 'EliminarTodos':
		$DestinoRe =	$RutaServidor.$DirImagRecortadas;
		// Eliminamos todas la miniaturas.
		$salida = EliminarTodos($DestinoRe);
		break;
	case 'EliminarUno':
		$DestinoRe =	$RutaServidor.$DirImagRecortadas;
		$checkID = $_POST['checkID'];
		$imagen['nombre'] = $_POST['nombreFichero'];
		$imagen['nombre'] = $imagen['nombre'].'_'.$ImgAnchoCfg.'x'.$ImgAltoCfg.$_POST['extensionFichero'];
		$imagen['rutafichero'] = $DestinoRe.$imagen['nombre'];
		// Eliminanos UNA miniatura
		
		EliminarUno($imagen['rutafichero']);
		
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($checkID);
		break;	
		
	break;
    
}
 

 
?>
