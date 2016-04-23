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
            <input id="id" type="hidden" value="" />
            <input id="modelo" type="text" placeholder="Modelo" value="" />
            <input id="descripcion" type="text" placeholder="Descripcion" value="" />
            <select id="talle">
                <option value="1">L</option>
                <option value="2">M</option>
                <option value="3">S</option>
                <option value="4">XS</option>
            </select>
<!--            <input id="talle" type="text" placeholder="Talle" value="" />-->
           <select id="color">
                <option value="1">Blanco</option>
                <option value="2">Azul</option>
                <option value="3">Negro</option>
            </select>
<!--            <input id="color" type="text" placeholder="Color" value="" />-->
            <input id="stock" type="text" placeholder="Stock" value="" />
            <input id="precio" type="text" placeholder="Precio" value="" />
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
                    //producto.Id = null;
                    Producto.insert(producto);
                }  
            });
            
            var id = getUrlParameter('id');
            
            if(id) {
                Producto.getById(id);
                $("#btnAltaProducto").attr('value','Editar');
            }

		});
        
                    
            
            
            
            function getUrlParameter(sParam) {
                var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : sParameterName[1];
                    }
                }
            };
        
        
	
	</script>	
	</body>

</html>