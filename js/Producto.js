var Producto = {
    mostrarProductos: function () {

		var json = JSON.stringify(false);
		
        var _this = this;
        //Lo hice de esta forma porque necesitaba que el asincronismo sea false
		$.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET',   
			  url: "php/listarProductos.php",
			  success:  function(productos){  
					var rta = JSON.parse(productos);
					//console.log( "Data Loaded: " + rta );
					var items = [];
					for (i=0; i < rta.length; i++) {
						_this.dibujarProductoEnPantalla($('#container'), rta[i]);
					}
			  },
			  error:function(objXMLHttpRequest){
				  var e = objXMLHttpRequest;
			  }
			});
    },
	dibujarProductoEnPantalla: function($contenedor, producto) {
		return true;
			 
	}, 
    insertProducto: function () {
        var _this = this;

		$("#agregar").click(function() {

			var producto = _this.armarProducto();
			
			var jsonProducto = JSON.stringify(producto);

			
			// guardo el aviso
			$.post('php/agregarProducto.php', {producto:jsonProducto }, function(respuestaJson) {
				var rta = JSON.parse(respuestaJson);
				if(rta == "exito") {
					alert("El producto ha sigo guardado con exito.");
				}
				_this.mostrarMensajesError(rta);
				alertify.error("complete los campos obligatorios.");
				
					//console.log(rta);
			}).error(function(e){
					console.log('Error al ejecutar la petición por:' + e);
				}
			);
			
			return false;
		});
		
		
		
    },
    editProducto: function() {
        return false;
    },
	armarObjetoProducto: function() {
		
		var nombre = $('#nombre').val();
		
		var producto = {};
		
		producto.Nombre = nombre;
		

		return producto;
		
	},
}