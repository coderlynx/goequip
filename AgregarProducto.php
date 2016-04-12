<?php
require_once ('php/autoload.php');
session_start();
    

//if(!isset($_SESSION["nombre"]))	
  //header("Location: hoysesale");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
  
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Producto</title>
	</head>
	
	<body>
       <input value="Cerrar Sesion" id="btnCerrarSesion" type="button" style="float:right;" />
        <h3 id="">Listado de Productos</h3>
        
        <div id="contenedor"></div>


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
        
        <h1>Carrito</h1>
        <h2>Total Articulos: <span id="totalCarrito"></span></h2>
        <h2>Detalle del Carrito:</h2>
        <div id="contenedorCarro">
            
        </div>
        <div>Total: $ <span id="totalPedido"></span></div>

       
        <input type="button" value="Comprar" id="btnComprar" />
			
	
	<?php include('scripts.html') ?>
	<script src="js/Producto.js"></script>
	<script src="js/Carrito.js"></script>
	<script>
		$( document ).ready(function() {

			Producto.init();
            Carrito.init();
            
            //temporal en este lugar
            $("#btnComprar").click(function() {
                 $.ajax({
                      async:false,    
                      cache:false,   
                      type: 'POST', 
                      data: {pagar:true },
                      url: "php/controllerCarrito.php",
                      success:  function(respuestaJson){  
                          window.location.href = 'Pagar.php';
                      },
                      error:function(objXMLHttpRequest){
                           console.log('Error al ejecutar la petición por:' + e);
                      }
                    });
               
                
            });//fin btnComprar
            
            //temporal en este lugar
             $("#btnCerrarSesion").click(function() {
            
                 $.post('php/controllerCarrito.php', {sesion:false}, function(respuestaJson) {
                        var rta = JSON.parse(respuestaJson);
                        if(rta = "ok")	
                            alert("sesion destruida");

                    }).error(function(e){
                            console.log('Error al ejecutar la petición por:' + e);
                        }
                    );
            });//fin btnCerrarSesion

		});
	
	</script>	
	</body>

</html>