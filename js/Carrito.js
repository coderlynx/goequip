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
                
                    $("#cantidadProductosSeleccionados").html(rta.length);
					for (i=0; i < rta.length; i++) {
						_this.dibujarProductoEnCarrito($('#contenedorDetalleCarro'), rta[i]);
					}
                    _this.getTotal();
                    //_this.bindearBotones();
			  },
			  error:function(objXMLHttpRequest){
				  var e = objXMLHttpRequest;
			  }
			});
        
        $("#btnAgregarACarrito").click(function() {
            var producto = {};
            
            producto.id = $(this).parent()[0].id;
            producto.modelo = $(this).parent().find("#modelo").html();
            producto.descripcion = $(this).parent().find("#descripcion").html();
            producto.categoria = 'categoria';
            producto.precio = $(this).parent().find("#precio").html();
            producto.foto = $("#imgPrincipal").attr('src');
            
            producto.stock = 1;//default 1 cantidad
            if($(this).parent().find("#cantidad option:selected").val())
                producto.stock = $(this).parent().find("#cantidad option:selected").val();//La cantidad a agregar al carrito != al stock del producto en general
            
            producto.talle = $(this).parent().find("#talle").val();
            producto.color = $(this).parent().find("#color").val();
            
            if(producto.talle == ''){
                alert("Debe seleccionar un talle.");
                return false;
            } 
            
            if(producto.color == ''){
                alert("Debe seleccionar un color.");
                return false;
            }
               
            _this.postAgregar(producto);

        });
    
    },
    dibujarProductoEnCarrito: function(contenedor, producto){
        var _this = this;
        
        if(producto.id == '') return;
        
        //para actualizar la cantidad en el detalle del carrito
        if(contenedor.find("#"+producto.id)[0]) {
            contenedor.find("#"+producto.id).find(".cantidad").html(producto.stock);
            return false;
        }
                    
        var article = $("<article>");
        article.attr('id',producto.id);
        
        var div_imagen = $('<div>');
			div_imagen.addClass('info-detalle-compra');         
        
        var img = $('<img>');
			img.attr('src',producto.fotos);  
        
        
        var div_item = $('<div>');
			
			div_item.addClass('info-detalle-compra');
        
        var modelo = $("<h3>");
        modelo.addClass("modelo");
        modelo.html(producto.modelo);
           
        var precio = $("<span>");
        precio.addClass("detalle-items");
        precio.addClass("precio");
        precio.html(' $ ' + producto.precio);

        modelo.append(precio);
        
        var p_color = $('<p>');
        var color = $("<span>");
        color.addClass("color");
        color.html(producto.color);
        p_color.append("<span class='detalle-items'>Color:</span> " + color.html());
        
        var p_talla = $('<p>');
        var talla = $("<span>");
        talla.addClass("talla");
        talla.html(producto.talle);
        p_talla.append("<span class='detalle-items'>Talla:</span> " + talla.html());
        
        var p_cantidad = $('<p>');       
        var cantidad = $("<span>");
        cantidad.addClass("cantidad");
        cantidad.html(producto.stock);
        p_cantidad.append("<span class='detalle-items'>Cantidad:</span><span class='cantidad'>" + cantidad.html() + "</span>");
        
        var btnSumarACarrito = $("<input>");
        var idBtnSumar = "btnSumarACarrito"+producto.id;
        btnSumarACarrito.attr("type","button");
        btnSumarACarrito.attr("id",idBtnSumar);
        btnSumarACarrito.attr("value","Agregar");
        btnSumarACarrito.addClass("btnSumarACarrito");
        btnSumarACarrito.addClass('btn btn-default btn-custom');
        
        var btnQuitarDeCarrito = $("<input>");
        var idBtnQuitar = "btnQuitarDeCarrito"+producto.id;
        btnQuitarDeCarrito.attr("type","button");
        btnQuitarDeCarrito.attr("id",idBtnQuitar);
        btnQuitarDeCarrito.attr("value","Quitar");
        btnQuitarDeCarrito.addClass("btnQuitarDeCarrito");
        btnQuitarDeCarrito.addClass('btn btn-default btn-custom');
              
        
        div_imagen.append(img);
       
        div_item.append(modelo);
        div_item.append(p_color);
        div_item.append(p_talla);
        div_item.append(p_cantidad);
        div_item.append(btnSumarACarrito);
        div_item.append(btnQuitarDeCarrito);
        
        article.append(div_imagen);
        article.append(div_item);
        
        contenedor.append(article);
        
        _this.bindearBotones(idBtnSumar,idBtnQuitar);
        
		return div_item;
			 
        
    },
    bindearBotones: function(idBtnSumar, idBtnQuitar) {
        var _this = this;
        
        $("#"+idBtnSumar).click(function() {
            var producto = {};
            producto.id = $(this).parent().parent()[0].id;
            producto.modelo = "";
            producto.descripcion = "";
            producto.categoria = "";
            producto.precio = "";
            producto.stock = "";
            producto.talle = 1;//No lo tiene en cuenta...es solo para que rompa
            producto.color = 1;//idem anterior
            producto.foto = "";
            
            _this.postAgregar(producto);
            
        });
        
        $("#"+idBtnQuitar).click(function() {
            var producto = {};
            producto.id = $(this).parent().parent()[0].id;
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
                        _this.dibujarProductoEnCarrito($('#contenedorDetalleCarro'), rta);
                        
                        _this.getTotal();
                        alert("Producto agregado");
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
                        _this.dibujarProductoEnCarrito($('#contenedorDetalleCarro'), rta);                       
                    } else {
                        $('#contenedorDetalleCarro').find("#"+producto.id).remove();
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
                    if (rta) {
                        $(".totalPedido").html(rta.monto);
                        $(".totalCantidad").html(rta.cantidad);
                    }
			  },
			  error:function(objXMLHttpRequest){
				   console.log('Error al ejecutar la petición por:' + e);
			  }
			});
    }

}