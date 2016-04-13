<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes</title>
</head>
<body>
    <h1>Pedidos</h1>
    
    <div id="div_tabla">
        <input type="text" id="search" class="search" class="buscador" placeholder="Buscar"
            style="display: none;" />

        <table id="tabla_pedidos">

        </table>
    </div>
    
    
    <div id="div_tabla_Detalle" >

        <table id="tabla_pedidos_detalle">

        </table>
    </div>

    
     <?php include('scripts.html') ?>
     <script src="js/Pedido.js"></script>
     <script src="js/Grilla.js"></script>
     <script>
       $( document ).ready(function() {
            Pedido.init();


		});
    
    </script>
</body>

</html>