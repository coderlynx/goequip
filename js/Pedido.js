var Pedido = {
    init: function() {
        var _this = this;
        
      
    },
    insertPedido: function (nroPedido,formaDePago) {
        var _this = this;

        var pedido = {};
        pedido.nroPedido = nroPedido;
        pedido.formaDePago = formaDePago;
        
        //Parseo a JSON el obj Producto
        var jsonPedido = JSON.stringify(pedido);

        // guardo el producto
        $.post('php/controllerPedido.php', {pedido:jsonPedido }, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta) {
                alert("El nro de pedido es: " + rta);
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        );
		
    }
}