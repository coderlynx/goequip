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
		        <li><a href="#">Crear producto</a></li>
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
					<h2>Crear producto</h2>
				</div>
			</div>
				<form id="agregarProducto" class="producto-crear">
				    <input id="id" type="hidden" value="" />
					<div class="row">
						<div class="col-lg-6">
							<label>Categoría:</label>
                            <select id="categoria" class="form-control" required>
                                <option value="1">Waterrower</option>
                                <option value="2">Kangoo Jumps</option>
                                <option value="3">Equipos Cardio</option>
                                <option value="4">Accesorios</option>
                                <option value="5">Indumentaria</option>
                            </select>
<!--
							<select class="form-control">
							  <option>Accesorios</option>
							  <option>Equipos Cardio</option>
							  <option>Indumentaria</option>
							  <option>Kangoo Jumps</option>
							  <option>Waterrower</option>
							</select>
-->
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <label for="descripcion">Descripción:</label>
						    <textarea id="descripcion" class="form-control" rows="3" required></textarea>
						  </div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <label for="modelo">Modelo:</label>
						    <input id="modelo" type="text" class="form-control" id="modelo" placeholder="Modelo" required>
						  </div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <p>Elija los talles disponibles:</p>
						     <div id="talles" class="grupo_chk">
			                </div>
			                
<!--
						    <label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox1" value="option1"> XS
							</label>
							<label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox2" value="option2"> S
							</label>
							<label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox3" value="option3"> M
							</label>
							<label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox3" value="option3"> L
							</label>
-->
						  </div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <p>Elija los colores disponibles:</p>
						    <div id="colores" class="grupo_chk">
			                </div>
                        
<!--
						    <label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox1" value="option1">
							  <div class="circle color-blanco">Blanco</div>
							</label>
							<label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox1" value="option1">
							  <div class="circle color-gris">Gris</div>
							</label>
							<label class="checkbox-inline">
							  <input type="checkbox" id="inlineCheckbox1" value="option1">
							  <div class="circle color-negro">Negro</div>
							</label>
-->
						  </div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<label>Stock:</label>
							<input id="stock" type="number" class="form-control" placeholder="Stock" value="" required />
<!--
							<select class="form-control">
							  <option>En Stock</option>
							  <option>Sin Stock</option>
							</select>
-->
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <label class="sr-only" for="precio">Precio (Pesos Argentinos)</label>
						    <div class="input-group">
						      <div class="input-group-addon">$</div>
						      <input id="precio" type="number" class="form-control" id="precio" placeholder="Precio (Pesos Argentinos)" required>
						    </div>
						  </div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
							    <label for="fotos">Seleccione (una o más imágenes, al mismo tiempo):</label>
							    <input type="file" id="fotos" name="files[]" multiple >
							    <p class="help-block">Formato: tipos de imágnes soportados .jpeg, .jpg, .png, .gif</p>
							    <p class="help-block">Tamaño: se recomienda utilizar imágenes de 350 x 300 (pixels)</p>
							    <p id="cantArchivos" style="margin-top: 10px"></p>
                                <div id="visor"></div><br>
                                <div id="visorEdicion"></div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<button id="btnAltaProducto" type="submit" class="btn btn-custom">Guardar</button>
							<div id="mensaje"></div>
						</div>
					</div>
				</form>
		</div>
	</main>
	<footer class="pie text-center">
		<p>Panel de control Outlet GYM</p>
	</footer>
	<script src="js/lib/jquery.js"></script>
	<!--<script src="http://code.jquery.com/jquery-latest.js"></script><!-->
	<script src="js/lib/bootstrap.min.js"></script>
    <script src="js/Autenticacion.js"></script>
	<script src="js/Producto.js"></script>
	<script src="js/ConstructorDeCheck.js"></script>
	<script>
		$(document).ready(function() { 
            ConstructorDeCheck.armarCheckBox('#talles','talles');
            ConstructorDeCheck.armarCheckBoxColores('#colores','colores');
            
            Autenticacion.init();
            Autenticacion.bindearBtnCerrarSesion();
            //cuando presiona guardar
            $("#agregarProducto").submit(function(e) {
                e.preventDefault();
                if( Producto.validarCampos()) {
                    var producto = Producto.armarObjetoProducto();
                    //producto.Id = null;
                    // OJO, NO cambiar el método nativo de JS por JQuery
                    var form = document.getElementById("agregarProducto");
                    Producto.insert(producto, form);
                }  
            });
            $("#fotos").change(previewFiles);
            
            var id = getUrlParameter('id');
            
            if(id) {
                var prod = Producto.getById(id);
                if(prod.id == 0)
                    return;
                
                Producto.completarInputs(prod);
                //talles
                for(i = 0; i < prod.talles.length; i++) {
                    Producto.tildarCheckbox(prod.talles[i], 'talles');
                }
                //colores
                for(i = 0; i < prod.colores.length; i++) {
                    Producto.tildarCheckbox(prod.colores[i], 'colores');
                }
                $("#btnAltaProducto").attr('value','Editar');
                $("#btnAltaProducto").html("Editar");
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
        
        // Previsualizador de múltiples imágenes
        function previewFiles() {
            var preview = $('#visor');
            var files   = document.querySelector('input[type=file]').files;
            var cantFotos = files.length;
            $("#cantArchivos").text("Cantidad de archivos cargardos: " + cantFotos);
            preview.empty();
            
            function readAndPreview(file) {
                // Make sure `file.name` matches our extensions criteria
                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
                    var cssString = "border: 1px solid black; margin-right: 10px;";
                    var reader = new FileReader();

                  reader.addEventListener("load", function () {
                    var image = new Image();
                    image.width = 100;
                    image.height = 100;
                    image.style.cssText = cssString;
                    image.title = file.name;
                    image.src = this.result;
                    preview.append(image);
                  }, false);

                  reader.readAsDataURL(file);
                }
            }

            if (files) {
                [].forEach.call(files, readAndPreview);
            }
        }
	</script>	
</body>
</html>