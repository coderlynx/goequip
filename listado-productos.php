<?php
session_start();
    
if(!(isset($_SESSION["perfil"])) || $_SESSION["perfil"] != 1)
  header("Location: index.html");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Outlet Gym</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/normalize.css" />
	<link rel="stylesheet" href="css/normalize.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/lib/modernizr-2.8.3-respond-1.4.2.min.js"></script>

	<!-- Google Font -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

	<!-- Font Awesome -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Estilos -->
	<link rel="stylesheet" href="css/panel.css">
	<link rel="stylesheet" href="css/estilos.css">

</head>
<body>
	<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<header id="header"></header>
	
	    <nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li><a href="#">Listado de productos</a></li>
		        <li><a href="crear-producto.php">Crear producto</a></li>
		      </ul>
		      <form class="navbar-form navbar-right" role="search">
		        <div class="form-group">
		          <input id="txtBusqueda" type="text" class="form-control busqueda-input" placeholder="Búsqueda">
		        </div>
		        <button id="btnBuscarProducto" class="btn btn-default busqueda-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
		      </form>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
	
	<main>
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2 style="display:inline-block; margin-right:20px; margin-bottom: 50px;">Listado de productos</h2>
					<div style="display:inline-block;">
						<select id="categoria" class="form-control" required>
							<option value="1">Waterrower</option>
							<option value="2">Kangoo Jumps</option>
							<option value="3">Equipos Cardio</option>
							<option value="4">Accesorios</option>
							<option value="5">Indumentaria</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
			    <div id="contenedor"></div>
                <input type="hidden" id="pantalla" value="pantallaProductos" />
			</div>
		</div>
	</main>
	<footer class="pie text-center">
		<p>Panel de control Outlet GYM</p>
	</footer>
	<script src="js/lib/jquery.js"></script>
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script><!-->
	<script src="js/lib/bootstrap.min.js"></script>
    <script src="js/lib/jquery.number.min.js"></script>
    <script src="js/Autenticacion.js"></script>
    <script src="js/Producto.js"></script>
	<script src="js/Carrito.js"></script>
    <script type="text/javascript">
      
         $( document ).ready(function() {
			 
			 $( "#header" ).load( "codigoComun.php #header", function() {
                Carrito.init();
                Autenticacion.init();
             }); 
			 
			Producto.init();
			
			$( "#categoria" ).change(function() {
			  Producto.getByCategoria($(this).val(),'PrecioMenor');
			});
		});
	
    </script>
</body>
</html>