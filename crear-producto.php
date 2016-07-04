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
	<link rel="stylesheet" href="css/jquery.filer.css">
	<link rel="stylesheet" href="css/themes/jquery.filer-dragdropbox-theme.css">
	<script src="js/lib/modernizr-2.8.3-respond-1.4.2.min.js"></script>

	<!-- Google Font -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

	<!-- Font Awesome -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Estilos -->
	<link rel="stylesheet" href="css/panel.css">

</head>
<body>
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
			                
						  </div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <p>Elija los colores disponibles:</p>
						    <div id="colores" class="grupo_chk">
			                </div>

						  </div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<label>Stock:</label>
							<input id="stock" type="number" class="form-control" placeholder="Stock" value="" required />

						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
						  <div class="form-group">
						    <label class="sr-only" for="precio">Precio (Pesos Argentinos)</label>
						    <div class="input-group">
						      <div class="input-group-addon">$</div>
						      <input id="precio" type="number" step="any" class="form-control" id="precio" placeholder="Precio (Pesos Argentinos)" required>
						    </div>
						  </div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
                            <div id="uploader-container" class="form-group">
                                <label>Imágenes:</label>
                                <p class="help-block">Formatos de imágnes soportados: .jpeg, .jpg, .png, .gif</p>
                                <p class="help-block">Tamaño: se recomienda utilizar imágenes de 450 x 350 (pixels)</p>
                                <input type="file" name="files[]" id="filer_input" multiple="multiple">
                                <div id="box-messages"></div>
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
	<script src="js/lib/bootstrap.min.js"></script>
    <script src="js/lib/jquery.filer.min.js"></script>
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
                if (Producto.validarCampos()) {
                    var producto = Producto.armarObjetoProducto();
                    
                    // OJO, NO cambiar el método nativo de JS por JQuery
                    var form = document.getElementById("agregarProducto");
                    
                    Producto.insert(producto, form);
                }  
            });
            
            var id = getUrlParameter('id');
            
            if (id) {
                
                var prod = Producto.getById(id);
                if (prod.id == 0) return;
                
                Producto.completarInputs(prod);
                
                //talles
                for (var i = 0; i < prod.talles.length; i++) {
                    Producto.tildarCheckbox(prod.talles[i], 'talles');
                }
                //colores
                for (var i = 0; i < prod.colores.length; i++) {
                    Producto.tildarCheckbox(prod.colores[i], 'colores');
                }
                // Imágenes
                if (prod.fotos[0].id !== null) {
                    
                    var files = [];
                    
                    for (var i = prod.fotos.length - 1; i >= 0; i--) {

                        if (!prod.fotos[i].ruta.includes("thumb")) {
                            var obj = {};
                            obj.id = prod.fotos[i].id;
                            obj.name = prod.fotos[i].nombre;
                            obj.size = prod.fotos[i].size;
                            obj.type = prod.fotos[i].tipo;
                            obj.file = prod.fotos[i].ruta;
                            obj.thumb = prod.fotos[i].rutaThumbnail;
                            
                            files.push(obj);
                        }
                    }
                    
                }
                
                $("#btnAltaProducto").attr('value','Editar');
                $("#btnAltaProducto").html("Editar");
                
            }
            
            cargarUploader(files);
        });
        function cargarUploader(files) {

            $('#filer_input').filer({
                extensions: ['jpg', 'jpeg', 'png', 'gif'],
                changeInput: true,
                showThumbs: true,
                addMore: true,
                files: files,
                onSelect: function(file, a, b, c, d, inputEl) {
                    
                    var filerKit = inputEl.prop("jFiler");
                    var _URL = window.URL || window.webkitURL;
                    var file, img, width = 450, height = 350, 
                        id = filerKit.current_file.id;
                
                    if (file) {
                        console.log(id);
                        img = new Image();
                        img.onload = function() {
                            if (this.width < width || this.height < height) {
                                Producto.mostrarMensaje("danger", "La imagen debe " +
                                                        "ser de al menos " + width + " x " + 
                                                        height + " pixels", "", 5000);
                                
                                window.setTimeout(function () {
                                    filerKit.remove(id)
                                }, 3000);
                            }
                        };
                        img.onerror = function() {
                            console.log( "not a valid file: " + file.type);
                        };
                        img.src = _URL.createObjectURL(file);
                    }    
                },
                onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, 
                                   inputEl) {
                    
                    var filerKit = inputEl.prop("jFiler");
                    var file_id = filerKit.files_list[id];

                    if (file_id.file.id) {
                        Producto.eliminarImagen(file_id.file.id, file_id.file.file,
                                                file_id.file.thumb);
                    }
                }
            });
        }
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
        }
	</script>	
</body>
</html>