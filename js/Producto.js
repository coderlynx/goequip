var modelo, descripcion, talle, color, stock, precio;

var Producto = {
    init: function() {
        var _this = this;
        
        _this.mostrarProductos();

      //cuando presiona guardar
        $("#btnAltaProducto").click(function() {
            if( _this.validarCampos()) {
                var producto = _this.armarObjetoProducto();
                _this.insertProducto(producto);
            }
          
            
        })
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
        
        var p = $("<p>");
        p.html(producto.modelo);
        
        div_item.append(p);
        
        contenedor.append(div_item);
        
		return div_item;
			 
	}, 
    insertProducto: function (producto) {
        var _this = this;

        //Parseo a JSON el obj Producto
        var jsonProducto = JSON.stringify(producto);

        // guardo el aviso
        $.post('php/controllerProducto.php', {producto:jsonProducto }, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta == "exito") {
                alert("El producto ha sigo guardado con exito.");
            }
            
           

                //console.log(rta);
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
        
       /* modelo = $("#modelo").val();
        descripcion = $("#descripcion").val();
        talle = $("#talle").val();
        color = $("#color").val();
        stock = $("#stock").val();
        precio = $("#precio").val();*/
				
		var producto = {};
		
		producto.Modelo = $("#modelo").val();
		producto.Descripcion = $("#descripcion").val();
		producto.Talle = $("#talle").val();
		producto.Color = $("#color").val();
		producto.Stock = $("#stock").val();
		producto.Precio = $("#precio").val();
		

		return producto;
		
	},
}