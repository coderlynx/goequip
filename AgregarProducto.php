<?php
session_start();
if(!isset($_SESSION["nombre"]))	
  //header("Location: hoysesale");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
  
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Agregar Producto</title>
	</head>
	
	<body>
			<div class="">
			<h3 id="nombre_formulario">Agregar Producto</h3>
			<form id="">

				<input id="nombre" type="text" placeholder="Nombre" value="">
				
			</form>
			
		</div>
		
	
	<?php include('scripts.html') ?>
	<script>
		$( document ).ready(function() {
			

			Producto.insertProducto();

			Producto.editProducto();	


		});
	
	</script>	
	</body>

</html>