<?php
require_once ('php/autoload.php');
session_start();
    

//if(!isset($_SESSION["nombre"]))	
  //header("Location: home");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
  
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Producto</title>
	</head>
	
	<body>
       <input value="Cerrar Sesion" id="btnCerrarSesion" type="button" style="float:right;" />
        
        <h3 id="nombre_formulario">Crear Producto</h3>
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

            //cuando presiona guardar
            $("#btnAltaProducto").click(function() {
                if( Producto.validarCampos()) {
                    var producto = Producto.armarObjetoProducto();
                    producto.Id = null;
                    Producto.insertProducto(producto);
                }  
            });
		});
	
	</script>	
	</body>

</html>