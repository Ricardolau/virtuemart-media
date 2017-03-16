<?php
/* Fichero de tareas a realizar.
 * 
 * 
 * Con el switch al final y variable $pulsado
 
 * 		$pulsado = 'comprobarProductos						-> Ejectua MsqlCsv($lineaA, $lineaF,$nombrecsv);
 * 
 *  */
/* ===============  REALIZAMOS CONEXIONES  ===============*/
// creo que esta recogida de datos debe estar antes swich y solo pulsado.
// la tabla solo en la opción que la necesite.

include_once ("./../../configuracion.php");
// Crealizamos conexion a la BD Datos
include_once ("./../mod_conexion/conexionBaseDatos.php");
// Incluimos clase objeto de consultas.

// Incluimos funciones
include_once ("./funciones.php");
$pulsado = $_POST['pulsado'];
 
 switch ($pulsado) {
   
    case 'comprobarProductos':
        $TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
        $productos = ProductosImagenMal($TodosProductos,$BDVirtuemart,$prefijoTabla,$DirInstVirtuemart,$RutaServidor );
        header("Content-Type: application/json;charset=utf-8");
		echo json_encode($productos);
        break;
	
}
 
/* ===============  CERRAMOS CONEXIONES  ===============*/
mysqli_close($BDVirtuemart);
 
 
?>


