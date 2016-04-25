<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categoria Producto</title>
    <?php include('estilos.html') ?>
        <style>
        .margen {
            margin:10px;
        }
        
    </style>
</head>
<body>
  <div id="contenedorCarro">         
  </div>
  <div>Total: $ <span id="totalPedido"></span></div>
   
   <div class="contenedor contenedor-productos">	
		<section class="row  producto-rec">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
				<div class="menu-lateral">
					<h2>Waterrower</h2>
					<ul>
						<li class="activo"><a href="">A1 Home</a></li>
						<li><a href="">A1 Studio</a></li>
					</ul>
				</div>
			</div>
			<div class="row">
			<select id="ordenar">
			    <option data-orden="PrecioMenor" value="1">Menor Precio</option>
			    <option data-orden="PrecioMayor" value="2">Mayor Precio</option>
			</select>
				<div id="contenedorCategoria" class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
				
				
				</div>
			</div>
		</section>
	</div>
    
    
    
    <?php include('scripts.html') ?>
	<script src="js/Producto.js"></script>
	<script src="js/Carrito.js"></script>
	<script>
		$( document ).ready(function() {

            Carrito.init();
            
            var id = getUrlParameter('id');
            
            if(id) {
                Producto.getByCategoria(id, 'PrecioMenor');
            }
            
            $('#ordenar').change(function() {
               //var orden = $('[name="filtro"]:checked').data('filtro');
               var orden =  $('#ordenar option:selected').data('orden');
               $('#contenedorCategoria').empty();
               Producto.getByCategoria(id, orden);
                
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