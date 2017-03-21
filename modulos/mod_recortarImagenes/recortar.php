<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
?>
<!-- Script acciones de usuario -->
<script>
// Declaramos variables globales javascript
var checkID = [];
var nombreFichero = [];
var extensionFichero = [];
var tipoFichero = [];
var anchoFichero = [];
var altoFichero = [];

// Funcion para controlar lo pulsado (botones y link )



</script>
</head>
<body>
<?php 
	include './../../header.php';
	include 'funciones.php';
?>
	<script src="<?php echo $HostNombre; ?>/modulos/mod_recortarImagenes/funciones.js"></script>

<?php
	// Variables reiniciadas
	$ficheros = array();
	$ficheroserroneos = array ();
	$sufijo = '_'.$ImgAltoCfg.'x'.$ImgAnchoCfg;
	//Saber cuantos fichero hay en origen sin importar si son imagenes.
	$CantidadFicheros = count(scandir($RutaServidor.$DirImagOriginales));
	$CantidadMiniaturas = count(scandir($RutaServidor.$DirImagRecortadas));
	$LimitePagina = 50;
	// Cargamos fichero de paginado, esto debería se una función donde enviamos 
	// 	total items
	//  limite paginacion 
	// 	pagina actual ¿?
	// 	url 
	// NOTA: De momento es un fichero.. 
	// 	Este nos devuelve $HtmlPG que lo imprimimos donde queramos.
	include 'paginado.php';
	// Los datos solo vamos a CARGAR y MOSTRAR LOS DE LA PAGINA.
	// Ahora tenemos crear array de imagenes, con las siguiente extructura:
		 //  [NumeroImagen] Array
		 //           [nombre] => nombre.extension
		 //           [ancho] => 500
		 //           [alto] => 375
		 //           [tipoimagen] => 'C' cuadrada, 'P' panoramica, 'V' verticalimage/jpeg
		 //           [tipofichero] => 1,2,3,4,5,6 ( gif,jpg,png y más extensiones pero no la utilizo) ver funcion exif_imagetype()
		 //			  [comprobacion] => Es el estado en la tabla, con el indicamos
		 //									"Existe Miniatura" 
		 //									"No existe Miniatura"
		 //							Los siguiente estados , lo ponemos en la tabla cuando:
		 // 								"Eliminado Miniatura"
		 //									"Nuevo" cuando la acabamos de crear.
	 $files = array_filter(glob($RutaServidor.$DirImagOriginales."*"), 'is_file');
	 // Files es un array con solo ficheros del directorio que indicamos.
	 // Esto implica que no todas son imagenes, listamos al final de la tabla.
	 $x=0;// Contador para imagenes correctas.
	 $y=0;// Contador para ficheros o imagenes erroneas.
	 // Creamos array que vamos utiliza ( $ficheros ) y ($ficheroserroneos)
	 $final		=$paginas['Actual'] * 50;
	 $inicio	=$final-$LimitePagina;
	 // Recorremos files son los de pagina que estamos.
	 for ($i = $inicio; $i < $final; $i++) {
		$file = $files[$i];
		 // Llamamos a funcion con ruta fichero ...
		 // donde comprueba 
		 $DatosImagen = DatosImagen($file);
		 // El fichero no es una imagen no la añadimos
		switch (true){
			case (!empty($DatosImagen['error'])):
				// Hay el parametro error entonces continuamos con foreach.
				$y= $y+1;
				$ficheroserroneos [$y] = $DatosImagen;
				continue;
				
			case ($DatosImagen['extension'] == '.jpeg'):
				// Si la extension es .jpeg entonces lo marcamos como fichero erroneo.
				$y= $y+1;
				$ficheroserroneos [$y] = $DatosImagen;
				continue;
			default:
				// Es una imagen
				// Ahora comprobamos que su estado.
				$fileMiniatura =$RutaServidor.$DirImagRecortadas.$DatosImagen['nombre'].$sufijo.$DatosImagen['extension'];
				
				if (file_exists ( $fileMiniatura)) {
					$Estado = 'Existe Miniatura';
				} else {
					$Estado = 'No existe Miniatura';

				}
				
				$DatosImagen['estado'] = $Estado;
				// Añadimos a array $ficheros.	
				$x= $x+1;
				$ficheros [$x] = $DatosImagen;
				// Ahora sabemos que exist
		}
		//~ echo '<pre>';
		//~ print_r($DatosImagen);
		//~ echo '</pre>';
	 }
	 
	 
		 $DestinoRe =	$RutaServidor.$DirImagRecortadas;
		
	//~ }?>

	<div class="container">
		
		<div class="col-md-8">
			<h1>Vamos recortar y redimensionar imagenes </h1>
			<p>La imagenes vamos a tratar son las imagenes que tenemos en el <strong>directorio origen</strong> que asignamos en configuracion.Recomiendo no poner el directorio de virtuemart directamente.</p>
			<div class="col-md-7">
				<h3>Parametros que tiene por defecto</h3>
				<ul>
				<li><strong>Nombre de servidor:</strong> <?php echo $NombreServidor;?></li>
				<li><strong>Ruta de servidor:</strong> <?php echo $RutaServidor;?></li>
				<li><strong>Directorio de imagenes Originales:</strong><?php echo $DirImagOriginales;?></li>
				<li><strong>Directorio destino de redimensiones:</strong><?php echo $DestinoRe ;?></li>
				<li><strong>La medida final de la imagen:</strong> <?php echo $sufijo;?></li>
				</ul>
			</div>
			<div class="col-md-5">
			<h3>Recuerda que:</h3>
			<ul>
			<li>Convierte las imagenes <strong>Panoramicas y verticales a cuadradas</strong>, desde el centroy luego las redimensiona, para ello las recorta en el centro.</li>
			<li>La imagenes cuadradas solo las redimensiona a las medidas que le indicamos en configuracion</li>
			</ul>
			
			</div>
		</div>
		<div class="col-md-4">
			<h2>Procesando</h2>
			<p>Indicamos las comprobaciones totales de los ficheros que encontramos en Banco Fotos.</p>
			<div>Ficheros encontrados en Origen: <span class="label label-default"><?php echo count($files);?></span></div>
			<div>Ficheros encontrados en Redimension: <span class="label label-default"><?php echo $CantidadMiniaturas;?></span></div>
			<div class="alert alert-info"><p> El resto de comprobaciones me queda pendiente realizar <strong>"expresion regular"</strong> para ejecutar ls</p></div>
			<h4>Proceso</h4>
			<p>Aquí debería estar barra proceso.</p>
			<div id="procesando">INACTIVO</div>
			
			
		</div>
		<div class="col-md-12">
			
			
			<div><hr></div>
			<div id="ListadoFicherosTratar">
			<h3>Opciones a realizar</h3>

			<div class="col-md-6">
				<input type="submit" value="Crear Miniatura" onclick="metodoClick('Redimensionar');"> 
			</div>
			<div class="col-md-6">
				<input type="submit" value="Eliminar Miniaturas" onclick="metodoClick('Eliminar');"> 
			</div>
			
			<?php  // Mostramos el paginado. 
			echo $htmlPG; 
			?>
			<h3>Comprobacion de esta pagina</h3>
			<div style="float:left;margin-left:20px;">Imagenes <span class="label label-default"><?php echo count($ficheros);?></span></div>
			
			<div style="float:left;margin-left:20px;">
				<a href="#ListadoFicherosErroneos" onclick="metodoClick('ListaFicherosErroneos');">No imagenes <span class="badge"><?php echo count($ficheroserroneos);?></span></a>
			</div>
			
			
			<div class="ficheros-tratar">
			<table class="table">
				<thead>
					<tr>
						<th><input type="checkbox" name="checkTotal" value="0" onchange="metodoClick('TodaSeleccion');">
						<span id="checkTotalPaginas" style="display:none;">Todas <a title="Todas las paginas">(*)</a>
						<input type="checkbox" name="checkTotalPaginas" value="0" onchange="metodoClick('TodasPaginas');" disabled='true'> 
						</a>
						</span>
						</th>
						<th>Nombre Fichero</th>
						<th>extension</th>
						<th>Tipo</th>
						<th>Alto</th>
						<th>Ancho</th>
						<th>Estado</th>
					</tr>
					</thead>
					<tbody>
						<?php
										
						$x=0;
						foreach ($ficheros as $imagen)
						{
							if  (empty($imagen['error'])) 
							{
								$class="";
								// Vamos utilizar class, para cambiar el colo de fila, si se produce alguno de estos casos:
								// 		- Si no es png,jpg,gif , ya virtuemarta genera un error.
								// 		- Si no tiene miniatura.
								
								$x= $x+1;
								?>
								<tr>
								<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $x;?>" value="<?php echo $x;?>"></td>
								<td><span id="nombreFic<?php echo $x;?>"><?php echo $imagen['nombre'];?></span></td>
								<td><span id="extensionFic<?php echo $x;?>"><?php echo $imagen['extension'];?></span></td>
								<td><span id="tipoFic<?php echo $x;?>"><?php echo $imagen['tipoimagen'];?></span></td>
								<td><span id="anchoFic<?php echo $x;?>"><?php echo $imagen['ancho'];?></span></td>
								<td><span id="altoFic<?php echo $x;?>"><?php echo $imagen['alto'];?></span></td>
								
								<td><span id="estadoFic<?php echo $x;?>"><?php echo $imagen['estado'];?></span></td>

								</tr>
							<?php
							}
						}?>
								
				</tbody>
			</table>
			</div>
			</div>
			<!-- Ahora mostramos ficheros erroneos. -->
			<div  id="ListadoFicherosErroneos">
			<h2>Listado ficheros erroneos</h2>
			<p> Revisamos si el fichero es una imagen (gif, jpg o png), si es otra extension entonces lo registramos como un fichero erroneo.</p>
			<?php
			foreach ($ficheroserroneos as $ficheroError)
			{
			echo '<pre>';
			print_r($ficheroError);
			echo '</pre>';
			}
			?>
			
			
			
			
			</div>
			
			
			
			
			
			
		</div>
		
	
	</div>
	
</body>
</html>

