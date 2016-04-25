<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detalle Producto</title>
    <?php include('estilos.html') ?>
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
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
					<div class="vista-grande">
						<img src="img/productos/a1home.jpg" alt="A1 Home">
					</div>
					<div class="preview">
						<a href=""><img src="img/productos/a1home.jpg" alt="A1 Home"></a>
						<a href=""><img src="img/productos/a1home.jpg" alt="A1 Home"></a>
						<a href=""><img src="img/productos/a1home.jpg" alt="A1 Home"></a>
						<a href=""><img src="img/productos/a1home.jpg" alt="A1 Home"></a>
					</div>
					<div class="video-producto">

					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 detalle-producto">
					<div class="divProducto" id="">
<!--					    <input id="id" type="hidden" val="" />-->
						<h3 id="modelo" class="modelo">Waterrower A1 Home</h3>
						<p id="precio" class="precio">$ 31.900</p>
						<p id="descripcion" class="descripcion">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. </p>
						<p>Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. </p><p>Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. </p>

						<div id="color" class="color">
							<h4>Colores:</h4>
							<a href=""><div class="circle color-blanco"></div></a>
							<a href=""><div class="circle color-celeste"></div></a>
							<a href=""><div class="circle color-negro"></div></a>
						</div>

						<div id="talle" class="talle">
							<h4>Talla:</h4>
							<a href="">L</a>
							<a href="">M</a>
							<a href="">S</a>
							<a href="">XS</a>
						</div>

						<div class="cantidad-select">
							<h4>Cantidad:</h4>
							<div class="btn-group">
                              <select id="cantidad" class="btn btn-default dropdown-toggle" aria-haspopup="true" aria-expanded="false">
                              </select>
<!--
							  <button id="stock" type="button" class="btn btn-default dropdown-toggle stock" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    1 <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu pull-right">
							    <li><a href="">2</a></li>
							    <li><a href="">3</a></li>
							    <li><a href="">4</a></li>
							  </ul>
-->
							</div>
						</div>

						<div class="disponibilidad">
							<h4>Disponibilidad:</h4>
							<p class="disponibilidad-ok">En stock</p>
							<p class="disponibilidad-out">Sin stock</p>
						</div>

                        <input type="button" id="" class="btn btn-default btn-custom btnAgregarACarrito" value="Agregar al carrito"/>
<!--<a class="btn btn-default btn-custom btn-agregar" href="#" role="button">Agregar al carrito</a>-->
						
					</div>
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
                var prod = Producto.getById(id);
                Producto.completarDetalle(prod);
               
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

        
	
	</script>	
</body>
</html>