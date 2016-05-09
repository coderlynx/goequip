var Pedido = {
    init: function() {
        var _this = this;
        
        $('#div_detalle_pedido').hide();
        
        $.ajax({
              async:false,    
              cache:false,   
              type: 'GET', 
              data: {G:true },
              url: "php/controllerPedido.php",
              success:  function(respuestaJson){
                var rta = JSON.parse(respuestaJson);
                Pedido.dibujarTabla(rta, $('#div_tabla'))
              },
              error:function(objXMLHttpRequest){
				  var e = objXMLHttpRequest;
                   console.log('Error al ejecutar la petición por:' + e);
              }
        });
        
      
    },
    insertPedido: function (nroPedido,formaDePago,status) {
        var _this = this;

        var pedido = {};
        pedido.nroPedido = nroPedido;
        pedido.formaDePago = formaDePago;
        pedido.estadoDePago = status;
        
        //Parseo a JSON el obj Producto
        var jsonPedido = JSON.stringify(pedido);

        // guardo el producto
        $.post('php/controllerPedido.php', {pedido:jsonPedido }, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta) {
                window.location.href = "CompraFinalizada.php?nroPedido=" + rta;
                //alert("El nro de pedido es: " + rta);
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        );
		
    },
    dibujarTabla: function (resultado, div_tabla) {
        var _this = this;
        //$("#tabla_pedidos").empty();
        $('#div_tabla_Detalle').hide();
        $("#search").show();
       

        var divGrilla = div_tabla;
        var pedidos = resultado;
        var columnas = [];
        var nombre = "";
        columnas.push(new Columna("Nro. Pedido", {generar: function (un_pedido) { return un_pedido.nroPedido; } }));
        columnas.push(new Columna("Cliente", { generar: function (un_pedido) { return un_pedido.idCliente } }));  
        columnas.push(new Columna("Total", { generar: function (un_pedido) { return '$ ' + un_pedido.total } }));  
        columnas.push(new Columna("Cantidad", { generar: function (un_pedido) { return un_pedido.cantidad } }));  
        columnas.push(new Columna("Pago", { generar: function (un_pedido) { return un_pedido.formaDePago } }));  
        columnas.push(new Columna("Envio", { generar: function (un_pedido) { return un_pedido.formaDeEnvio } }));  
        columnas.push(new Columna("Estado", { generar: function (un_pedido) { return un_pedido.estadoDePago } }));  
        columnas.push(new Columna("Fecha", { generar: function (un_pedido) { return un_pedido.fecha } }));  
        columnas.push(new Columna('Detalle', {
            generar: function (un_pedido) {
                var btn_accion = $('<a>');
                var img = $('<img>');
                img.attr('src', 'css/img/lupa.png');
                img.attr('width', '15px');
                img.attr('height', '15px');
                btn_accion.append(img);
                btn_accion.click(function () {
                    $('#div_tabla_Detalle').show();
                    _this.getPedido(un_pedido.id);
                });
                return btn_accion;
            }
        }));

        _this.GrillaResumen = new Grilla(columnas);
        _this.GrillaResumen.SetOnRowClickEventHandler(function (un_pedido) {
        });
        _this.GrillaResumen.CargarObjetos(pedidos);
        _this.GrillaResumen.DibujarEn(divGrilla);
        
        //buscador de tabla
        var options = {
            valueNames: ['Cliente', 'Pago', 'Envío', 'Estado']
        };
        var featureList = new List('div_tabla', options);

    },
    getPedido: function(id) {
         $.ajax({
              async:false,    
              cache:false,   
              type: 'GET', 
              data: {id:id },
              url: "php/controllerPedido.php",
              success:  function(respuestaJson){
                var rta = JSON.parse(respuestaJson);
                Pedido.dibujarTablaDetalle(rta, $('#div_tabla_detalle'))
              },
              error:function(objXMLHttpRequest){
                   console.log('Error al ejecutar la petición por:' + e);
              }
        });
        
    },
    dibujarTablaDetalle: function (pedido, div_tabla) {
        var _this = this;
        $("#div_tabla_detalle").empty();
        $('#div_detalle_pedido').show();
        $("#detalleNroPedido").html(pedido.nroPedido);
        $("#detalleCliente").html(pedido.idCliente);
        $("#detalleFecha").html(pedido.fecha);
        $("#detalleTotal").html(pedido.total);

        var divGrilla = div_tabla;
        //var pedidos = resultado;
        var columnas = [];
        var nombre = "";
        columnas.push(new Columna("Modelo", {generar: function (un_producto) { return un_producto.modelo; } }));
        columnas.push(new Columna("Cantidad", {generar: function (un_producto) { return un_producto.stock; } }));
        columnas.push(new Columna("Precio", {generar: function (un_producto) { return un_producto.precio; } }));
       
       
        _this.GrillaResumen = new Grilla(columnas);
        _this.GrillaResumen.SetOnRowClickEventHandler(function (un_producto) {
        });
        _this.GrillaResumen.CargarObjetos(pedido.productos);
        _this.GrillaResumen.DibujarEn(divGrilla);
        
    }
}