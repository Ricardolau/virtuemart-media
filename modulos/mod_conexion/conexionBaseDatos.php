<?php 
// Creamos Array $Conexiones para obtener datos de conexiones
// teniendo en cuenta que le llamo a conexiones  a cada conexion a la Bases de Datos..
$Conexiones = array(); 

// [Numero conexion]
//		[NombreBD] = Nombre de la base datos..
// 		[conexion] = Correcto o Error
//		[respuesta] = " Respuesta de conexion de error o de Correcta"
//		[VariableConf] = Nombre variable de configuracion




/************************************************************************************************/
/*************   Realizamos conexion de base de datos de ImportarRecambios.          ************/
/************************************************************************************************/
$Conexiones [1]['NombreBD'] = $BaseDatos;
$BDImportRecambios = new mysqli("localhost", $usuario, $passport , $BaseDatos);
// Como connect_errno , solo muestra el error de la ultima instrucción mysqli, tenemos que crear una propiedad, en la que 
// está vacía, si no se produce error.
if ($BDImportRecambios->connect_errno) {
		$Conexiones [1]['conexion'] = 'Error';
		$Conexiones [1]['respuesta']=$BDImportRecambios->connect_errno.' '.$BDImportRecambios->connect_error;
		$BDImportRecambios->controlError = $BDImportRecambios->connect_errno.':'.$BDImportRecambios->connect_error;
} else {
	$Conexiones [1]['conexion'] ='Correcto';
	$Conexiones [1]['respuesta']= $BDImportRecambios->host_info;
/** cambio del juego de caracteres a utf8 */
 mysqli_query ($BDImportRecambios,"SET NAMES 'utf8'");
}



?>
