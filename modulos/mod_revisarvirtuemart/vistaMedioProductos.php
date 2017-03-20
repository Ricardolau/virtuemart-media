<!DOCTYPE html>
<html>
<head>
<?php
	include './../../head.php';
	include './../../modulos/mod_conexion/conexionBaseDatos.php';
?>
<script>
// Variable Globales
var checkID;
var nombreFichero;
// Funcion para controlar lo pulsado (botones y link )
function metodoClick(pulsado){
	console.log("Inicimos switch de control pulsar");
	switch(pulsado) {
		case 'ComprobarLocal':
			// Quiere decir que pulso check de toda la seleccion.
			alert('Pulso ComprobarLocal');
			break;
			
		case 'TodaSeleccion':
			// Quiere decir que pulso check de toda la seleccion.
			MarcarTodas();
			break;
			
		case 'ComprobarEstado':
			// Quiere decir que pulso comprobar.
			VerFicheroSeleccionado ();
			comprobarEstado()
			break;
		default:
			alert('Error pulsado incorrecto');
	}
}
function MarcarTodas() {
	// Esta funcion hace es seleccionar o deseleccionar todas las imagenes de la pagina actual
	// si lo tenemos marcado checkTotal, entonces desmarcamos todos.
	checkID = [] ; // Reiniciamos varible global.
	var i = 0;
	var valor= false;
	if($('input[name=checkTotal]').is(':checked')){
		// Quiere decir que esta marcado, por lo que desmarcamos.
		valor = true;
	 } else {
		// Quiere decir que marcamos todos.
		valor = false;
		
	 }
	$(".rowCheckFichero").each(function(){ 
		i++;
		//todos los que sean de la clase row1
		$('input[name=checkFic'+i+']').prop("checked", valor);
		console.log('Entro en cambio '+'checkFic'+i);
	});
	
	return;
	
}

// Funcion para leer los check que se seleccionaron
function VerFicheroSeleccionado (){
		
			// Contamos check están activos.... 
			// Reiniciamos varibles globales.
			checkID = [] ;
			nombreFichero = [];
			// variable funcion para bucle.			
			var i= 0;
			// Con la funcion each hace bucle todos los que encuentra..
			$(".rowCheckFichero").each(function(){ 
				i++;
				//todos los que sean de la clase row1
				if($('input[name=checkFic'+i+']').is(':checked')){
					// Solo entramos en los que están seleccionado.
					// Ahora tengo hacer array :
					valor = '0';
					valor = $('input[name=checkFic'+i+']').val();
					checkID.push( valor );
					
					valor = '0';
					nombreCampo = 'nombreFichero'+i;
					valor = document.getElementById(nombreCampo).innerHTML;
					nombreFichero.push(valor);
					
					
				}
				
			});
			//~ console.log('ID de Ficheros seleccionadas:'+checkID);
			//~ console.log('Nombre de Ficheros seleccionadas:');
			//~ console.log(nombreFichero);
			return;
		
}


// Funcion para comprobar si existe la imagen en la instalación de la web
function comprobarEstado(){
	// Comprobamos que si hay alguno seleccionado.
	alert('Quieres comprobar Estado de todos?');
	$('input[name=checkTotal]').prop("checked", true);
	MarcarTodas();
	VerFicheroSeleccionado();
	if (nombreFichero){
		// Obtenemos nombreFichero con check
		// Script que utilizamos para ejecutar AJAX.
		var parametros = {
		 "pulsado" 	: 'comprobarEstado',
		 "ficheros": nombreFichero
				};
		$.ajax({
			data:  parametros,
			url:   'tareas.php',
			type:  'post',
			beforeSend: function () {
					console.log('Enviamos '+ nombreFichero[0]);
					$("#proceso").html("Enviamos "+ nombreFichero[0]);

			},
			success:  function (response) {
					// Cuando se recibe un array con JSON tenemos que parseJSON
					//~ var respuesta = response;
					console.log('Recibimos respuesta');
					$("#proceso").html("Termino");
					console.log('Respuesta');
					console.log(response);
					//~ console.log(respuesta.length);
					//~ console.log(response.toSource());
					for (var id in response){
						console.log(response[id]);
						if (response[id] === ' Existe'){
							console.log( 'Disabled check'+ id);
							$("#Proceso"+id).html("Existe ");
							$('input[name=checkFic'+id+']').prop("checked", false);// De marco
							$('input[name=checkFic'+id+']').attr("disabled", true); // Desactivo
						} else {
							console.log(' Ponemos no existe en proceso' +id);
							$("#Proceso"+id).html("No existe servidor");

						
						}
					}
					// Ahora desmarcamos todas..
					$('input[name=checkTotal]').prop("checked", false);
					MarcarTodas();

			}
		});
	}
}

 
</script>


