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
		if ($Nom_Dir_Actual != 'raiz'){
			$Dir_Actual .= $Nom_Dir_Actual.'/';
			$ruta = $ruta.$Nom_Dir_Actual.'/';
		}
	}
	// Obtenemos los ficheros que no son imagenes(jpg o png)
	$instruccion = "find ".$ruta." -mindepth 1 -maxdepth 1 -type f \! \( -iname '*jpg' -or -iname '*png' \)";
	exec($instruccion,$out,$e);
	$ficheros['NoImagenes']= $out;
	
	
	
?>

<div class="container">
	<h1>Mostramos los fichero no validos</h1>
	<p>Consideramos ficheros no validos, todos aquellos que no son jpg y png</p>	
	<p>Se han encontrado <?php echo count($ficheros['ImgNoUtilizadas']);?> ficheros (jpg y png) de <strong>directorio <?php echo $Nom_Dir_Actual;?></strong> que no está añadidos a tabla de media</p>
	<?php 
	//~ echo '<pre>';
	//~ print_r($ficheros['NoImagenes']);
	//~ echo '</pre>';
	?>
	<!-- Mostramos barra y proceso que realizamos -->
	
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
		foreach ($ficheros['NoImagenes'] as $fichero){
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
