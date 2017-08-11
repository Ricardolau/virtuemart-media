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
				
				<div class="PreguntasFrecuentes">
				<h3>Preguntas Frecuentes</h3>
					<?php include 'preguntasFrecuentes.html' ;?>
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
