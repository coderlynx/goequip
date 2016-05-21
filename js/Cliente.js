var dni, cuil, apellido, nombre, telefono, email, cont = 0;

var Cliente = {
    init: function() {
        var _this = this;
        _this.mostrarClientes();
        
        //cuando presiona guardar
        $("#btnAltaCliente").click(function() {
            if( _this.validarCampos()) {
                var cliente = _this.armarObjetoCliente();
                _this.insertCliente(cliente);
                _this.mostrarClientes();
                _this.resetAll();
            }  
        });
    },
    validarCampos: function() {
      return true;
    },
    armarObjetoCliente: function() {		
		var cliente = {};

        cliente.Id = $("#idCliente").val().trim();
		cliente.Dni = $("#dni").val();
		cliente.Cuil = $("#cuil").val();
		cliente.Apellido = $("#apellido").val();
		cliente.Nombre = $("#nombre").val();
		cliente.Telefono = $("#telefono").val();
		cliente.Email = $("#email").val();
        // Domicilio
        cliente.Calle = $("#calle").val();
        cliente.Numero = $("#numero").val();
        cliente.Piso = $("#piso").val();
        cliente.Depto = $("#depto").val();
        cliente.Localidad = $("#localidad").val();
        cliente.Provincia = $("#provincia").val();
        cliente.Pais = "Argentina";//TEMPORAL SI SE USA UN COMBO $("#pais").val();
        cliente.CP = $("#cp").val();
		
		return cliente;
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
        var _this = this;
        contenedor.empty();
        contenedor.css("display","block");
        var div_item = $('<div>');
            div_item.attr('id',cliente.id);
            //div_item.addClass('');
        
        var p = $("<p>");
        p.html("DNI: " + cliente.dni + ", Apellido: " + cliente.apellido + ", Nombre: " + cliente.nombre +
              ", CUIL: " + cliente.cuil + ", Teléfono: " + cliente.telefono + ", E-mail: " + cliente.email +
              ", Calle: " + cliente.domicilio.calle + ", Número: " + cliente.domicilio.numero + 
              ", Localidad: " + cliente.domicilio.localidad + " ");
        div_item.append(p);
        var a = $("<a href='#' class='eliminar'>Dar de baja</a>");
        p.append(a);
        var a = $("<a href='#' class='editar'>Editar</a>");
        p.append("&nbsp;&nbsp;&nbsp;");
        p.append(a);
        contenedor.append(div_item);
        
        _this.agregarManejadoresEvento();
		
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
                $("#btnDatosPersonales").click();
                //window.location.href = "Pagar.php";
                //location.reload();
            } else {
              alert(rta);
            }
        }).error(function(e){
                console.log('Error al ejecutar la petición por:' + e);
          }
        );
        return false;
    },
    editCliente: function(idCliente) {
        var _this = this;
        $.get("php/controllerCliente.php", {id: idCliente}, function(respuestaJson) {
            var rta = JSON.parse(respuestaJson);
            _this.mostrarDatosCliente(rta);
        });
    },
    eliminarCliente: function(idCliente) {
        $.ajax({
              async:false,    
              cache:false,   
              type: 'DELETE', 
              data: {idCliente:idCliente },
              url: "php/controllerCliente.php",
              success:  function(rta){
                    if(rta == "exito") {
                        alert("El cliente ha sido eliminado con exito.");
                    } else {
                      console.log(rta);
                    }
              },
              error:function(objXMLHttpRequest){
                   console.log('Error al ejecutar la petición por:' + e);
              }
        });
        return false;
    },
    getClienteByIdUsuario: function() {
        var _this = this;
        $.ajax({
              async:false,    
              cache:false,   
              type: 'GET', 
              data: {idUsuario: true},
              url: "php/controllerCliente.php",
              success: function(respuestaJson){
                if(respuestaJson == "No se encontro el cliente.") {
                    $("#mensaje_cliente").html(respuestaJson);
                    return false;
                }
                    var rta = JSON.parse(respuestaJson);
                    _this.mostrarDatosCliente(rta);
                    //$("#nombre").val(rta.nombre);
              },
              error:function(objXMLHttpRequest){
                   console.log('Error al ejecutar la petición por:' + e);
              }
        });   
    },
    mostrarDatosCliente: function(cliente) {
        if($("#idCliente")) {//MOMENTANEAMENTE PARA DIBUJAR EN LA PANTALLA DE FORMA DE ENTREGA SOLO EL DOMICILIO
            $("#idCliente").val(cliente.id);
            $("#dni").val(cliente.dni);
            $("#cuil").val(cliente.cuil);
            $("#apellido").val(cliente.apellido);
            $("#nombre").val(cliente.nombre);
            $("#telefono").val(cliente.telefono);
            $("#email").val(cliente.email);
        }
        // Domicilio
        $("#calle").val(cliente.domicilio.calle);
        $("#numero").val(cliente.domicilio.numero);
        $("#piso").val(cliente.domicilio.piso);
        $("#depto").val(cliente.domicilio.depto);
        $("#localidad").val(cliente.domicilio.localidad);
        $("#provincia").val(cliente.domicilio.provincia);
        $("#pais").val(cliente.domicilio.pais);
        $("#cp").val(cliente.domicilio.cp);
        
        $("#nombre_formulario").text("Editar Cliente");
        $("#btnAltaCliente").val("Editar");
	},
    resetAll: function() {
        $("#frmCliente")[0].reset();
        $("#idCliente").val("");
        $("#nombre_formulario").text("Agregar Cliente");
        $("#btnAltaCliente").val("Guardar");
    },
    agregarManejadoresEvento: function(){
        var _this = this;
        // Cuando presiono link 'Editar'
        $("a.editar").click(function(e){
            var idCliente = $(this).parent().parent().attr("id");
            _this.editCliente(idCliente);
        });
        // Cuando presiono link 'Eliminar'
        $("a.eliminar").click(function(e){
            var idCliente = $(this).parent().parent().attr("id");
            _this.eliminarCliente(idCliente);
        });
    }
}