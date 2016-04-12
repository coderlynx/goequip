var dni, cuil, apellido, nombre, telefono, email;

var Cliente = {
    init: function() {
        var _this = this;
        
        _this.mostrarClientes();

      //cuando presiona guardar
        $("#btnAltaCliente").click(function() {
            if( _this.validarCampos()) {
                var cliente = _this.armarObjetoCliente();
                _this.insertCliente(cliente);
            }
        })
    },
    validarCampos: function() {
        return true;
    },
    mostrarClientes: function () {
        var _this = this;
        //Lo hice de esta forma porque necesitaba que el asincronismo sea false
		$.ajax({
            async:false,    
            cache:false,   
            type: 'GET',   
            url: "php/controllerCliente.php",
            success:  function(clientes){  
                  var rta = JSON.parse(clientes);
                  //console.log( "Data Loaded: " + rta );
                  var items = [];
                  for (i=0; i < rta.length; i++) {
                      _this.dibujarClienteEnPantalla($('#contenedor'), rta[i]);
                  }
            },
            error:function(objXMLHttpRequest){
                var e = objXMLHttpRequest;
            }
          });
    },
	dibujarClienteEnPantalla: function(contenedor, cliente) {
        var div_item = $('<div>');
			div_item.attr('id',cliente.id);
			//div_item.addClass('');
        
        var p = $("<p>");
        p.html(cliente.dni);
        div_item.append(p);
        contenedor.append(div_item);
        
		return div_item;		 
	}, 
    insertCliente: function (cliente) {
        var _this = this;

        //Parseo a JSON el obj Cliente
        var jsonCliente = JSON.stringify(cliente);

        // guardo el aviso
        $.post('php/controllerCliente.php', {cliente:jsonCliente }, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            if(rta == "exito") {
                alert("El cliente ha sigo guardado con exito.");
            }
                //console.log(rta);
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
          }
        );
        return false;
    },
    editCliente: function() {
        return false;
    },
	armarObjetoCliente: function() {		
		var cliente = {};
		
		cliente.Dni = $("#dni").val();
		cliente.Cuil = $("#cuil").val();
		cliente.Apellido = $("#apellido").val();
		cliente.Nombre = $("#nombre").val();
		cliente.Telefono = $("#telefono").val();
		cliente.Email = $("#email").val();
		
		return cliente;
	},
}