var Producto = {
    init: function () {
        var _this = this;
        
        _this.mostrarProductos();

    },
    validarCampos: function () {
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
			div_item.addClass('col-lg-3 col-md-4 col-sm-4 col-xs-12 producto');
        
        
       
        
        var link = $("<a>");
        link.attr('href','producto.html?id=' + producto.id + '&categoria=' + producto.categoria.id);
        
        var foto = $("<img>");
        foto.attr('src', producto.fotos[0]);
        foto.attr('alt', producto.modelo);
        foto.addClass('img-responsive');
        
        var h3 = $("<h3>");
        h3.html(producto.modelo);
        
        var p = $("<p>");
        p.html('Precio: $ ' + producto.precio);
        
        
        link.append(foto);
        div_item.append(link);
        div_item.append(h3);
        div_item.append(p);
        //div_item.append(article);

        if($('#pantalla').val() == 'pantallaProductos'){
            var div_btn = $("<div>");
            
            var btnEditar = $("<button>");
            btnEditar.addClass('btn btn-info btn-editar btnEditar');
            btnEditar.html('<i class="fa fa-pencil" aria-hidden="true"></i> Editar');            
            
            var btnEliminar = $("<button>");
            btnEliminar.addClass('btn btn-danger btn-eliminar btnEliminar');
            btnEliminar.html('<i class="fa fa-trash" aria-hidden="true"></i> Eliminar');

            
            div_btn.append(btnEditar);
            div_btn.append(btnEliminar);  
            div_item.append(div_btn);  
        }
        
        contenedor.append(div_item);
                
		return div_item;
			 
	}, 
    insert: function (producto, form) {
        var _this = this;

        //Parseo a JSON el obj Producto
        var jsonProducto = JSON.stringify(producto);
        var formData = new FormData(form);
        
        // Agrego las propiedades del objeto producto al form con las imágenes
        formData.append("producto", jsonProducto);

        // guardo el producto
        $.ajax({
            url: 'php/controllerProducto.php',
            type: 'POST',
            data: formData,
            cache: false,
            mimeType:"multipart/form-data",
            contentType: false,
            processData: false,
            success: function (respuestaJson) {
                var rta = JSON.parse(respuestaJson);
                if(rta == "exito") {
                    alert("El producto ha sigo guardado con exito.");
                    $("#visor").empty();
                }
                else {
                    alert(respuestaJson);
                }
            },
            error: function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        });

        /*
        $.post('php/controllerProducto.php', {producto:jsonProducto}, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta == "exito") {
                alert("El producto ha sigo guardado con exito.");
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        );
        */

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
            
                    $('#listaProductos').empty();
                    $('#cantidadDeProductos').html(productos.length + " Productos");
                    $('#nombreCategoria').html(productos[0].categoria.descripcion);
            
                    for (i=0; i < productos.length; i++) {
						_this.dibujarProductoEnPantalla($('#contendorProductos'), productos[i]);
                        
                        //dibujo el menu del costado
                        $('#listaProductos').append("<li><a href='producto.html?id=" + productos[i].id + "&categoria=" + productos[i].categoria.id + "' >" + productos[i].modelo + "</a></li>");
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
		producto.Talle = this.recorrerCheckbox('talles');//$("#talle option:selected").val();
		producto.Color = this.recorrerCheckbox('colores');//$("#color option:selected").val();
		producto.Stock = $("#stock").val();
		producto.Precio = $("#precio").val();
		

		return producto;
		
	},
    completarInputs: function(producto) {
        $("#id").val(producto.id);
        $("#modelo").val(producto.modelo);
        $("#descripcion").val(producto.descripcion);
        $("#categoria").val(producto.categoria.id);
        $("#stock").val(producto.stock);
        $("#precio").val(producto.precio);
    },
    completarDetalle: function(producto) {
        $(".divProducto").attr('id',producto.id);
        $("#modelo").html(producto.modelo);
        $("#precio").html(producto.precio);
        $("#descripcion").html(producto.descripcion);
        if (producto.fotos[0]) {
            $("#imgPrincipal").attr('src',producto.fotos[0]);
        } else {
            $("#imgPrincipal").attr('src',"img/productos/sin_imagen.png");
        }

        for (i=1; i < producto.fotos.length;i++){
            var link = $("<a>");
            var ruta = producto.fotos[i];
            var filename = producto.fotos[i].replace(/^.*[\\\/]/, '');
            link.attr({href: ruta, "data-lightbox": filename,
                      "data-title": filename.substring(1,filename.lastIndexOf('.'))});
            
            var img = $("<img>");
            img.attr('src',producto.fotos[i]);
            img.attr('alt',producto.modelo);
            
            link.append(img);          
            $(".preview").append(link);
        }
        
        //dibujo los colores
        for (i=0; i < producto.colores.length;i++){
            var div = $("<div>");
            div.addClass('circle');
            div.attr('style','background:' + producto.colores[i].descripcion);
            div.attr('data-color',producto.colores[i].id);

            $('.colores').append(div);
        }

        
        //dibujo los talles
        for (i=0; i < producto.talles.length;i++){
            var span = $("<span>");
            span.addClass('talle');
            span.attr('data-talle',producto.talles[i].id);
            span.html(producto.talles[i].descripcion);

            $('.talla').append(span);
        }
        
            
        //para dibujar si tiene o no stock
        if(producto.stock > 0) {
            for (i=1; i < producto.stock; i++) {
                var option = $('<option>');
                option.attr('value',i);
                option.html(i);
                $('#cantidad').append(option);
                $('#msgStock').html('Con Stock');
                $('#msgStock').addClass('disponibilidad-ok');
            }
        } else {
            $('#cantidad').hide();
            $('#btnAgregarACarrito').hide();
            $('#msgStock').html('Sin Stock');
            $('#msgStock').addClass('disponibilidad-out');
            
        }
        
    },
    bindearBotones: function(){
        var _this = this;
        
        
        $(".btnVerMas").click(function() {
            var idProducto = this.parentNode.id;
            window.location.href = 'DetalleProducto.php?id='+idProducto;
                   
        });
        
        
        $(".btnEditar").click(function() {
            var idProducto = this.parentNode.parentNode.id;
        
            $.get('php/controllerProducto.php', {id:idProducto}, function(respuestaJson) {
                var rta = JSON.parse(respuestaJson);
                if(rta) {
                    //alert(rta.modelo);
                    window.location.href = 'crear-producto.php?id='+rta.id;
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
                var idProducto = this.parentNode.parentNode.id;
               // borro el producto
                $.ajax({ 
                    type: 'DELETE',   
                    url: "php/controllerProducto.php",
                    data : {id:idProducto},
                    success:  function(respuestaJson){  
                        //var rta = JSON.parse(respuestaJson);
                        //if(rta == "exito") {
                            alert(respuestaJson);
                            location.reload();
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
		
		
	},
	recorrerCheckbox: function(name) {
		
		var checkboxValues = new Array();
		//recorremos todos los checkbox seleccionados con .each
		$('input[name="'+name+'[]"]:checked').each(function() {
			//$(this).val() es el valor del checkbox correspondiente
			checkboxValues.push($(this).val());
		});
		
		return checkboxValues;

	},
	tildarCheckbox: function(elemento, name) {
		
		$('input[name="'+name+'[]"]').each(function() {
			//$(this).val() es el valor del checkbox correspondiente
			if($(this).val() == elemento.id ) $(this)[0].checked =true;
		});
		
	},
}