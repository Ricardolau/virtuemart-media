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
	case 'ImagenesQueNoseUtilizan':
		// Obtenemos los files, en vez mandarlos por Ajax.
		$Dir_Actual = $DirImageProdVirtue;
		if ($_POST['Nom_Dir_actual'] <>''){
			$Dir_Actual = $DirImageProdVirtue.$_POST['Nom_Dir_actual'].'/';
		}
		$files = filesProductos($RutaServidor,$Dir_Actual,$DirInstVirtuemart);
		$ficherosNoUtilizado = fileNoUtilizados ($BDVirtuemart,$prefijoTabla,$files,$Dir_Actual);
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($ficherosNoUtilizado);
		break;
	case 'comprobarEstado':
		$nombrefichero = $_POST['ficheros'];
		$checkID = $_POST['checkID'];
        $ComprobarEstado = comprobarEstado($nombrefichero,$HostNombre,$DirImageProdVirtue,$checkID);
        header("Content-Type: application/json;charset=utf-8");
		echo json_encode($ComprobarEstado);
        break;
	
	case 'comprobarProductos':
        $TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
        $productos = ProductosImagenMal($TodosProductos,$BDVirtuemart,$prefijoTabla,$DirInstVirtuemart,$RutaServidor );
        header("Content-Type: application/json;charset=utf-8");
		$algo='Devuelvo algo';
		echo json_encode($productos);
        break;
	
	case 'ProductosImagen':
        // Tanto podemos recibir uno mas productos, en un array
        $TodosProductos = $_POST['ArrayEnviado'];

        $productos = ProductosImagenMal($TodosProductos,$BDVirtuemart,$prefijoTabla,$DirInstVirtuemart,$RutaServidor );
        header("Content-Type: application/json;charset=utf-8");
		$algo='Devuelvo algo';
		echo json_encode($productos);
        break;
}
 
/* ===============  CERRAMOS CONEXIONES  ===============*/
mysqli_close($BDVirtuemart);
 
 
?>


