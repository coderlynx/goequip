var Pedido = {
    init: function() {
        var _this = this;
        
        $.ajax({
              async:false,    
              cache:false,   
              type: 'GET', 
              data: {pagar:true },
              url: "php/controllerPedido.php",
              success:  function(respuestaJson){
                var rta = JSON.parse(respuestaJson);
                Pedido.dibujarTabla(rta, $('#div_tabla'), $('#tabla_pedidos'))
              },
              error:function(objXMLHttpRequest){
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
    dibujarTabla: function (resultado, div_tabla, tabla) {
        var _this = this;
        $("#tabla_pedidos").empty();
        $('#div_tabla_Detalle').hide();
        $("#search").show();
       

        var divGrilla = div_tabla;
        var pedidos = resultado;
        var columnas = [];
        var nombre = "";
        columnas.push(new Columna("nroPedido", {generar: function (un_pedido) { return un_pedido.nroPedido; } }));
        columnas.push(new Columna("idCliente", { generar: function (un_pedido) { return un_pedido.idCliente } }));  
        columnas.push(new Columna("Total", { generar: function (un_pedido) { return un_pedido.total } }));  
        columnas.push(new Columna("Forma de Pago", { generar: function (un_pedido) { return un_pedido.formaDePago } }));  
        columnas.push(new Columna("Forma de Envio", { generar: function (un_pedido) { return un_pedido.formaDeEnvio } }));  
        columnas.push(new Columna("Estao de Pago", { generar: function (un_pedido) { return un_pedido.estadoDePago } }));  
        columnas.push(new Columna("Fecha", { generar: function (un_pedido) { return un_pedido.fecha } }));  
        columnas.push(new Columna('Detalle', {
            generar: function (un_pedido) {
                var btn_accion = $('<a>');
                var img = $('<img>');
                img.attr('src', '../Imagenes/detalle.png');
                img.attr('width', '15px');
                img.attr('height', '15px');
                btn_accion.append(img);
                btn_accion.click(function () {
                    $('#div_tabla_Detalle').show();
                    _this.getPedido(un_pedido.id, $("#tabla_pedidos_detalle"));
                });
                return btn_accion;
            }
        }));

        _this.GrillaResumen = new Grilla(columnas);
        _this.GrillaResumen.SetOnRowClickEventHandler(function (un_pedido) {
        });
        _this.GrillaResumen.CargarObjetos(pedidos);
        _this.GrillaResumen.DibujarEn(divGrilla);

    },
    getPedido: function(id, tabla ) {
         $.ajax({
              async:false,    
              cache:false,   
              type: 'GET', 
              data: {id:id },
              url: "php/controllerPedido.php",
              success:  function(respuestaJson){
                var rta = JSON.parse(respuestaJson);
                Pedido.dibujarTablaDetalle(rta, $('#div_tabla_detalle'), tabla)
              },
              error:function(objXMLHttpRequest){
                   console.log('Error al ejecutar la petición por:' + e);
              }
        });
        
    },
    dibujarTablaDetalle: function (resultado, div_tabla, tabla) {
        var _this = this;
        $("#div_tabla_detalle").empty();
   

        var divGrilla = div_tabla;
        var pedidos = resultado;
        var columnas = [];
        var nombre = "";
        columnas.push(new Columna("nroPedido", {generar: function (un_pedido) { return un_pedido.nroPedido; } }));
       
       
        _this.GrillaResumen = new Grilla(columnas);
        _this.GrillaResumen.SetOnRowClickEventHandler(function (un_pedido) {
        });
        _this.GrillaResumen.CargarObjetos(pedidos);
        _this.GrillaResumen.DibujarEn(divGrilla);

    }
}