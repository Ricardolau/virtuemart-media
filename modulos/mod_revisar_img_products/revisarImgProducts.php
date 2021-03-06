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
	?>
</head>
<body>
	<?php 
		include './../../header.php';
		include 'funciones.php';
	?>
    <script src="<?php echo $HostNombre; ?>/modulos/mod_revisar_img_products/funciones.js"></script>
<?php
	// Inicializamos variable de inicio y entorno:
	// 		$Nom_Dir_Actual : Es nombre del directorio actual.
	//		$Dir_Actual: Ruta del directorio actual DESDE la instalacion de joomla... NO ES UNA RUTA COMPLETA. 
	// 		$rutas : Ruta completa
	// 		$error : la utilizamos para saber si hubo un error antes de cargar la pagina.
	//
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
	// Obtenemos directorios
	$directorios = ObtenerDirectorios($ruta);
	
	// Obtenemos los ficheros que no son imagenes(jpg o png)
	$instruccion = "find ".$ruta." -mindepth 1 -maxdepth 1 -type f \! \( -iname '*jpg' -or -iname '*png' \)";
	exec($instruccion,$out,$e);
	$ficheros['NoImagenes']= $out;
	
	// Obtenemos productos.
	$productos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
	if (isset($TodosProductos['ErrorConsulta'])){
		// Ahora comprobamos si hubo un error en la consulta,
		$error = '<p>Error de conexion con la base de datos o no hay articulos pasados</p>';
		$error .= '<p>'.$TodosProductos['ErrorConsulta'].'</p><br/>';
	}	
	
	
	//Obtenemos array de ficheros y directorios que existen en directorio asignado para productos.
	$files = filesProductos($RutaServidor,$Dir_Actual,$DirInstVirtuemart); 
	// Obtenemos files y directorios 
	
	if (empty($files['error'])){
		$ficheros['Total'] = count($files);
	} else {
		$error .= $files['error'];
	}
	
	//Obtenemos la cantidad de ficheros que no se utilizan en virtuemart_media.
	//~ if ($ficheros['Total']){
		//~ $ficheros['ImgNoUtilizadas']= fileNoUtilizados ($BDVirtuemart,$prefijoTabla,$files,$Dir_Actual);
	//~ } 
	
	// Ahora comprobamos:
	//		1.- Registros que hay en tabla Media
	//		2.- Que registros no se utilizan
	//		3.- Si todos los registros (url) son correcta y existen.
	
	//~ $Media = ObtenerDatosMedia( $files, $BDVirtuemart,$prefijoTabla,$RutaServidor,$DirInstVirtuemart,$prefijoTabla );
	//~ if ($Media['NumeroFiles']!= count($files)){
		//~ // Quiere decir que el numero ficheros y el numero registros de media_product no son iguales
		//~ $error .= '<p>Hubo en error en Media ya que NO obtuvo la misma cantidad ficheros... </p>';
	//~ }
	// Obtenemos el valores de [Existe] y de [virtuemart_product_id]
	//~ $CArrayExiste = array_count_values(array_column($Media, 'Existe'));// Creamos array suma de resultados
	//~ $ArrayId = array_count_values(array_column($Media, 'virtuemart_product_id'));// Creamos array suma de resultados
	// $ArrayId = array_column($Media,'virtuemart_product_id');
	//Ahora montamos array (MediaResumen)
	//~ if (isset($CArrayExiste['no'])){
		//~ $MediaResumen['NoUrl'] = $CArrayExiste['no'];
	//~ } else {
		//~ $MediaResumen['NoUrl'] =0;
	//~ }
	//~ if (isset($ArrayId[0])){
		//~ 
		//~ $MediaResumen['NoUtiliza'] = $ArrayId[0]; // Cantidad de registro que id producto es 0
	//~ }else{
		//~ $MediaResumen['NoUtiliza'] = 0;
	//~ }
			
	// Si hubo error antes mostrar container , se bloquea ...
	if ($error != ''){
	 echo '<div class= "container"><h4>Hubo un error</h4>'.$error.'</div>';
	 exit;	
	} 
