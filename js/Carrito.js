var Carrito = {
    init: function() {
        var _this = this;
        
            $.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET', 
              //data: {show:true},
			  url: "php/controllerCarrito.php",
			  success:  function(productos){  
                    if(productos == '') return false;
					var rta = JSON.parse(productos);
					//console.log( "Data Loaded: " + rta );
					var items = [];
					for (i=0; i < rta.length; i++) {
						_this.dibujarProductoEnCarrito($('#contenedorCarro'), rta[i]);
					}
                    _this.getTotal();
                    //_this.bindearBotones();
			  },
			  error:function(objXMLHttpRequest){
				  var e = objXMLHttpRequest;
			  }
			});
        
        $(".btnAgregarACarrito").click(function() {
            var producto = {};
            
            producto.id = $(this).parent()[0].id;
            producto.modelo = $(this).parent().find(".modelo").html();
            producto.descripcion = $(this).parent().find(".descripcion").html();
            producto.categoria = 'categoria';
            producto.precio = $(this).parent().find(".precio").html();
            producto.stock = $(this).parent().find("#cantidad option:selected").val();//La cantidad a agregar al carrito != al stock del producto en general
            producto.talle = $(this).parent().find(".talle").html();
            producto.color = $(this).parent().find(".color").html();
            
            _this.postAgregar(producto);

        });
    
    },
    dibujarProductoEnCarrito: function(contenedor, producto){
        var _this = this;
        
        if(producto.id == '') return;
        
        if(contenedor.find("#"+producto.id)[0]) {
            contenedor.find("#"+producto.id).find(".stock").html(producto.stock);
            return false;
        }
        
        var div_item = $('<div>');
			div_item.attr('id',producto.id);
			div_item.addClass('margen');
        
        var modelo = $("<span>");
        modelo.addClass("modelo");
        modelo.html(producto.modelo);
        modelo.addClass('margen');
        
        var precio = $("<span>");
        precio.addClass("precio");
        precio.html(producto.precio);
        precio.addClass('margen');
        
        var stock = $("<span>");
        stock.addClass("stock");
        stock.html(producto.stock);
        stock.addClass('margen');
        
        var btnSumarACarrito = $("<input>");
        var idBtnSumar = "btnSumarACarrito"+producto.id;
        btnSumarACarrito.attr("type","button");
        btnSumarACarrito.attr("id",idBtnSumar);
        btnSumarACarrito.attr("value","Agregar");
        btnSumarACarrito.addClass("btnSumarACarrito");
        btnSumarACarrito.addClass('margen');
        
        var btnQuitarDeCarrito = $("<input>");
        var idBtnQuitar = "btnQuitarDeCarrito"+producto.id;
        btnQuitarDeCarrito.attr("type","button");
        btnQuitarDeCarrito.attr("id",idBtnQuitar);
        btnQuitarDeCarrito.attr("value","Quitar");
        btnQuitarDeCarrito.addClass("btnQuitarDeCarrito");
        btnQuitarDeCarrito.addClass('margen');
              
        
        div_item.append(modelo);
        div_item.append(precio);
        div_item.append(stock);
        div_item.append(btnSumarACarrito);
        div_item.append(btnQuitarDeCarrito);
        
        contenedor.append(div_item);
        
        _this.bindearBotones(idBtnSumar,idBtnQuitar);
        
		return div_item;
			 
        
    },
    bindearBotones: function(idBtnSumar, idBtnQuitar) {
        var _this = this;
        
        $("#"+idBtnSumar).click(function() {
            var producto = {};
            producto.id = $(this).parent()[0].id;
            producto.modelo = "";
            producto.descripcion = "";
            producto.categoria = "";
            producto.precio = "";
            producto.stock = "";
            producto.talle = "";
            producto.color = "";
            
            _this.postAgregar(producto);
            
        });
        
        $("#"+idBtnQuitar).click(function() {
            var producto = {};
            producto.id = $(this).parent()[0].id;
            _this.postQuitar(producto);
            
        });
        
    },
    postAgregar: function(producto) {
        var _this = this;
        
        var jsonProducto = JSON.stringify(producto);
        
        $.ajax({
			  async:false,    
			  cache:false,   
			  type: 'POST', 
              data: {producto:jsonProducto },
			  url: "php/controllerCarrito.php",
			  success:  function(respuestaJson){  
                 var rta = JSON.parse(respuestaJson);
                    if(rta)	{
                        _this.dibujarProductoEnCarrito($('#contenedorCarro'), rta); 
                        _this.getTotal();
                    }
			  },
			  error:function(objXMLHttpRequest){
				   console.log('Error al ejecutar la petición por:' + e);
			  }
			});
                    
    },
    postQuitar: function(producto) {
        var _this = this;

        $.ajax({
			  async:false,    
			  cache:false,   
			  type: 'DELETE', 
              data: {id:producto.id },
			  url: "php/controllerCarrito.php",
			  success:  function(respuestaJson){  
                 var rta = JSON.parse(respuestaJson);
                    if(rta)	{
                        _this.dibujarProductoEnCarrito($('#contenedorCarro'), rta);                       
                    } else {
                        $('#contenedorCarro').find("#"+producto.id).remove();
                    }
                    _this.getTotal();
            
			  },
			  error:function(objXMLHttpRequest){
				   console.log('Error al ejecutar la petición por:' + e);
			  }
			});
            
    },
    getTotal: function() {
        $.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET', 
              data: {calculate:true },
			  url: "php/controllerCarrito.php",
			  success:  function(respuestaJson){  
                 var rta = JSON.parse(respuestaJson);
                    if(rta)	$("#totalPedido").html(rta);
			  },
			  error:function(objXMLHttpRequest){
				   console.log('Error al ejecutar la petición por:' + e);
			  }
			});
    }

}