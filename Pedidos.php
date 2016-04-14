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
    
    
    <div id="div_detalle_pedido">
        <h3>Nro Pedido: <span id="detalleNroPedido"></span></h3>
        <h3>Cliente: <span id="detalleCliente"></span></h3>
        <h3>Fecha: <span id="detalleFecha"></span></h3>
        <div id="div_tabla_detalle">

            <table id="tabla_pedidos_detalle">

            </table>
        </div>
        <h3>Total: $ <span id="detalleTotal"></span></h3>
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