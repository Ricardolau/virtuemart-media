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
				<p>Conseguir una aplicación que podamos gestionar las imagenes virtuemart :</p>
				<ul>
					<li>- Saber que imagenes del directorio product de virtuemart son utilizados por los productos que tenemos Base Datos.</li>
					<li>- Creando la imagenes miniatura de la imagenes de productos a la medida que le indiquemos, recortando la imagen y redimensionado.</li>
				</ul>
				<p>Lo ideal sería que fuera un componente a parte, para poder realizarlo directamente en la web, sin tener que tener un servidor local y realizar instalaciones locales de nuestra web.</p>
				<div class="alert alert-info">
				<strong>IDEAS:</strong>
				<p>Crear un extension de virtuemart que realice este proceso.<strong>Dejaría ser independiente a virtuemart.</strong></p>
				</div>
				<div>
						<h4>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false">¿Saber que imagenes realmente utilizamos en los productos?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h4>
						<div id="collapseOne" class="collapse pepe">
							<p>El gestor multimedia de virtuemart (Archivos de medios) <strong>NO</strong> nos facilita que ficheros multimedia estan utilizados por que productos.</p>
							<p> Por esté motivo, el apartado de "Revisar Imagenes Virtuemart" donde necesitamos una <strong>conexion a la base datos local</strong> de nuestra web, para poder indicar que ficheros son utilizados y por cuales.</p>
							<p> De momento no controlamos si realmente hay conexion, por lo que te recomiendo que revises log errores de servidor si ves que no funciona correctamente.</p>
							<p> Y revisa el fichero configuracion donde indicamos servidor, base de datos y usuario.</p>
							
						</div>
				</div>
				<div>
						<h4>
							<?php 
							/* Para que este expandido, lo hace con date aria-expanded ="false" o "true"
							 * */
							?>
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false">¿Que hace realmente el gestor multimedia de virtuemart?
							<span style="float:right;" class="icono-collapse">+</span>
							</a>
							
						</h4>
						<div id="collapseTwo" class="collapse pepe">
							<p> Archivos de medios es el gestor multimedia de virtuemart, que nos permite:</p>
							<ul>
							<li><strong>Editar:</strong> Modificar el "Título archivo único", poner los subtitulos, Clase css,texto alternativo en caso de no carga y incluso seleccionar otra url para esa imagen.</li>
							<li><strong>Eliminar:</strong> La elimina de la tabla de virtuemart_media y elimina la imagen miniatura. <strong>OJO!! No elimina la imagen del directorio original</strong>. ( categoria,product,fabricante,vendedor....)</li>
							<li><strong>Nuevo:</strong> Nos deja añadir una imagen nueva, donde le indicamos ubicacion-tipo, si es product, categoria, fabricante...Normalmente no lo utilizo. </li>
							<li><strong>Sincronizar Medios con virtuemart:</strong> Pienso que lo hace, es ver la imagenes que hay dentro del directorio stories/viertuemart ( por defecto) y crea miniaturas, añadiendolo a la tabla de virtuemart_medios. </li>
							</ul>
							<p> Por lo que observo en las opciones que tiene al añadir un fichero, el apartado virtuemart "Arichovs de medios" de virtuemart, permite tener ficheros para luego descargar y poder venderlos. No lo utilizo , asi que no se como va.</p>
								
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
				<div class="alert alert-info">
				Al crear la miniaturas lo hace en directorio dentro <?php echo $DirImagRecortadas;?> , para evitar romper la web, por lo que se recomienda hacer una copia de seguridad antes pegar las imagenes en la web.
				</div>
				<div class="alert alert-info">
				Recuerda que cuando sobreescribas las imagenes redimensionadas, tendras que limpiar cache del navegador para que puedas ver los cambios.
				</div>
			
				
			</div>
			<div class="col-md-4">
				<div>
				<h2>Requesitos mínimos</h2>
				<ul>
				<li>Servidor Apache.</li>
				<li>PHP y MySql</li>
				</ul>
				</div>
				<div>
					<div class="alert alert-info">
					<p>Está aplicación es OPEN SOURCE, con ello queremos decir que puedes utilizar este código en otras aplicaciones y modificarlo sin problemas.</p>
					</div>
					<div class="alert alert-danger">
					<p>Revisa el fichero configuracion para correcto funcionamiento.</p>
					<p>No se publica el directorio de imagenes para evitar problemas con el copyright que puedan tener.</p>
					</div>
				</div>
			</div>
			
		</div>
	</section>
</body>
</html>
