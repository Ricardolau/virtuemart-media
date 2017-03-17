<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
	include './../../modulos/mod_conexion/conexionBaseDatos.php';
?>
<script>
// Funcion para controlar lo pulsado (botones y link )
function metodoClick(pulsado){
	console.log("Inicimos switch de control pulsar");
	switch(pulsado) {
		case 'Copiar':
			// Quiere decir que pulso check de toda la seleccion.
			alert('Pulso copiar');
			break;
			
		case 'TodaSeleccion':
			// Quiere decir que pulso check de toda la seleccion.
			MarcarTodas();
			break;
		default:
			alert('Error pulsado incorrecto');
	}
}
function MarcarTodas() {
	// Esta funcion hace es seleccionar o deseleccionar todas las imagenes de la pagina actual
	// si lo tenemos marcado checkTotal, entonces desmarcamos todos.
	checkID = [] ; // Reiniciamos varible global.
	var i = 0;
	var valor= false;
	if($('input[name=checkTotal]').is(':checked')){
		// Quiere decir que esta marcado, por lo que desmarcamos.
		valor = true;
	 } else {
		// Quiere decir que marcamos todos.
		valor = false;
		
	 }
	$(".rowCheckFichero").each(function(){ 
		i++;
		//todos los que sean de la clase row1
		$('input[name=checkFic'+i+']').prop("checked", valor);
		console.log('Entro en cambio '+'checkFic'+i);
	});
	
	
	
}




</script>


</head>
<body>
<?php 
		include './../../header.php';
		include 'funciones.php';
?>
<script src="<?php echo $HostNombre; ?>/modulos/mod_revisarvirtuemart/funciones.js"></script>
<?php
 // Obtenemos de lo queremos mostrar.
	if ($_GET) {
		if ($_GET['view']) {
			$view = $_GET['view'];
		}
	}	
	$TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
 // Creamos array con producto que no tiene imagen
	$productosSinImagen = array();
	$i= 0;
	foreach ($TodosProductos as $productos){
		if (count($productos['Imagenes'])== 0 && strlen($productos['product_gtin'])>0){
			$i++;
			$productosSinImagen[$i]['product_gtin']=$productos['product_gtin'];
			$productosSinImagen[$i]['product_id']=$productos['product_id'];
		}
	}
 if (isset($TodosProductos['ErrorConsulta'])){
	echo '<div class= "container"><h4>Hubo un error de conexion con la base de datos o no hay articulos pasados</h4>';
	echo '<p>'.$TodosProductos['ErrorConsulta'].'</p></div>';
	exit;
}
?>
<div class="container">
	<h1>Mostramos los productos que no tiene imagen</h1>	
	
	<div class="col-md-12">
		<?php echo 'Nos faltan imagenes en productos:'.$TodosProductos ['SinIdMedia'];	?>
		<input type="submit" value="Comprobar Local" onclick="metodoClick('ComprobarLocal');"> 
		<input type="submit" value="Copia Imagenes Abastros" onclick="metodoClick('Copiar');"> 

	</div>
	<table class="table table-striped">
    <thead>
      <tr>
        <th>TODAS
        <input type="checkbox" name="checkTotal" value="0" onchange="metodoClick('TodaSeleccion');">
		</th>
        <th>ID Producto</th>
        <th>Ref_gtin</th>
        <th>ServidorLocal<a title="Comprobamos servidor local si existe">(!)</a></th>
        <th>Abastros<a title="Comprobamos servidor abastros si existe">(!)</a></th>
        <th>Existe/Copiada<a title="Comprobamos Existe o copiada">(!)</a></th>
      </tr>
    </thead>
    <tbody>
    <?php
		$i= 0;
		foreach ($productosSinImagen as $Productos){
			$i++;
			?>
			<tr>
				<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $i;?>" value="<?php echo $i;?>"></td>
				<td><?php echo $Productos['product_id'];?></td>
				<td><?php echo $Productos['product_gtin'];?></td>
				<td class="rowCheckLocal"><input type="checkbox" name="checkLocal<?php echo $i;?>" value="<?php echo $i;?>"></td>
				<td class="rowCheckAbastro"><input type="checkbox" name="checkAbastros<?php echo $i;?>" value="<?php echo $i;?>"></td>
				<td class="Estado"></td>

			</tr>	
		<?php
	
		}
	?>
      <tr>
      </tr>
      
      
    </tbody>
  </table>
	<?php
	//~ foreach ($TodosProductos as $Productos){
			//~ 
	//~ }
	//~ 
	//~ echo '<pre>';
		//~ print_r($TodosProductos );
	//~ echo '</pre>';
	?>
</div>

</body>
</html>
