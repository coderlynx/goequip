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
            <div id="talles" class="grupo_chk">
			</div>
          
            <div id="colores" class="grupo_chk">
			</div>
           
<!--
            <select id="talle">
                <option value="1">L</option>
                <option value="2">M</option>
                <option value="3">S</option>
                <option value="4">XS</option>
            </select>
            <input id="talle" type="text" placeholder="Talle" value="" />
           <select id="color">
                <option value="1">Blanco</option>
                <option value="2">Azul</option>
                <option value="3">Negro</option>
            </select>
-->
<!--            <input id="color" type="text" placeholder="Color" value="" />-->
            <input id="stock" type="text" placeholder="Stock" value="" /><br><br>
            <input id="precio" type="text" placeholder="Precio" value="" /><br><br>
            <label for="fotos">Seleccione (una o más imágnees): </label>
            <input type="file" id="fotos" name="files[]" multiple onchange="PreviewImage();"/>
            <p>Nota: Formatos de imágnes soportados: .jpeg, .jpg, .png, .gif</p>
            <div id="visor"></div><br>
            <input type="button" id="btnAltaProducto" value="Guardar" />
        </form>

	<?php include('scripts.html') ?>
	<script src="js/Producto.js"></script>
	<script src="js/ConstructorDeCheck.js"></script>
	<script>
		$( document ).ready(function() { 
            //ConstructorDeCheck.armarCheckBox('#talles','talles');
            //ConstructorDeCheck.armarCheckBox('#colores','colores');
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
        var PreviewImage = (function(){
            var counter = 0;
            var visor = $("#visor");
            var fotos = document.getElementById("fotos");
            
            return function (){
                var oFReader = new FileReader();
                var idImag = "uploadPreview" + counter;
                oFReader.readAsDataURL(fotos.files[0]);

                var img = "<img id='" + idImag + "' style='width: 100px; height: 100px; border: 1px solid black; margin-right: 5px;' />";
                visor.append(img)

                oFReader.onload = function (oFREvent) {
                    document.getElementById(idImag).src = oFREvent.target.result;
                };
                counter++;
            };
        })();
	</script>	
	</body>

</html>