<header>
   <!-- Debería generar un fichero de php que se cargue automaticamente el menu -->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
	<a class="navbar-brand" href="#">Catalogo</a>
      </div>
      <ul class="nav navbar-nav">
	<li class="active"><a href="<?php echo $HostNombre.'/index.php'?>">Home</a></li>
	<li><a href="<?php echo $HostNombre.'/modulos/mod_recortarImagenes/recortar.php';?>">Recortar Imagenes</a></li>
	<li><a href="<?php echo $HostNombre.'/modulos/mod_revisar_img_products/revisarImgProducts.php';?>">Revisar Imagenes Productos</a></li>
      </ul>
    </div>	
  </nav>
  <!-- Fin de menu -->
</header>
