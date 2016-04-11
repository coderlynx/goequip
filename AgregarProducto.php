<?php
session_start();
//if(!isset($_SESSION["nombre"]))	
  //header("Location: home");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
  
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Agregar Producto</title>
	</head>
	
	<body>
        <div id="contenedor"></div>


        <h3 id="nombre_formulario">Agregar Producto</h3>
        <form id="">

            <input id="modelo" type="text" placeholder="Modelo" value="">
            <input id="descripcion" type="text" placeholder="Descripcion" value="">
            <input id="talle" type="text" placeholder="Talle" value="">
            <input id="color" type="text" placeholder="Color" value="">
            <input id="stock" type="text" placeholder="Stock" value="">
            <input id="precio" type="text" placeholder="Precio" value="">
            <input type="button" id="btnAltaProducto" value="Guardar" />
        </form>
			
	
	<?php include('scripts.html') ?>
	<script src="js/Producto.js"></script>
	<script>
		$( document ).ready(function() {

			Producto.init();

		});
	
	</script>	
	</body>

</html>