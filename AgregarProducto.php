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
		 <!-- Estilos -->
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
       <input value="Cerrar Sesion" id="btnCerrarSesion" type="button" style="float:right;" />
        
        <h3 id="nombre_formulario">Crear Producto</h3>
        <form id="agregarProducto">
            <input id="id" type="hidden" value="" />
            <input id="modelo" type="text" placeholder="Modelo" value="" /><br><br>
            <textarea id="descripcion" placeholder="Descripcion" value="" ></textarea><br><br>
<!--            <input id="descripcion" type="text" placeholder="Descripcion" value="" />-->
            <select id="categoria">
                <option value="1">Waterrower</option>
                <option value="2">Kangoo Jumps</option>
                <option value="3">Equipos Cardio</option>
                <option value="4">Accesorios</option>
                <option value="5">Indumentaria</option>
            </select>
            <br><br>
            <p>Elija los talles disponibles: </p>
            <div id="talles" class="grupo_chk">
			</div>
           <p>Elija los colores disponibles: </p>
            <div id="colores" class="grupo_chk">
			</div>
           
            <input id="stock" type="text" placeholder="Stock" value="" /><br><br>
            <input id="precio" type="text" placeholder="Precio" value="" /><br><br>
            <label for="fotos">Seleccione (una o más imágnees, al mismo tiempo): </label>
            <p>Nota: Formatos de imágnes soportados: .jpeg, .jpg, .png, .gif</p>
            <input type="file" id="fotos" name="files[]" multiple />
            <p id="cantArchivos" style="margin-top: 10px"></p>
            <div id="visor"></div><br>
            <input type="button" id="btnAltaProducto" value="Guardar" />
        </form>

	<?php include('scripts.html') ?>
	<script src="js/Producto.js"></script>
	<script src="js/ConstructorDeCheck.js"></script>
	<script>
		$(document).ready(function() { 
            ConstructorDeCheck.armarCheckBox('#talles','talles');
            ConstructorDeCheck.armarCheckBoxColores('#colores','colores');
            //cuando presiona guardar
            $("#btnAltaProducto").click(function() {
                if( Producto.validarCampos()) {
                    var producto = Producto.armarObjetoProducto();
                    //producto.Id = null;
                    // OJO, NO cambiar el método nativo de JS por JQuery
                    var form = document.querySelector("form");
                    Producto.insert(producto, form);
                }  
            });
            $("#fotos").change(previewFiles);
            
            var id = getUrlParameter('id');
            
            if(id) {
                var prod = Producto.getById(id);
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