<?php
/* El objetivo es controlar las imagenes que hay productos, los existen /virtuemart/product y los que no se utilizan.
 * Recortando al tamaño más grande posible.
 * Creando una imagen cuadrada en una carpeta que le indiquemos.
 */

?>

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
	?>
    <script src="<?php echo $HostNombre; ?>/modulos/mod_revisarvirtuemart/funciones.js"></script>
<?php
	// Inicializamos variable de inicio y entorno:
	$error ='';
	$ficheros = array ();
	//Creamos array de ficheros que existene en el directorio
	$files = filesProductos($RutaServidor,$DirImageProdVirtue); 
	// Ahora obtenemos productos.
	$TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
	if (isset($TodosProductos['ErrorConsulta'])){
		// Ahora comprobamos si hubo un error en la consulta,
		$error = '<p>Error de conexion con la base de datos o no hay articulos pasados</p>';
		$error .= '<p>'.$TodosProductos['ErrorConsulta'].'</p>';
	}	
	// Ahora comprobamos:
	//		1.- Registros que hay en tabla Media
	//		2.- Que registros no se utilizan
	//		3.- Si todos los registros (url) son correcta y existen.
	$Media = ObtenerDatosMedia( $files, $BDVirtuemart,$prefijoTabla,$RutaServidor,$DirInstVirtuemart,$prefijoTabla );
	if ($Media['NumeroFiles']!= count($files)){
		// Quiere decir que el numero ficheros y el numero registros de media_product no son iguales
		$error .= '<p>Hubo en error en Media ya que obtuvo la misma cantidad ficheros... </p>';
	}
	// Obtenemos el valores de [Existe] y de [virtuemart_product_id]
	$CArrayExiste = array_count_values(array_column($Media, 'Existe'));// Creamos array suma de resultados
	$ArrayId = array_count_values(array_column($Media, 'virtuemart_product_id'));// Creamos array suma de resultados
	//~ $ArrayId = array_column($Media,'virtuemart_product_id');
	//Ahora montamos array (MediaResumen)
	if (isset($CArrayExiste['no'])){
		$MediaResumen['NoUrl'] = $CArrayExiste['no'];
	} else {
		$MediaResumen['NoUrl'] =0;
	}
	if (isset($ArrayId[0])){
		
		$MediaResumen['NoUtiliza'] = $ArrayId[0]; // Cantidad de registro que id producto es 0
	}else{
		$MediaResumen['NoUtiliza'] = 0;
	}
	// Obtenemos array de producto.
	$productos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
	// Ahora nos falta solo los datos de:
	// Ficheros que no son imagenes en el directorio .
	// Ficheros que no existen en Media
	// Los registros Media que no encuentra URL
	// Los registros en Media que no se utilizan en producto.
	
	
	// Si hubo error antes mostrar container , se bloquea ...
	if ($error != ''){
	 echo '<div class= "container"><h4>Hubo un error</h4>'.$error.'</div>';
	 exit;	
	} 
	
	?>

	<div class="container">
		<div class="col-md-8">
			<h1>Analizamos las imagenes de productos de virtuemart</h1>
			<p>El objetivo es saber: </p>
			<ul> 
			<li> <strong>Ficheros existentes:</strong> Cuantos ficheros existen en el directorio asignado a productos.</li>
			<li> <strong>Ficheros No imagenes:</strong> Cuantos de esos ficheros no son imagenes o su extension no es correcto. </li>
			<li> <strong>Ficheros No existen Media:</strong> Cuantos ficheros existen directorio y no en tabla de Media</li>
			<li> <strong>Media Registros:</strong> Cuantos registros hay en virtuemart_media (solo tipo producto).</li>
			<li> <strong>Media url No encuentra:</strong> Comprobamos cuantas urls de media no son correctas.</li>
			<li> <strong>Media Reg.No utiliza:</strong> Cuantos registros de virtuemart_media de tipo product no se utilizan.</li>
			<li> <strong>Productos Total:</strong>Cantidad de Productos que hay.</li>
			<li> <strong>Productos sin imagen:</strong>Que productos no tiene imagen asignada.</li>
			<li> <strong>Productos Imagen Mal:</strong>Cuantos productos tiene una imagen MAL asignada, no existe.</li>
			</ul> 
			
		</div>
		<div class="col-md-4">
			<h3>Pasos que realizamos</h3>
			<ol>
				<li>Array ficheros en directorio virtuemart/product</li>
				<li>Buscamos el campo `virtuemart_media_id` en la tabla `$prefijoTabla_virtuemart_medias` que contenga direccion del fichero en el campo 'file_url'.</li>
				<li>Buscamos el id 'vituemart_media_id' en la tabla `$prefijoTabla_virtuemart_product_medias`  para saber si se usa en algún producto, si no se usa entonces </li>	
			</ol>		
			<div id="proceso">
			</div>
		</div>
		<div class="col-md-12">
		<h4>Resultado</h4>
			<div>
				<div class="floatL marginL20">
					Ficheros existentes 
					<span class="label label-default"><?php echo $Media['NumeroFiles'];?></span>
				</div>
				<div class="floatL marginL20">
					Ficheros NO imagenes 
					<span class="label label-default">?</span>
				</div>
				<div class="floatL marginL20">
					Ficheros No Media 
					<span class="label label-default">?</span>
				</div>
			</div>
			<div>
				<div class="floatL marginL20">
					Media Total Reg
						<span class="label label-default"><?php echo $Media['NRegProducto'];?></span>
				</div>
				<div class="floatL marginL20">
					Media url No encuentra 
					<span class="label label-default"><?php echo $MediaResumen['NoUrl'];?></span>
				</div>
				<div class="floatL marginL20">
					Media Reg.No utiliza 
					<span class="label label-default"><?php echo $MediaResumen['NoUtiliza'];?></span>
				</div>
			</div>
			<div>
				<div class="floatL marginL20">
					Productos Total 
					<span class="label label-default"><?php echo $productos['TotalProductos'];?> </span>
				</div>
				<div class="floatL marginL20">
					<a href="./vistaMedioProductos.php">
					Productos sin imagen 
						<span class="label label-default"><?php echo $productos['SinIdMedia'];?> </span>
					</a>
				</div>
				<div class="floatL marginL20">
					Productos Mal imagen 
					<span id="PMImagen" class="label label-default">? </span>
				</div>
			</div>
		</div>
		
		<script>
         //~ // Se ejecuta cuando termina de carga toda la pagina.
            $(document).ready(function () {
                comprobarProductos();
                
            });
        </script>
        
	
	
	
	
</body>
</html>

