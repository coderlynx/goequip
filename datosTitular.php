<?php
session_start();

if(!isset($_SESSION["nombre"]))	{
    header("Location: index.html");
    //echo "Tenes que estar logueado";
    //exit;
    //header("Location: index.php");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
}
//echo $_SESSION["idUsuario"];

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
	<link rel="stylesheet" href="css/estilos.css">

</head>
<body>
	<!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <main>
    <div class="contenedor">
    	 <header id="header">
    		
    	</header>

	    <nav id="nav" class="navbar navbar-default">
		  
		</nav>

	<!-- /.CATEGORIA -->
	<div class="contenedor carrito">	
		<div class="container-fluid">
		<div class="row text-center">
			<h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> Datos para tu compra</h1>
			 
		</div>
		<section class="row">
			<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 col-lg-offset-1 col-md-offset-1 detalle-compra datos-compra">
				<h2><i class="fa fa-user" aria-hidden="true"></i> Datos del titular:</h2>
				<p id="mensaje_cliente" style="color:red;"></p>
				<form action="">
                    <input id="idCliente" type="hidden">
					<fieldset class="form-group">
					    <label for="nombre">Nombre</label>
					    <input type="text" class="form-control" id="nombre" placeholder="Ingrese su nombre">
					</fieldset>

					<fieldset class="form-group">
					    <label for="apellido">Apellido</label>
					    <input type="text" class="form-control" id="apellido" placeholder="Ingrese su apellido">
					</fieldset>

					<fieldset class="form-group">
					    <label for="email">Email</label>
					    <input type="email" class="form-control" id="email" placeholder="Ingrese su email">
					</fieldset>

					<fieldset class="form-group">
					    <div>
						    <label for="telefono">Teléfono</label>
						    <div class="row">
						    	<div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
						    		<input type="text" class="form-control" id="codigo" placeholder="Ej. 11">
						    	</div>
						    	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
						    		<input type="text" class="form-control" id="telefono" placeholder="Ej. 47474747">
						    	</div>
						    </div>
					    </div>
					</fieldset>

					<fieldset class="form-group">
					    <div>
						    <div class="row">
						    	<div class="col-lg-6">
						    		<fieldset class="form-group">
									    <label for="dni-tipo">DNI</label>
									    <select class="form-control" id="dni-tipo">
									      <option>-Tipo-</option>
									      <option>DNI</option>
									      <option>LC</option>
									      <option>LE</option>
									    </select>
									</fieldset>
						    	</div>
						    	<div class="col-lg-6">
						    		<label for="dni-nro">Número</label>
						    		<input type="text" class="form-control" id="dni" placeholder="Número">
						    	</div>
<!--
						    	<div class="col-lg-4">
						    		<fieldset class="form-group">
									    <label for="dni-tipo">Sexo</label>
									    <select class="form-control" id="dni-tipo">
									      <option>-Sexo-</option>
									      <option>Femenino</option>
									      <option>Masculino</option>
									    </select>
									</fieldset>
						    	</div>
-->
						    </div>
					    </div>
					</fieldset>

					<fieldset class="form-group">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="cuil">Cuit/Cuil</label>
					        <input type="text" class="form-control" id="cuil" placeholder="Ingrese su cuit/cuil" />
                        </div>
                        <div class="col-lg-6">
                            <label for="fechaNacimiento">Fecha de Nacimiento</label>
					        <input type="date" class="form-control" id="fechaNacimiento" placeholder="Fecha" />
                        </div>
                        
                    </div>
					    
					</fieldset>

<!--
					<fieldset class="form-group">
					    <div>
						    <div class="row">
						    	<div class="col-lg-12">
						    		<label for="nac-dia">Fecha de nacimiento</label>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-lg-3">
						    		<fieldset class="form-group">
									    <select class="form-control" id="nac-dia">
									      <option>-Día-</option>
									      <option>1</option>
									      <option>2</option>
									      <option>3</option>
									      <option>4</option>
									    </select>
									</fieldset>
						    	</div>
						    	<div class="col-lg-3">
						    		<fieldset class="form-group">
									    <select class="form-control" id="nac-mes">
									      <option>-Mes-</option>
									      <option>Enero</option>
									      <option>Febrero</option>
									      <option>Marzo</option>
									      <option>Abril</option>
									      <option>Mayo</option>
									      <option>Junio</option>
									      <option>Julio</option>
									      <option>Agosto</option>
									      <option>Septiembre</option>
									      <option>Octubre</option>
									      <option>Noviembre</option>
									      <option>Diciembre</option>
									    </select>
									</fieldset>
						    	</div>
						    	<div class="col-lg-3">
						    		<fieldset class="form-group">
									    <select class="form-control" id="nac-ano">
									      <option>-Año-</option>
									      <option>1</option>
									      <option>2</option>
									      <option>3</option>
									      <option>4</option>
									    </select>
									</fieldset>
						    	</div>
						    </div>
					    </div>
					</fieldset>
