<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <?php include('estilos.html') ?>
</head>
<body>
    <input value="Cerrar Sesion" id="btnCerrarSesion" type="button" style="float:right; margin:5px;" />

    
    <span style="float:right; margin:5px;" id="nombreUsuario"><?php if(isset($_SESSION['nombre'])) echo $_SESSION['nombre'] ?></span>

    
   
  
  <form class="navbar-form navbar-right" role="search">
    <div class="form-group">
      <input id="txtBusqueda" type="text" class="form-control busqueda-input" placeholder="Búsqueda">
    </div>
    <button id="btnBuscarProducto" class="btn btn-default busqueda-btn"><i class="fa fa-search" aria-hidden="true"></i></button>
  </form>
   <a href="AgregarProducto.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Crear producto</a>
   
   <h3 id="">Listado de Productos</h3>
        
        <div id="contenedor"></div>
        <input type="hidden" id="pantalla" value="pantallaProductos" />

    
</body>
    <?php include('scripts.html') ?>
    <script src="js/Autenticacion.js"></script>
    <script src="js/Producto.js"></script>
    <script type="text/javascript">
      
         $( document ).ready(function() {

			Producto.init();
           
            Autenticacion.init();
            
          
            
		});
	
    </script>
</html>