?>

	<?php 
	// Código para debug
	//~ echo $DirImageProdVirtue;
	//~ $fi1 = array_column($ficheros['ImgNoUtilizadas'], '0);

	//~ echo '<pre>';
	//~ print_r($ficheros['ImgNoUtilizadas']);
	//~ echo '</pre>';
	//~ echo '<pre>';
	//~ print_r($files );
	//~ echo '</pre>';
	
	?>
	

	<div class="container">
		<div class="col-md-12"><h1>Analizamos Imagenes de productos en virtuemart</h1></div>
		<div class="col-md-8">
			<h3>Objetivo:</h3>
			<p>El objetivo es analizar las imagenes que utilizan los productos de virtuemart, tanto los registros en la tablas como los directorios donde se guardan los ficheros.</p>
			<a href ="" data-toggle="modal" data-target="#miModal">Más info</a>
			
			<!-- Caramamos pantallas modal , que muestrar al hacer ponerse encima -->
			<?php include './paginaModal.php'; ?>
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
				<h5><strong>Directorio actual:<?php echo '/'.$Nom_Dir_Actual;?></strong></h5>
				<?php if ($Nom_Dir_Actual != 'raiz') { ?>
					<a href="./revisarImgProducts.php">
				<?php } ?>
				<span class="glyphicon glyphicon-home"></span>
				<?php if ($Nom_Dir_Actual != 'raiz') { ?>
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
						if ($Nom_Dir_Actual != $directorio['Nombre']) { ?>
								<a href="?<?php echo'&directorio='.$directorio['Nombre']?>">
						<?php }
						echo $directorio['Nombre']; 
						if ($Nom_Dir_Actual != $directorio['Nombre']) { ?>
								</a><br/>
						<?php }
					}
				?>
			</div>
			<div class="col-md-3">
				<h4>Analisis de Ficheros:</h4>
				<p>Analizamos los ficheros que dentro del directorio</p>
				<ul style="padding-left:15px;"> 
					<li> <strong>Total Ficheros:</strong> Cuantos ficheros existen en esta carpeta <strong><?php echo '/'.$Nom_Dir_Actual;?></strong></li>
					<li> <strong>Ficheros No imagenes:</strong> Cuantos no son imagenes (jpg o png). </li>
					<li> <strong>Ficheros No utiliza Media:</strong> Cuantos no se utilizan en virtuemart_media como tipo de product.</li>
				</ul> 
				<div class="floatL marginL20">
					Total Ficheros 
					<span class="label label-default"><?php echo $ficheros['Total'];?></span>
				</div>
				
				<div class="floatL marginL20">
					<?php if (count($ficheros['NoImagenes'])>0){
						$advertencia= 'warning';
						echo '<a href="./vistaFic_NoValidos.php?directorio='.$Nom_Dir_Actual.'">';
					} else {
					$advertencia= 'default';
					}
					?>
					Ficheros NO imagenes 
					
					<span class="label label-<?php echo $advertencia; ?>"><?php echo count($ficheros['NoImagenes']);?></span>
					<?php if (count($ficheros['NoImagenes'])>0)
					{
						echo '</a>';
					}
					?>
				</div>
				<div class="floatL marginL20" id="IDImgNoUtiliza">
					Ficheros No Media 
					<span  class="label label-default">?</span>
				</div>
			</div>
			<div class="col-md-3">
				<h4>Analisis de Productos:</h4>
				<ul style="padding-left:15px;">
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
					<span id="PMImagen" class="label label-default"> ? </span>
				</div>
			</div>
			
			<div class="col-md-3">
				<h4>Analisis de Medios:</h4>
				<p>Llamamos Medios, a la tabla que utiliza virtuemart para "Gestion de Medios"</p>
				<ul style="padding-left:15px;">
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
			
			var NomDirActual = '<?php echo $Nom_Dir_Actual;?>';
			var arrayProcesos = new Array("ImagenesQueNoseUtilizan", "Proceso1", "Proceso2");
			var ProcesoActual = '';
			var segundero = 0;
			var contador; 
			// Se ejecuta cuando termina de carga toda la pagina.
            $(document).ready(function () {
                procesosPendientes();
                
                
            });
        </script>
        
	</body>
</html>