-->

				<hr>	
					
					<h2>Domicilio de facturación</h2>

					<fieldset class="form-group">
                        <div class="row">
                           <div class="col-lg-6">
					            <label for="provincia">Provincia</label>
					            <input type="text" class="form-control" id="provincia" placeholder="CABA, Buenos Aires, Santa Fe, etc">
                            </div>
                            <div class="col-lg-6">
					            <label for="localidad">Ciudad, barrio o localidad</label>
					            <input type="text" class="form-control" id="localidad" placeholder="Barrio, ciudad o localidad...">
                            </div>
                        </div>
					</fieldset>

					<fieldset class="form-group">
					    <div>
						    <div class="row">
						    	<div class="col-lg-4">
						    		<label for="calle">Calle</label>
						    		<input type="text" class="form-control" id="calle" placeholder="Ej. Av. Centenario">
						    	</div>
						    	<div class="col-lg-4">
						    		<label for="numero">Nro.</label>
						    		<input type="text" class="form-control" id="numero" placeholder="Ej. 1458">
						    	</div>
						    	<div class="col-lg-2">
						    		<label for="piso">Piso</label>
						    		<input type="text" class="form-control" id="piso" placeholder="Ej. 12">
						    	</div>
						    	<div class="col-lg-2">
						    		<label for="depto">Depto</label>
						    		<input type="text" class="form-control" id="depto" placeholder="Ej. D">
						    	</div>
						    </div>
					    </div>
					</fieldset>

					<fieldset class="form-group">
					    <div class="row">
						    <div class="col-lg-4">
							    <label for="cp">Código postal</label>
							    <input type="text" class="form-control" id="cp" placeholder="Ej. 1642">
							    <small><a href="http://www.correoargentino.com.ar/formularios/cpa" target="_blank">Consultar mi código postal.</a></small>
						    </div>
					    </div>
					</fieldset>

					<a class="btn btn-default btn-custom" href="#" id="btnAltaCliente" role="button">Guardar datos</a>
                    <a class="btn btn-default btn-custom" role="button" id="btnDatosPersonales">Continuar</a>
				</form>
			</div>

			<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 detalle-carrito">
				
			</div>
			<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 detalle-carrito">
				<article class="detalle-carrito-resumen">
					<h2><i class="fa fa-shopping-cart" aria-hidden="true"></i> Resúmen de tu compra:</h2>
					<div class="row">
					<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Cantidad:</span></p></div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p class="totalCantidad"> 3</p></div>

					<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Total:</span></p></div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p> $ <span class="totalPedido"></span></p></div>

					<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Envío:</span></p></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p> $ <span class="totalEnvio"></span></p></div>

					<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Total:</span></p></div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p class="precio"> $ <span class="totalPedidoFinal"></span></p></div>
					</div>
					
				</article>
			</div>
		</section>
		</div>
	</div>


    </div><!-- /.contenedor global -->
    
	<footer id="footer" class="pie">
		
	</footer>

	
	<script src="js/lib/jquery.js"></script>
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script><!-->
	<script src="js/lib/bootstrap.min.js"></script>
	<script src="js/Cliente.js"></script>
	<script src="js/Carrito.js"></script>
	<script>
		$( document ).ready(function() {
            
             //codigo repetido extraido
             $( "#header" ).load( "codigoComun.php #header", function() {
                Carrito.getTotal();
                //Autenticacion.init();
             });
            
             $( "#nav" ).load( "codigoComun.php #nav" );
             $( "#footer" ).load( "codigoComun.php #footer" );


            Cliente.getClienteByIdUsuario();
                
                $("#btnAltaCliente").click(function() {
                    if( Cliente.validarCampos()) {
                        var cliente = Cliente.armarObjetoCliente();
                        Cliente.insertCliente(cliente);
                    }
                });
                
                
                $("#btnDatosPersonales").click(function() {
                    
                    //var idCliente = 1;
                    
                    $.post('php/controllerPedido.php', {idCliente:true}, function(respuesta) {
                        //var rta = JSON.parse(respuestaJson);
                        if(respuesta == 'ok') {
                           window.location.href = "Pagar.php";
                        } else {
                            alert(respuesta);
                        }
                            
                        

                    }).error(function(e){
                            console.log('Error al ejecutar la petición por:' + e);
                        }
                    );
                    
                    

                });


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