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
        
        var descripcion = $("<span>");
        descripcion.addClass("descripcion");
        descripcion.html(producto.descripcion);
        descripcion.addClass('margen');
        
        var color = $("<span>");
        color.addClass("color");
        color.html(producto.color);
        color.addClass('margen');
        
        var talle = $("<span>");
        talle.addClass("talle");
        talle.html(producto.talle);
        talle.addClass('margen');
        
        var btnAgregar = $("<input>");
        btnAgregar.attr("type","button");
        btnAgregar.attr("value","Agregar a Carrito");
        btnAgregar.addClass("btnAgregarACarrito");
        btnAgregar.addClass('margen');
        
        var btnVerMas = $("<input>");
        btnVerMas.attr("type","button");
        btnVerMas.attr("value","Mas informacion");
        btnVerMas.addClass("btnVerMas");
        btnVerMas.addClass('margen');
        
        var btnEditar = $("<input>");
        btnEditar.attr("type","button");
        btnEditar.attr("value","Editar");
        btnEditar.addClass("btnEditar");
        btnEditar.addClass('margen');
        
        var btnEliminar = $("<input>");
        btnEliminar.attr("type","button");
        btnEliminar.attr("value","Eliminar");
        btnEliminar.addClass("btnEliminar");
        btnEliminar.addClass('margen');
        
        
        div_item.append(modelo);
        div_item.append(descripcion);
        div_item.append(precio);
        div_item.append(stock);
        div_item.append(talle);
        div_item.append(color);
        div_item.append(btnVerMas);
        div_item.append(btnAgregar);
        div_item.append(btnEditar);
        div_item.append(btnEliminar);
        
        contenedor.append(div_item);
        
		return div_item;
			 
	}, 
    insert: function (producto) {
        var _this = this;

        //Parseo a JSON el obj Producto
        var jsonProducto = JSON.stringify(producto);

        // guardo el producto
        $.post('php/controllerProducto.php', {producto:jsonProducto}, function(respuestaJson) {
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
    getById: function(idProducto) {
        var _this = this;
        var prod = {};
         //Lo hice de esta forma porque necesitaba que el asincronismo sea false
		$.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET', 
              data: {id:idProducto},
			  url: "php/controllerProducto.php",
			  success:  function(respuestaJson){  
					var producto = JSON.parse(respuestaJson);
                    if(producto) {
                        prod = producto;
                    }
			  },
			  error:function(objXMLHttpRequest){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
        });
        
        return prod;
    },
    getByCategoria: function(idCategoria, orden) {
        var _this = this;
         //Lo hice de esta forma porque necesitaba que el asincronismo sea false
		$.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET', 
              data: {idCategoria:idCategoria, orden:orden},
			  url: "php/controllerProducto.php",
			  success:  function(respuestaJson){  
					var productos = JSON.parse(respuestaJson);
                    for (i=0; i < productos.length; i++) {
						_this.dibujarProductoEnPantalla($('#contenedorCategoria'), productos[i]);
					}
                    _this.bindearBotones();
			  },
			  error:function(objXMLHttpRequest){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
        });
        
    },
	armarObjetoProducto: function() {
				
		var producto = {};
		
        producto.Id = $("#id").val();
		producto.Modelo = $("#modelo").val();
		producto.Descripcion = $("#descripcion").val();
		producto.Categoria = $("#categoria option:selected").val();
		producto.Talle = $("#talle option:selected").val();
		producto.Color = $("#color option:selected").val();
		producto.Stock = $("#stock").val();
		producto.Precio = $("#precio").val();
		

		return producto;
		
	},
    completarInputs: function(producto) {
        $("#id").val(producto.id);
        $("#modelo").val(producto.modelo);
        $("#descripcion").val(producto.descripcion);
        $("#categoria").val(producto.categoria);
        $("#talle").val(producto.talle);
        $("#color").val(producto.color);
        $("#stock").val(producto.stock);
        $("#precio").val(producto.precio);
    },
    completarDetalle: function(producto) {
        $(".divProducto").attr('id',producto.id);
        $("#modelo").html(producto.modelo);
        $("#precio").html(producto.precio);
        $("#descripcion").html(producto.descripcion);
        
    },
    bindearBotones: function(){
        var _this = this;
        
        
        $(".btnVerMas").click(function() {
            var idProducto = this.parentNode.id;
            window.location.href = 'DetalleProducto.php?id='+idProducto;
                   
        });
        
        
        $(".btnEditar").click(function() {
            var idProducto = this.parentNode.id;
        
            $.get('php/controllerProducto.php', {id:idProducto}, function(respuestaJson) {
                var rta = JSON.parse(respuestaJson);
                if(rta) {
                    //alert(rta.modelo);
                    window.location.href = 'AgregarProducto.php?id='+rta.id;
                } else {
                    alert("Producto no encontrado");
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
                $.ajax({ 
                    type: 'DELETE',   
                    url: "php/controllerProducto.php",
                    data : {id:idProducto},
                    success:  function(respuestaJson){  
                        //var rta = JSON.parse(respuestaJson);
                        //if(rta == "exito") {
                            alert(respuestaJson);
                        //}
                    },
                    error:function(objXMLHttpRequest){
                      console.log('Error al ejecutar la petición por:' + e);
                    }
                });
            } 
          
        });
        
        $("#btnBuscarProducto").click(function(e){
														
				var texto = $("#txtBusqueda").val();

				$.get('php/controllerProducto.php', {buscar:texto },function(respuestaJson) {
					var productos = JSON.parse(respuestaJson);
                        if(productos.length > 0) {
                            $('#contenedor').empty();
                            for (i=0; i < productos.length; i++) {
                                _this.dibujarProductoEnPantalla($('#contenedor'), productos[i]);
                            }
                            _this.bindearBotones();

                        } else {
                            alert('No se encontraron resultados.');
                        }
				}).error(function(e){
					console.log('Error al ejecutar la petición.' + e);
                });
                e.preventDefault();
				});
       
    },
	bindearBuscadorProductos: function() {
		var _this = this;
			$('#txtBusqueda').keypress(function(e){
				if(e.which == 13) {
					$('#btnBuscarProducto').click();
				}
			});
		
		
	}
}