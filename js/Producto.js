var Producto = {
    init: function() {
        var _this = this;
        
        _this.mostrarProductos();

    },
    validarCampos: function() {
        return true;
    },
    mostrarProductos: function () {

        var _this = this;
        //Lo hice de esta forma porque necesitaba que el asincronismo sea false
		$.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET',   
			  url: "php/controllerProducto.php",
			  success:  function(productos){  
					var rta = JSON.parse(productos);
					//console.log( "Data Loaded: " + rta );
					var items = [];
					for (i=0; i < rta.length; i++) {
						_this.dibujarProductoEnPantalla($('#contenedor'), rta[i]);
					}
                    _this.bindearBotones();
			  },
			  error:function(objXMLHttpRequest){
				  var e = objXMLHttpRequest;
			  }
			});
    },
	dibujarProductoEnPantalla: function(contenedor, producto) {
        
        var div_item = $('<div>');
			div_item.attr('id',producto.id);
			//div_item.addClass('');
        
        var modelo = $("<span>");
        modelo.addClass("modelo");
        modelo.html(producto.modelo);
        
        var precio = $("<span>");
        precio.addClass("precio");
        precio.html(producto.precio);
        
        var stock = $("<span>");
        stock.addClass("stock");
        stock.html(producto.stock);
        
        var descripcion = $("<span>");
        descripcion.addClass("descripcion");
        descripcion.html(producto.descripcion);
        
        var color = $("<span>");
        color.addClass("color");
        color.html(producto.color);
        
        var talle = $("<span>");
        talle.addClass("talle");
        talle.html(producto.talle);
        
        var btnAgregar = $("<input>");
        btnAgregar.attr("type","button");
        btnAgregar.attr("value","Agregar a Carrito");
        btnAgregar.addClass("btnAgregarACarrito");
        
        var btnEditar = $("<input>");
        btnEditar.attr("type","button");
        btnEditar.attr("value","Editar");
        btnEditar.addClass("btnEditar");
        
        var btnEliminar = $("<input>");
        btnEliminar.attr("type","button");
        btnEliminar.attr("value","Eliminar");
        btnEliminar.addClass("btnEliminar");
        
        
        
        div_item.append(modelo);
        div_item.append(descripcion);
        div_item.append(precio);
        div_item.append(stock);
        div_item.append(talle);
        div_item.append(color);
        div_item.append(btnAgregar);
        div_item.append(btnEditar);
        div_item.append(btnEliminar);
        
        contenedor.append(div_item);
        
		return div_item;
			 
	}, 
    insertProducto: function (producto) {
        var _this = this;

        //Parseo a JSON el obj Producto
        var jsonProducto = JSON.stringify(producto);

        // guardo el producto
        $.post('php/controllerProducto.php', {producto:jsonProducto, AM:true }, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta == "exito") {
                alert("El producto ha sigo guardado con exito.");
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        );

        return false;

		
		
		
    },
    editProducto: function() {
        return false;
    },
	armarObjetoProducto: function() {
				
		var producto = {};
		
		producto.Modelo = $("#modelo").val();
		producto.Descripcion = $("#descripcion").val();
		producto.Talle = $("#talle").val();
		producto.Color = $("#color").val();
		producto.Stock = $("#stock").val();
		producto.Precio = $("#precio").val();
		

		return producto;
		
	},
    bindearBotones: function(){
        var _this = this;
        
        
        $(".btnEditar").click(function() {
             var idProducto = this.parentNode.id;
                $.get('php/controllerProducto.php', {id:idProducto}, function(respuestaJson) {
                    var rta = JSON.parse(respuestaJson);
                    if(rta) {
                        alert(rta.modelo);
                    } else {
                        alert("producto no encontrado");
                    }
                }).error(function(e){
                        console.log('Error al ejecutar la petición por:' + e);
                    }
                );
           
        });
        
        $(".btnEliminar").click(function() {
            var r = confirm("¿Está seguro de querer eliminar el producto " + this.parentNode.id + "?");
            
            if (r == true) {
                var idProducto = this.parentNode.id;
               // borro el producto
                $.post('php/controllerProducto.php', {id:idProducto, B:true }, function(respuestaJson) {
                    var rta = JSON.parse(respuestaJson);
                    if(rta == "exito") {
                        alert("El producto ha sigo eliminado con exito.");
                    }
                }).error(function(e){
                        console.log('Error al ejecutar la petición por:' + e);
                    }
                );

                return false;
            } 
          
        });
    }
}