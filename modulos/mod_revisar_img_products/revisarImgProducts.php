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
// Ahora comprobamos hay parametros en la url
if (isset($_GET['directorio'])) {
	$directorioActual = $_GET['directorio'];
} else {
	$directorioActual = 'raiz';
}?>
</head>
<body>
	<?php 
		include './../../header.php';
		include 'funciones.php';
	?>
    <script src="<?php echo $HostNombre; ?>/modulos/mod_revisar_img_products/funciones.js"></script>
<?php
	// Inicializamos variable de inicio y entorno:
	$error ='';
	$ruta = $RutaServidor.$DirImageProdVirtue;
	$ficheros = array ();
	//Si estamos en un sub-directorio entonce $DirImageProdVirtue cambia....
	if ($directorioActual != 'raiz'){
		$DirImageProdVirtue = $DirImageProdVirtue.$directorioActual.'/';
		$ruta = $ruta.$directorioActual.'/';
	}
	//Obtenemos array de ficheros y directorios que existen en directorio asignado para productos.
	$DirectoriosFiles = filesProductos($RutaServidor,$DirImageProdVirtue); 
	// Obtenemos files y directorios 
	$files = $DirectoriosFiles['fichero'];
	$directorios = $DirectoriosFiles['directorio'];
	// Ahora obtenemos los ficheros que no son jpg o png
	$instruccion = "find ".$ruta." -mindepth 1 -maxdepth 1 -type f \! \( -iname '*jpg' -or -iname '*png' \)";
	// Obtenemos los ficheros que no son jpg , ni png
	exec($instruccion,$out,$e);
	$ficheros['NoImagenes']= $out;
	
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
		$error .= '<p>Hubo en error en Media ya que NO obtuvo la misma cantidad ficheros... </p>';
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
		
	// Si hubo error antes mostrar container , se bloquea ...
	if ($error != ''){
	 echo '<div class= "container"><h4>Hubo un error</h4>'.$error.'</div>';
	 exit;	
	} 
?>

	<?php 
	// Código para debug
	echo '<pre>';
	print_r($out);
	echo '</pre>';
	?>
	

	<div class="container">
		<div class="col-md-12"><h1>Analizamos Imagenes de productos en virtuemart</h1></div>
		<div class="col-md-8">
			<h3>Objetivo:</h3>
			<p>El objetivo es analizar las imagenes que utilizan los productos de virtuemart, tanto los registros en la tablas como los directorios donde se guardan los ficheros.</p>
			<p>La tabla donde se guardan las rutas es //prefijo//_virtuemart_medias.</p>
			<p>La tabla donde se guarda que imagenes utiliza un producto, es //prefijo_virtuemart_product_medias</p>
			
			
		</div>
		<div class="col-md-4">
			<h3>Barra de proceso</h3>
			<div id="proceso">
			</div>
		</div>
		<div class="col-md-12">
		
			<!-- Mostramos rutero de carpetas -->
			<div class="col-md-3">
				<h4>Analisis de directorio:</h4>
				<p>Saber cuantas y cuales son las carpetas existen en el directorio asigando para los productos</p>
				<h5><strong>Directorio actual:<?php echo '/'.$directorioActual;?></strong></h5>
				<?php if ($directorioActual != 'raiz') { ?>
					<a href="./revisarImgProducts.php">
				<?php } ?>
				<span class="glyphicon glyphicon-home"></span>
				<?php if ($directorioActual != 'raiz') { ?>
					</a> 
				<?php } ?>
				<div>
					Sub-Directorios 
					<span class="label label-default"><?php echo count($directorios);?></span>
				</div>
				<?php
					// Mostramos las carpetas
					foreach ($directorios as $directorio){
						echo '-> '; // Separador...
						if ($directorioActual != $directorio['Nombre']) { ?>
								<a href="?<?php echo'&directorio='.$directorio['Nombre']?>">
						<?php }
						echo $directorio['Nombre']; 
						if ($directorioActual != $directorio['Nombre']) { ?>
								</a><br/>
						<?php }
					}
				?>
			</div>
			<div class="col-md-3">
				<h4>Analisis de Ficheros:</h4>
				<p>Analizamos los ficheros que dentro del directorio</p>
				<ul> 
					<li> <strong>Ficheros existentes:</strong> Cuantos ficheros existen en cada esas carpetas.</li>
					<li> <strong>Ficheros No imagenes:</strong> Cuantos ficheros no son imagenes o su extension no es correcto. </li>
					<li> <strong>Ficheros No utiliza Media:</strong> Cuantos registros de virtuemart_media de tipo product no se utilizan.</li>
				</ul> 
				<div class="floatL marginL20">
					Ficheros existentes 
					<span class="label label-default"><?php echo $Media['NumeroFiles'];?></span>
				</div>
				<div class="floatL marginL20">
					Ficheros NO imagenes 
					<span class="label label-default"><?php echo count($ficheros['NoImagenes']);?></span>
				</div>
				<div class="floatL marginL20">
					Ficheros No Media 
					<span class="label label-default">?</span>
				</div>
			</div>
			<div class="col-md-3">
				<h4>Analisis de Productos:</h4>
				<ul>
					<li> <strong>Productos Total:</strong>Cantidad de Productos que hay.</li>
					<li> <strong>Productos sin imagen:</strong>Que productos no tiene imagen asignada.</li>
					<li> <strong>Productos Imagen Mal:</strong>Cuantos productos tiene una imagen MAL asignada o no existe.</li>
				</ul>
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
			
			<div class="col-md-3">
				<h4>Analisis de Medios:</h4>
				<p>Llamamos Medios, a la tabla que utiliza virtuemart para "Gestion de Medios"</p>
				<ul>
					<li> <strong>Registros en Media:</strong> Cuantos registros hay en virtuemart_media (solo tipo producto).</li>
					<li> <strong>Url de Media mal:</strong> Comprobamos cuantas urls de media no son correctas.</li>
				</ul>
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
			

		</div>
		
		<script>
			var arrayConsulta;
			var NEnviado=0;
			// Se ejecuta cuando termina de carga toda la pagina.
            $(document).ready(function () {
                comprobarProductos();
                
                
            });
        </script>
        
	</body>
</html>

