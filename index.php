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
				<div class="panel-group" id="accordion">
					<div class="panel panel-default">
						<div class="panel-heading">
						<h3 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">¿Saber que imagenes realmente utilizamos en los productos?</a>
						</h3>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
								<p> Para saber que imagenes utilizamos en los productos creamos una <strong>conexion a la base datos local</strong>.</p>
								<p> Ver el fichero configuracion donde indicamos servidor, base de datos y usuario.</p>
								<p> De momento no controlamos si realmente hay conexion, por lo que te recomiendo que revises log errores de servidor si ves que no funciona correctamente.</p>
								
							</div>
						</div>
					</div>
				</div>
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
