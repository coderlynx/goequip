<?php
session_start();

?>
   

   <header id="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 logo">
                <h1><a href="index.html" title="Tienda"><img src="img/logo.jpg" alt="Tienda"></a></h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="user">
                   <span style="font-family: 'Roboto', sans-serif;" id="nombreUsuario"></span>
                    <!-- Single button -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu pull-right">
                        <?php
                          if(!isset($_SESSION["perfil"])) {
                            
                        ?>
                        <li><a href="login.html"><i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar sesión</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="crearcuenta.html"><i class="fa fa-user-plus" aria-hidden="true"></i> Registrarse</a></li>
                         <?php
                            } else {
                        ?>	
                        <li><a href="misDatos.php"><i class="fa fa-pencil" aria-hidden="true"></i> Mis datos</a></li>
                        <?php
							}
							
                          if(isset($_SESSION["perfil"]) &&  $_SESSION["perfil"] == 1) {
                            
                        ?>
                        <li role="separator" class="divider"></li>
                        <li><a href="listado-productos.php"><i class="fa fa-user-plus" aria-hidden="true"></i> ABM Productos</a></li>
<!--                        <li><a href="AgregarCliente.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Crear Cliente</a></li>-->
                        <li><a href="pedidos.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Pedidos</a></li>
                        <li><a href="Graficos.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Estadisticas</a></li>
                        <?php
                            }
                        ?>	

                        <li role="separator" class="divider"></li>
                        <li><a id="btnCerrarSesion" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i> Salir</a></li>
                      </ul>
                    </div>
                    <!-- <p><span class="hide-767">Bienvenido a tu Tienda de Fitness on-line</span></p><a href=""><i class="fa fa-user" aria-hidden="true"></i> <i class="fa fa-angle-down" aria-hidden="true"></i></a> -->
                </div>
                <div class="total-header">

                    <a id="btnIrACarrito" href="#">Total $ <span class="totalPedido">0</span> <i class="fa fa-shopping-cart" aria-hidden="true"></i> <span class="totalCantidad" class="badge">0</span></a>
<!--                    <a class="btn btn-default btn-custom" >Comprar</a> -->
                </div>
            </div>
        </div>
    </div>
</header>

<nav id="nav" class="navbar navbar-default">
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
        <li><a href="categoria.html?id=1">Waterrower</a></li>
        <li><a href="categoria.html?id=2">Kangoo Jumps</a></li>
        <li><a href="categoria.html?id=3">Equipos Cardio</a></li>
        <li><a href="categoria.html?id=4">Accesorios</a></li>
        <li><a href="categoria.html?id=5">Indumentaria</a></li>
      </ul>
      <form id="formBusqueda" class="navbar-form navbar-right" role="search">
        <div class="form-group">
          <input type="text" id="txtBusqueda" class="form-control busqueda-input" placeholder="Búsqueda">
        </div>
        <button type="submit" class="btn btn-default busqueda-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
      </form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



<section id="destacados">
    <div class="container-fluid destacados">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 col-md-offset-1">
                <article class="destacados-articulo">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Waterrower</h2>
                    <h3>Producto destacado</h3>
                    <div class="foto-destacado"><a href="producto.html?id=1&categoria=1"><img src="img/destacados/waterrower-home.jpg" alt="Waterrower" class="img-responsive"></a></div>
                    <p>Remo A1 Home</p>
                    <a class="btn btn-default btn-custom" href="producto.html?id=1&categoria=1" role="button">Ver producto</a>
                </article>	
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <article class="destacados-articulo">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Kangoo Jumps</h2>
                    <h3>Producto nuevo</h3>
                    <div class="foto-destacado"><a href="producto.html?id=3&categoria=2"><img src="img/destacados/botas.jpg" alt="Kangoo Jumps" class="img-responsive"></a></div>
                    <p>Botas KJ-XR3</p>
                    <a class="btn btn-default btn-custom" href="producto.html?id=3&categoria=2" role="button">Ver producto</a>
                </article>	
            </div>
            <div class="clearfix visible-xs"></div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <article class="destacados-articulo">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Equipos Cardio</h2>
                    <h3>Promo</h3>
                    <div class="foto-destacado"><a href="producto.html?id=4&categoria=3"><img src="img/destacados/waterrower-studio.jpg" alt="Waterrower" class="img-responsive"></a></div>
                    <p>Remo A1 Studio</p>
                    <a class="btn btn-default btn-custom" href="producto.html?id=4&categoria=3" role="button">Ver producto</a>
                </article>	
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <article class="destacados-articulo">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Accesorios</h2>
                    <h3>Producto destacado</h3>
                    <div class="foto-destacado"><a href="producto.html?id=5&categoria=4"><img src="img/destacados/bolso.jpg" alt="Accesorios" class="img-responsive"></a></div>
                    <p>Bolso KJBAG9</p>
                    <a class="btn btn-default btn-custom" href="producto.html?id=5&categoria=4" role="button">Ver producto</a>
                </article>	
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6">
                <article class="destacados-articulo">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Indumentaria</h2>
                    <h3>Producto destacado</h3>
                    <div class="foto-destacado"><a href="producto.html?id=6&categoria=5"><img src="img/destacados/calzas.jpg" alt="Indumentaria" class="img-responsive"></a></div>
                    <p>Calzas deportivas mujes</p>
                    <a class="btn btn-default btn-custom" href="producto.html?id=6&categoria=5" role="button">Ver producto</a>
                </article>	
            </div>
        </div>
    </div>
</section><!-- /.Fin destacados -->




<footer id="footer" class="pie">
    <div class="contenedor">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <ul>
                        <li><a href="">Aviso legal</a></li>
                        <li><a href="">Condiciones generales</a></li>
                        <li><a href="">Política de cookies</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <ul>
                        <li><a href="">Contacto</a></li>
                        <li><p>Atención al cliente: 4747-4747</p></li>
                        <li><a href="mailto:contacto@outletgym.com"><span class="vinculo-custom">contacto@outletgym.com</span></a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                    <div class="social">
                    <img src="img/titulos.gif" alt=" ">
                    <h2>Seguinos:</h2>
                    <ul class="icon_list">
                        <li><a href="#"target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"target="_blank"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
</footer>