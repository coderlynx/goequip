var Producto = {
    init: function () {
        var _this = this;
        
        _this.mostrarProductos();
    },
    self: function() {
        return this;  
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
                for (var i=0; i < rta.length; i++) {
                    _this.dibujarProductoEnPantalla($('#contenedor'), rta[i]);
                }
                _this.bindearBotones();
            },
            error:function(objXMLHttpRequest){
              var e = objXMLHttpRequest;
            }
        });
    },
	dibujarProductoEnPantalla: function (contenedor, producto) {       
        var div_item = $('<div>');
        div_item.attr('id',producto.id);
		div_item.attr('style','height:360px;');
        div_item.addClass('col-lg-3 col-md-4 col-sm-4 col-xs-12 producto');
        
        var link = $("<a>");
        link.attr('href','producto.html?id=' + producto.id + '&categoria=' + producto.categoria.id);
        
        var foto = $("<img>");

		if(producto.fotos.length == 0) {
			foto.attr('src', "img/na.jpg");
		} else if (producto.fotos[0].ruta != null) {
            foto.attr('src', producto.fotos[0].ruta.replace("../", ""));
            foto.attr('alt', producto.modelo);
        } else {
            foto.attr('src', "img/na.jpg");
        }

        foto.css({margin: '0 5px'})
        foto.addClass('img-responsive');
        link.append(foto);
        
        var h3 = $("<h3>");
        h3.html(producto.modelo);
		h3.attr('style','position:absolute; bottom:70px;');
        
        var p = $("<p>");
        p.html('Precio: $ ' + producto.precio);
		p.attr('style','position:absolute; bottom:40px;');
        
        div_item.append(link);
        div_item.append(h3);
        div_item.append(p);

        if ($('#pantalla').val() == 'pantallaProductos') {
            var div_btn = $("<div>");
			div_btn.attr('style','position:absolute; bottom:0;');
            
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
    detectarSO: function() {
        var appVersion = navigator.appVersion;
        var OSName = "";
        if (appVersion.indexOf("Win") != -1) {
            OSName = "Windows";
        } else if (appVersion.indexOf("Mac") != -1) {
            OSName = "MacOS";
        } else if (appVersion.indexOf("X11") != -1) {
            OSName = "UNIX";
        } else if (appVersion.indexOf("Linux") != -1) {
            OSName = "Linux";
        } else {
            OSName = "Unknown OS";
        }
        return OSName.toUpperCase();
    },
    insert: function (producto, form) {
        var _this = this;
        //Parseo a JSON el obj Producto
        var jsonProducto = JSON.stringify(producto);
        var formData = new FormData(form);
        
        // Agrego las propiedades del objeto producto al form con las imágenes
        formData.append("producto", jsonProducto);

        $.ajax({
            url: 'php/controllerProducto.php',
            type: 'POST',
            data: formData,
            cache: false,
            mimeType: "multipart/form-data",
            contentType: false,
            processData: false,
            success: function (data, textStatus) {
                if (data) {
                    console.log(data);
                    $('#categoria').val("");
                    $('#descripcion').val("");
                    $('#modelo').val("");
                    $('.check').prop("checked", false);
                    $('#stock').val("");
                    $('#precio').val("");
                    $('#filer_input').prop("jFiler").reset();
                    
                    Producto.self().mostrarMensaje("success", "Producto cargado exitosamente", 
                                                  "listado-productos.php");
                } else {
                    console.log(textStatus);
                }
            },
            error: function(e){
                console.log('Error al ejecutar la petición por:' + e);
            }
        });

        return false;
    },
    getById: function(idProducto) {
        var _this = this;
        var prod = {};
        
		$.ajax({
			  async: false,    
			  cache: false,   
			  type: 'GET', 
              data: {id: idProducto},
			  url: "php/controllerProducto.php",
			  success:  function(respuestaJson) {  
					var producto = JSON.parse(respuestaJson);
                    if(producto === "error") {
                        alert("Producto no encontrado");
                        prod = { id:0};
                    } else {
                        prod = producto;
                    }
			  },
			  error:function(e){
				  console.log('Error al ejecutar la petición por:' + e);
			  }
        });
        
        return prod;
    },
    getByCategoria: function(idCategoria, orden) {
        var _this = this;
        
		$.ajax({
			  async:false,    
			  cache:false,   
			  type: 'GET', 
              data: {idCategoria: idCategoria, orden: orden},
			  url: "php/controllerProducto.php",
			  success:  function(respuestaJson){  
                    var productos = JSON.parse(respuestaJson);
                    var length = productos.length;

                    $('#listaProductos').empty();
					$('#contenedor').empty();
                    $('#cantidadDeProductos').html(length + " Productos");
                    
                    if (length > 0) {
                        $('#nombreCategoria').html(productos[0].categoria.descripcion);

                        for (var i=0; i < length; i++) {
                            _this.dibujarProductoEnPantalla($('#contenedor'), productos[i]);

                            //dibujo el menu del costado
                            $('#listaProductos').append("<li><a href='producto.html?id=" + productos[i].id + "&categoria=" + productos[i].categoria.id + "' >" + productos[i].modelo + "</a></li>");
                        }
                    }
					_this.bindearBotones();
			  },
			  error:function(e){
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
		producto.Talle = this.recorrerCheckbox('talles');
		producto.Color = this.recorrerCheckbox('colores');
		producto.Stock = $("#stock").val();
		producto.Precio = $("#precio").val();
		
		return producto;	
	},
    completarInputs: function(producto) {
        var _this = this;
        
        $("#id").val(producto.id);
        $("#modelo").val(producto.modelo);
        $("#descripcion").val(producto.descripcion);
        $("#categoria").val(producto.categoria.id);
        $("#stock").val(producto.stock);
        $("#precio").val(producto.precio);
    },
    completarDetalle: function(producto) {

        if (producto.id === 0) return;
        
        var length = producto.fotos.length,
            categoria = producto.categoria['descripcion'],
            modelo = producto.modelo,
            ul = $(".bxslider"), thumb = {},
            link = {}, ruta = "", img = {}, li = {}, a = {};

        $(".divProducto").attr('id',producto.id);
        $("#modelo").html(producto.modelo);
        $("#precio").html(producto.precio);
        $("#descripcion").html(producto.descripcion);
        
        /* INICIO CARGA CAROUSEL IMAGENES PRODUCTO */
        if (producto.fotos.length > 0 && producto.fotos[0].ruta !== null) {
            var div = $("#bx-pager");
            var index = 0;
            
            for (var i = 0; i < length; i++) {
                img = $("<img>");
                img.attr({src: producto.fotos[i].ruta.replace("../", ""), 
                          title: producto.modelo});
                
                li = $("<li>")
                li.append(img);
                ul.append(li);
                
                a = $("<a>");
                a.attr({"data-slide-index": index.toString(), href: ""});
                
                thumb = $("<img>");
                thumb.attr({src: producto.fotos[i].rutaThumbnail.replace("../", "")})
                a.append(thumb);
                div.append(a);
                index++;
            }
        } else {
            img = $("<img>");
            img.attr({src: "img/na.jpg"});
            img.css({width: '100%'});
            li = $("<li>");
            li.append(img);
            ul.append(li);
        }
        /* FIN CARGA CAROUSEL IMAGENES PRODUCTO */
        
        // CAROUSEL PRODUCTOS
        $('.bxslider').bxSlider({
            pagerCustom: '#bx-pager',
            randomStart: true,
            adaptiveHeight: true
        });
        
        //dibujo los colores
		if (producto.colores[0].id != null) {
			for (var i = 0; i < producto.colores.length; i++){
				var div = $("<div>");
				div.addClass('circle');
				div.attr('style','background:' + producto.colores[i].descripcion);
				div.attr('data-color',producto.colores[i].id);

				$('.colores').append(div);
			}
		} else {
			$('.colores').append('Sin color para elegir');
		}

        //dibujo los talles
		if (producto.talles[0].id != null) {
			for (var i=0 ; i < producto.talles.length; i++){
				var span = $("<span>");
				span.addClass('talle');
				span.attr('data-talle',producto.talles[i].id);
				span.html(producto.talles[i].descripcion);

				$('.talla').append(span);
			}
		} else {
			$('.talla').append('Sin talle para elegir');
		}
         
        //para dibujar si tiene o no stock
        if(producto.stock > 0) {
            for (var i=1; i < producto.stock; i++) {
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
                    $('#mensaje').html('"Producto no encontrado.');
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
                    error:function(e){
                      console.log('Error al ejecutar la petición por:' + e);
                    }
                });
            } 
        });
        
       _this.bindearBuscadorProductos();       
    },
    buscarProductos: function(texto) {
        var _this = this;
        $('#textoBuscado').html(texto);
        $.get('php/controllerProducto.php', {buscar:texto },function(respuestaJson) {
                var productos = JSON.parse(respuestaJson);
                    if(productos.length > 0) {
                        $('#contenedor').empty();
                        for (var i=0; i < productos.length; i++) {
                            _this.dibujarProductoEnPantalla($('#contenedor'), productos[i]);
                        }
                        _this.bindearBotones();

                    } else {
                        $('#contenedor').html('No se encontraron resultados.');
                    }
            }).error(function(e){
                console.log('Error al ejecutar la petición.' + e);
            });
    },
	bindearBuscadorProductos: function() {
		var _this = this;
			
         $("#formBusqueda").submit(function(e){
            e.preventDefault();
            var texto = $("#txtBusqueda").val();
            window.location.href = "productos.html?q=" + texto;
            //_this.buscarProductos(texto);
            
           
            return false;
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
    eliminarImagen: function(idImagen, pathImagen, pathThumb) {

        $.ajax({ 
            type: 'DELETE',   
            url: "php/controllerProducto.php",
            data : {idImagen: idImagen, pathImagen: pathImagen, 
                    pathThumb: pathThumb},
            success:  function(respuestaJson){  
                Producto.self().mostrarMensaje("success", 
                                               "Imagen eliminada exitosamente");
            },
            error:function(e){
              console.log('Error al ejecutar la petición por:' + e);
            }
        });   
        
    },
    mostrarMensaje: function(tipo, mensaje, url = "", tiempo = 3000) {
        
        $('#box-messages').empty();
        $('#box-messages').append("<div class='alert alert-" + tipo + "'>" +
                                  mensaje + "</div>");

        window.setTimeout(function() {
          $("#box-messages div").fadeTo(500, 0).slideUp(500, function() {
              $(this).remove();
              if (url != "") window.location.href = url;
          });
        }, tiempo);
        
    }
}