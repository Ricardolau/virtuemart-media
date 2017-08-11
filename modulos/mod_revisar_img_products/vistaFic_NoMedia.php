<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
	include './../../modulos/mod_conexion/conexionBaseDatos.php';
?>
</head>
<body>
<?php 
	include './../../header.php';
	include 'funciones.php';
	$error ='';
	$Dir_Actual = $DirImageProdVirtue; // Ya que puede cambiar segun carpeta que estemos.
	$ruta = $RutaServidor.$DirInstVirtuemart.$Dir_Actual;
	$ficheros = array ();

	//Si estamos en un sub-directorio entonce $DirImageProdVirtue cambia....
	if (isset($_GET['directorio'])) {
		$Nom_Dir_Actual = $_GET['directorio'];
		$Dir_Actual .= $Nom_Dir_Actual.'/';
		$ruta = $ruta.$Nom_Dir_Actual.'/';
	} else {
		$Nom_Dir_Actual = 'raiz';
	}
	//Obtenemos array de ficheros y directorios que existen en directorio asignado para productos.
	$files = filesProductos($RutaServidor,$Dir_Actual,$DirInstVirtuemart); 
	if (empty($files['error'])){
		$ficheros['Total'] = count($files);
	} else {
		$error .= $files['error'];
	}
	//Obtenemos la cantidad de ficheros que no se utilizan en virtuemart_media.
	if ($ficheros['Total']){
		$ficheros['ImgNoUtilizadas']= fileNoUtilizados ($BDVirtuemart,$prefijoTabla,$files,$Dir_Actual);
	} 

?>

<div class="container">
	<h1>Mostramos las imagenes no utilizados en media</h1>	
	<p>Se han encontrado <?php echo count($ficheros['ImgNoUtilizadas']);?> ficheros (jpg y png) de <strong>directorio <?php echo $Nom_Dir_Actual;?></strong> que no está añadidos a tabla de media</p>
	
	<!-- Mostramos barra y proceso que realizamos -->
	</div>
	<table class="table table-striped">
    <thead>
      <tr>
        <th></th>
        <th>Fichero</th>
        <th>ver</th>
        <th>borrar</th>
       </tr>
    </thead>
    <tbody>
    <?php
		$i= 0;
		foreach ($ficheros['ImgNoUtilizadas'] as $fichero){
			$i++;
			?>
			<tr>
				<td><?php echo $i;?></td>
				<td><?php echo $fichero;?></td>
				<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $i;?>" value="<?php echo $i;?>"></td>
				<td></td>
			</tr>	
		<?php
			if ($i >250){
				//Salimo de bucle
				break;
			}
		}
	?>
      <tr>
      </tr>
      
      
    </tbody>
  </table>
	
</div>
</body>
</html>
