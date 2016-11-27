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
				<p>Conseguir una aplicación totalmente independiente de virtuemar que :</p>
				<ul>
					<li>- Controle las imagenes que tienes virtuemart en productos y si realmente está asociadas a productos.</li>
					<li>- Redimensiones las pagina a las medidas que le indiquemos.</li>
				</ul>
				<p>Lo ideal sería que fuera un componente a parte, para poder realizarlo directamente en la web, sin tener que tener un servidor local y realizar instalaciones locales de nuestra web.</p>
				<div class="alert alert-info">
				<strong>IDEAS:</strong>
				<p>Crear un extension de virtuemart que realice este proceso.<strong>Dejaría ser independiente a virtuemart.</strong></p>
				</div>
				<h3>Controlar las imagenes que tenemos en virtuemart en productos</h3>
				<p> Para poder controlar la imagenes de virtuemart tenemos crear una <strong>conexion a la base datos</strong>, ya que sino no podemos saber que imagenes están añadidas a los productos.</p>
				<p> La conexion será a la base de datos local, aunque podría ser a la del <strong>servidor producción</strong>.</p>
				<p> Si te indica un <strong>error de conexión</strong>, revisa el fichero configuracion, recuerda que el fichero se llama configuracion.php</p>
			<div class="alert alert-info">
				Al crear la miniaturas lo hace en directorio dentro <?php echo $DirImagRecortadas;?> , para evitar romper la web, por lo que se recomienda hacer una copia de seguridad antes pegar las imagenes en la web.
			</div>
			<div class="alert alert-info">
				Recuerda que cuando sobreescribas las imagenes redimensionadas, tendras que limpiar cache del navegador para que puedas ver los cambios.
			</div>
			
				
			</div>
			<div class="col-md-4">
				<div>
				<h2>Información funcionamiento</h2>
				<p>Las funcionalidades que vamos hacer son: </p>
				<ul>
					<li> - Buscamos en BD de la web virtuemar si existen las imagenes que hay product </li>
					<li> - Recortamos y optimizamos imagenes imagenes de product</li>
				</ul>
				</div>
				<div>
				<h2>Requesitos mínimos</h2>
				<ul>
				<li>Servidor Apache.</li>
				<li>PHP y MySql</li>
				<li>Framework Bootstrap</li>
				</ul>
				<p>Esta aplicación no tiene necesidad conexion a internet</p>
				</div>
				<div>
					<div class="alert alert-info">
					<p>Está aplicación es OPEN SOURCE, con ello queremos decir que puedes utilizar este código en otras aplicaciones y modificarlo sin problemas.</p>
					</div>
					<div class="alert alert-danger">
					<p>Lo que no se puede es publicar son los datos de la base de datos y las imagenes para evitar problemas con el copyright si lo tiene.</p>
					</div>
				</div>
			</div>
			
		</div>
	</section>
</body>
</html>
