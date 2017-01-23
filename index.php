<!DOCTYPE html>
<html>
<head>
<?php
	include 'head.php';
?>
</head>
<body>
	<?php 
	include 'header.php';
	?>
	
	<section>
		<div class="container">
			<div class="col-md-8">
				<h1>Tratamiento de imagenes</h1>
				<h2>El objetivo de está aplicacion local </h2>
				<p>Conseguir una aplicación <strong>open source</strong> que podamos gestionar las imagenes y sobre todo las que utiliza virtuemart.</p>
				<p> Lo se pretende es :</p>
				<ul>
					<li>Saber que imagenes del directorio product de virtuemart son utilizados por los productos que tenemos Base Datos.</li>
					<li>Crear las miniaturas de la imagenes de productos a la medida que le indiquemos, recortando la imagen y redimensionado.</li>
				</ul>
				<p>Lo ideal sería que fuera un componente a parte, para poder realizarlo directamente en la web, sin tener que tener un servidor local y realizar instalaciones locales de nuestra web.</p>
				
				<div>
				<h3>Preguntas frecuentes de Recorte Imagenes</h3>
						<h5>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false">¿Donde hace las imagenes recortadas?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h5>
						<div id="collapse1" class="collapse pepe">
							<p>Al crear la miniaturas lo hace en directorio dentro /beta/TratoFotosVirtuemart/BancoFotos/ResizeCuadradas/ , para evitar romper la web, por lo que se recomienda hacer una copia de seguridad antes pegar las imagenes en la web. </p>
							<p>Recuerda que cuando sobreescribas las imagenes redimensionadas, tendras que limpiar cache del navegador para que puedas ver los cambios. </p>
							
							
						</div>
						
				</div>
				
				<div>
				<h3>Preguntas frecuentes de Virtuemart (Gestión Imagenes)</h3>
						<h5>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false">Si eliminamos una imagen directamente en el servidor de un producto ¿ que sucede?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h5>
						<div id="collapse1" class="collapse pepe">
							<p>Lo que sucede es que en la tabla del producto seguira apuntando a esa imagen y mostrara el error imagen no encontrada.</p>
							<p>El gestor multimedia de virtuemart (Archivos de medios) tambien aparecerá y seguira mostrando en el listado pero indicando icono de peligro, tipo no correcto.</p>
							
							
						</div>
						<h5>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false">Eliminamos una imagen en virtuemart en productos ( pestaña imagenes ) ¿ que sucede?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h5>
						<div id="collapse2" class="collapse pepe">
							<p>Realmente muy poco, lo unico que hace es eliminarlo de la tabla ( virtuemart_product_media ), es la tabla que cruza con la tabla ( virtuemart_media )</p>
							<p>El gestor multimedia de virtuemart (Archivos de medios) es controlado por la tabla virtuemart_media, por lo que seguirá existiendo en el servidor es imagen y su miniatura en el directorio asignado para productos.</p>
							<p>El gestor multimedia tiene un filtro por tipo, podemos seleccionar "productos" y podemos comprobar que la imagen que quitamos del producto , sigue apareciendo , incluso con el tipo producto.</p>
							
						</div>
						<h5>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false">¿Que hace realmente el gestor multimedia de virtuemart?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h5>
						<div id="collapse3" class="collapse pepe">
							<p> Archivos de medios es el gestor multimedia de virtuemart, que nos permite:</p>
							<ul>
							<li><strong>Editar:</strong> Modificar el "Título archivo único", poner los subtitulos, Clase css,texto alternativo en caso de no carga y incluso seleccionar otra url para esa imagen.</li>
							<li><strong>Eliminar:</strong> La elimina de la tabla de virtuemart_media.<br/> <strong>OJO!! No elimina la imagen del directorio original y tampoco la miniatura</strong>. ( categoria,product,fabricante,vendedor....)</li>
							<li><strong>Nuevo:</strong> Nos deja añadir una imagen nueva, donde le indicamos ubicacion-tipo, si es product, categoria, fabricante...Normalmente no lo utilizo. </li>
							<li><strong>Sincronizar Medios con virtuemart:</strong> Pienso que lo hace, es ver la imagenes que hay dentro del directorio stories/viertuemart ( por defecto) y crea miniaturas, añadiendolo a la tabla de virtuemart_medios. </li>
							</ul>
							<p> Por lo que observo en las opciones que tiene al añadir un fichero, el apartado virtuemart "Archivos de medios" de virtuemart, permite tener ficheros para luego descargar y poder venderlos. No lo utilizo , asi que no se como va.</p>
								
						</div>
				</div>
				
				
				
				
				
				<script>
				// Cambien id por clase, pero no funciona correctamente
				// ya que pone - a todos cuando uno esta desplegado.
				$(document).ready(function(){
				  $(".collapse.pepe").on("hide.bs.collapse", function(){
					$(".icono-collapse").html('+');
				  });
				  $(".collapse.pepe").on("show.bs.collapse", function(){
					$(".icono-collapse").html('-');
				  });
				});
				</script>
				
			
				
			</div>
			<div class="col-md-4">
				<div>
					<div>
					<h2>Requesitos mínimos</h2>
					<ul>
					<li>Servidor Apache.</li>
					<li>PHP y MySql</li>
					</ul>
					</div>
					
				</div>
				<div>
					<div>
					<h2>Configuracion actual</h2>
					<p>Los parametros que tenemos en configuracion, es <strong>importante que los revises, sobretodo si trabajas con varias webs a la vez.</strong></p>
					<ul>
					<li>Donde está instalado:<?php echo $HostNombre;?></li>
					<li>Donde guarda tmp:<?php echo $ConfDir_subida ;?></li>
					<li>Directorio Banco Imagenes:<?php echo $DirImagOriginales;?> </li>
					<li>Usuario BD(virtuemart):<?php echo $usuario;?></li>
					<li>Base Datos (virtuemart):<?php echo $BaseDatos;?> </li>

					</ul>
					</div>
					<div>
						<div class="alert alert-info">
						<p>PENDIENTE: Check de comprobación de configuracion.</p>
						</div>
						<div class="alert alert-danger">
							<p>Revisa el fichero configuracion para correcto funcionamiento.</p>
							<p>No se publica el directorio de imagenes para evitar problemas con el copyright que puedan tener.</p>
						</div>
												
					</div>
				</div>
			</div>
			
		</div>
	</section>
</body>
</html>
