<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <?php include('estilos.html') ?>
    <style>
        .margen {
            margin:10px;
        }
        
    </style>
</head>
<body>
    <input value="Cerrar Sesion" id="btnCerrarSesion" type="button" style="float:right; margin:5px;" />
    <a style="float:right; margin:5px;" href="Pedidos.php">Pedidos</a>
    <a style="float:right; margin:5px;" href="Graficos.php">Estadisticas</a>
    <a style="float:right; margin:5px;" href="AgregarProducto.php">Crear producto </a>
    <a style="float:right; margin:5px;" href="AgregarCliente.php">Crear Cliente </a>
    
    <span style="float:right; margin:5px;" id="nombreUsuario"><?php if(isset($_SESSION['nombre'])) echo $_SESSION['nombre'] ?></span>

    
    <!-- REGISTRAR -->
	<div id="registrar_pop_up" >
		<form>
			<h5>Registrarse</h5>
			<input type="text"  id="form_reg_usuario" placeholder="Usuario" autofocus />
			<span style="color:red" id="form_reg_error_usuario" ></span>
			<input type="text"  id="form_reg_mail" placeholder="Mail" />
			<span style="color:red" id="form_reg_error_mail" ></span>
			<input type="password"  id="form_reg_password" placeholder="Password">
			<span style="color:red" id="form_reg_error_password" ></span>
			<input type="button" id="btn_form_registrar" value="Registrarme!" >
		</form>
	</div><!--  FIN DE REGISTRAR -->
	
	 <!-- LOGIN -->
	<div id="login_pop_up" >
		<form >
			<h5 >Login</h5>
			<input type="text" id="form_log_usuario" placeholder="Usuario" autofocus/>
			<span style="color:red" id="form_log_error_usuario" ></span>
			<input type="password" id="form_log_password" placeholder="Password">
			<span style="color:red" id="form_log_error_password" ></span>
			<input type="button" id="btn_form_login" value="Login" >
			<a class="link_recuperar_clave" href="recuperar">Recuperar Contraseña</a>
		</form>
	</div><!--  FIN DE LOGIN -->
	
	<ul class="nav navbar-nav">
        <li><a href="CategoriaProducto.php?id=1">Waterrower</a></li>
        <li><a href="CategoriaProducto.php?id=2">Kangoo Jumps</a></li>
        <li><a href="CategoriaProducto.php?id=3">Equipos Cardio</a></li>
        <li><a href="CategoriaProducto.php?id=4">Accesorios</a></li>
        <li><a href="CategoriaProducto.php?id=5">Indumentaria</a></li>
      </ul>
  
  <form class="navbar-form navbar-right" role="search">
    <div class="form-group">
      <input id="txtBusqueda" type="text" class="form-control busqueda-input" placeholder="Búsqueda">
    </div>
    <button id="btnBuscarProducto" class="btn btn-default busqueda-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
  </form>
   
   
   <h3 id="">Listado de Productos</h3>
        
        <div id="contenedor"></div>

        <h1>Carrito</h1>
        <h2>Total Articulos: <span id="totalCantidad"></span></h2>
        <h2>Detalle del Carrito:</h2>
        <div id="contenedorCarro">
            
        </div>
        <div>Total: $ <span id="totalPedido"></span></div>

       
        <input type="button" value="Comprar" id="btnComprar"  />
        <span>Debes estar logueado para poder comprar</span>
       
    
    
</body>
    <?php include('scripts.html') ?>
    <script src="js/Autenticacion.js"></script>
    <script src="js/Producto.js"></script>
	<script src="js/Carrito.js"></script>
    <script type="text/javascript">
      
         $( document ).ready(function() {

			Producto.init();
            Carrito.init();
            Autenticacion.init();
            
            //temporal en este lugar
            $("#btnComprar").click(function() {
                 $.ajax({
                      async:false,    
                      cache:false,   
                      type: 'POST', 
                      data: {pagar:true },
                      url: "php/controllerCarrito.php",
                      success:  function(respuestaJson){
                        if (respuestaJson == 'ok') {
                            window.location.href = 'ConfirmacionDatos.php';
                            return;
                            }
                            alert(respuestaJson);
                      },
                      error:function(objXMLHttpRequest){
                           console.log('Error al ejecutar la petición por:' + e);
                      }
                    });
               
                
            });//fin btnComprar
            
		});
	
    </script>
</html>