</head>
<body>
<?php 
		include './../../header.php';
		include 'funciones.php';
?>
<script src="<?php echo $HostNombre; ?>/modulos/mod_revisarvirtuemart/funciones.js"></script>
<?php
	$TodosProductos = ObtenerProductos($BDVirtuemart,$prefijoTabla);
 // Creamos array con producto que no tiene imagen
	$productosSinImagen = array();
	$i= 0;
	foreach ($TodosProductos as $productos){
		if (isset($productos['Imagenes']) != true && strlen($productos['product_gtin'])>0){
			$i++;
			$productosSinImagen[$i]['product_gtin']=$productos['product_gtin'];
			$productosSinImagen[$i]['product_id']=$productos['product_id'];
		}
	}
 if (isset($TodosProductos['ErrorConsulta'])){
	echo '<div class= "container"><h4>Hubo un error de conexion con la base de datos o no hay articulos pasados</h4>';
	echo '<p>'.$TodosProductos['ErrorConsulta'].'</p></div>';
	exit;
}
?>
<div class="container">
	<h1>Mostramos los productos que no tiene imagen</h1>	
	
	<div class="col-md-8">
		<?php echo 'Nos faltan imagenes en productos:'.$TodosProductos ['SinIdMedia'];	?>
		<input type="submit" value="Comprobar Local" onclick="metodoClick('ComprobarLocal');"> 

	</div>
	<div class="proceso" id="proceso">
	<!-- Mostramos barra y proceso que realizamos -->
	</div>
	<table class="table table-striped">
    <thead>
      <tr>
        <th>TODAS
        <input type="checkbox" name="checkTotal" value="0" onchange="metodoClick('TodaSeleccion');">
		</th>
        <th>ID Producto</th>
        <th>Ref_gtin</th>
        <th>Local<a title="Comprobamos servidor local si existe">(!)</a></th>
        <th>Abastros<a title="Comprobamos servidor abastros si existe">(!)</a></th>
        <th>Proceso<a title="No existe, Copiada y Existe">(!)</a></th>
      </tr>
    </thead>
    <tbody>
    <?php
		$i= 0;
		foreach ($productosSinImagen as $Productos){
			$i++;
			?>
			<tr>
				<td class="rowCheckFichero"><input type="checkbox" name="checkFic<?php echo $i;?>" value="<?php echo $i;?>"></td>
				<td><?php echo $Productos['product_id'];?></td>
				<td><span id="nombreFichero<?php echo $i;?>"><?php echo $Productos['product_gtin'];?></span></td>
				<td class="rowCompLocal"><span id="CompLocal<?php echo $i;?>"></span></td>
				<td class="rowCompAbastro"><?php echo '<a href="http://www.abantos-autoparts.com/tienda/fotos/"'.$Productos['product_gtin'].'jPG">Link abastros</a>';?>"></span></td>
				<td class="ProcesoEstado"><span id="Proceso<?php echo $i;?>"></span></td>

			</tr>	
		<?php
			if ($i >250){
				//Salimo de bucle
				break;
			}
		}
	?>
      <tr>
      </tr>
      
      
    </tbody>
  </table>
	<?php
	//~ foreach ($TodosProductos as $Productos){
			//~ 
	//~ }
	//~ 
	//~ echo '<pre>';
		//~ print_r($TodosProductos );
	//~ echo '</pre>';
	?>
</div>
<script>
         //~ // Se ejecuta cuando termina de carga toda la pagina.
            $(document).ready(function () {
                VerFicheroSeleccionado ();
				comprobarEstado()
                
                
            });
        </script>
</body>
</html>
