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

    
    
    	<header>
    		<div class="container-fluid">
    			<div class="row">
    				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12 logo">
    					<h1><a href="index.html" title="Outlet Gym"><img src="img/logo.jpg" alt="Outlet Gym"></a></h1>
    				</div>
    				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-right">
    				    <span style="font-family: 'Roboto', sans-serif;" id="nombreUsuario"></span>
    					<button id="btnCerrarSesion" type="button" class="btn btn-default btn-sesion">Cerrar sesión</button>
    				</div>
    			</div>
    		</div>
    	</header>
	
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
		        <li><a href="listado-productos.php">Listado de productos</a></li>
		        <li><a href="crear-producto.php">Crear producto</a></li>
		        <li><a href="#">Pedidos</a></li>
		      </ul>
		      <form class="navbar-form navbar-right" role="search">
		        <div class="form-group">
		          <input type="text" class="form-control busqueda-input" placeholder="Búsqueda">
		        </div>
		        <button type="submit" class="btn btn-default busqueda-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
		      </form>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
	
	<main>

		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h2>Listado de pedios</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 producto">
				    <div id="div_tabla">
                        <input type="text" id="search" class="search" class="buscador" placeholder="Buscar"
                            style="display: none; margin:10px;" />
                    </div>	
                  
<!--
                  <table class="table table-hover table-striped table-bordered tabla-pedidos">
					  <thead>
					    <tr>
					      <th>Nro. de pedido</th>
					      <th>Cliente</th>
					      <th>Total</th>
					      <th>Cantidad</th>
					      <th>Pago</th>
					      <th>Envío</th>
					      <th>Estado</th>
					      <th>Fecha</th>
					      <th>Detalle</th>
					    </tr>
					  </thead>
					  <tbody>
					    <tr>
					      <td>1461337510</td>
					      <td>Caino, Fernando Raul</td>
					      <td>$ 1000.5</td>
					      <td>5</td>
					      <td>Factura</td>
					      <td>Sucursal</td>
					      <td>Pendiente</td>
					      <td>2016-04-22</td>
					      <td>Ver <i class="fa fa-search" aria-hidden="true"></i></td>
					    </tr>
					    <tr>
					      <td>1461337510</td>
					      <td>Caino, Fernando Raul</td>
					      <td>$ 1000.5</td>
					      <td>5</td>
					      <td>Factura</td>
					      <td>Sucursal</td>
					      <td>Pendiente</td>
					      <td>2016-04-22</td>
					      <td>Ver <i class="fa fa-search" aria-hidden="true"></i></td>
					    </tr>
					    <tr>
					      <td>1461337510</td>
					      <td>Caino, Fernando Raul</td>
					      <td>$ 1000.5</td>
					      <td>5</td>
					      <td>Factura</td>
					      <td>Sucursal</td>
					      <td>Pendiente</td>
					      <td>2016-04-22</td>
					      <td>Ver <i class="fa fa-search" aria-hidden="true"></i></td>
					    </tr>
					  </tbody>
					</table>
-->
				</div>
			</div>
		</div>


		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h3>Detalle del pedido # <span class="p-magenta" id="detalleNroPedido"></span></h3>
					<h4>Cliente: <strong id="detalleCliente"></strong></h4>
					<p>Fecha: <span id="detalleFecha"></span></p>
					<div id="div_tabla_detalle">
                </div>
<!--
					<table class="table table-striped table-bordered tabla-detalles">
					  <thead>
					    <tr>
					      <th>Modelo</th>
					      <th>Cantidad</th>
					      <th>Precio</th>
					    </tr>
					  </thead>
					  <tbody>
					    <tr>
					      <td>Calzas deportivas mujes</td>
					      <td>1</td>
					      <td>500</td>
					    </tr>
					    <tr>
					      <td>Producto con poco stock</td>
					      <td>2</td>
					      <td>600</td>
					    </tr>
					    <tr>
					      <td></td>
					      <td><strong>TOTAL</strong></td>
					      <td>$<strong id="detalleTotal">1100</strong></td>
					    </tr>
					  </tbody>
					</table>
-->
				</div>
			</div>
		</div>

	</main>
	<footer class="pie text-center">
		<p>Panel de control Outlet GYM</p>
	</footer>
	<script src="js/lib/jquery.js"></script>
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script><!-->
	<script src="js/lib/bootstrap.min.js"></script>
	     <?php include('scripts.html') ?>
     <script src="js/Autenticacion.js"></script>
     <script src="js/Pedido.js"></script>
     <script src="js/lib/Grilla.js"></script>
     <script src="js/lib/list.js"></script>
     <script>
       $( document ).ready(function() {
            Pedido.init();
            Autenticacion.init();
           Autenticacion.bindearBtnCerrarSesion();

		});
    
    </script>
</body>
</html>