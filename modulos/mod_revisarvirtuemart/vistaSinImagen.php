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
?>
    <script src="<?php echo $HostNombre; ?>/modulos/mod_revisarvirtuemart/funciones.js"></script>


<?php
$TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
 // Ahora compramos cuantos productos obtenemos y si hubo un error.
 
 if (isset($TodosProductos['ErrorConsulta'])){
	echo '<div class= "container"><h4>Hubo un error de conexion con la base de datos o no hay articulos pasados</h4>';
	echo '<p>'.$TodosProductos['ErrorConsulta'].'</p></div>';
	exit;
}
?>
<div class="container">
	<h1>Mostramos los productos que no tiene imagen</h1>	
</div>

</body>
</html>
