var Autenticacion = {
    init: function () {

        var _this = this;
        
        $.get('php/controllerAutenticacion.php', function(rta) {
				//var rta = JSON.parse(respuestaJson);
                $('#nombreUsuario').text(rta);
                return;
			}).error(function(e){
						console.log('Error al ejecutar la peticion');
					}
			);
		
		_this.capturarEnter();

		//botones de los formularios de registro y login y logout
		$('#formRegistrar').submit( function(e) {
		  
            e.preventDefault();
			var registro = {};
			registro.nombre = $('#inputNombre').val();
			registro.mail = $('#inputEmail').val();
			registro.password = $('#inputPassword').val();
            
            //validamos el nombre de usuario
            var regex_nombre = /[A-z_-]/;//cualquier caracter alfa incluido el _
			if (!regex_nombre.test(registro.nombre.trim())) {
				//mensaje de invalido
                $('#mensaje').html("Nombre de usuario incorrecto (el nombre debe incluir letras)");
				return;
			}
			
			//Utilizamos una expresion regular para validar mail
			var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		 
			//Se utiliza la funcion test() nativa de JavaScript
			if (!regex.test(registro.mail.trim())) {
				//mensaje de invalido
                $('#mensaje').html("formato de mail invalido");
				return;
			}
            
            //valido campos email iguales
            if ($('#inputEmail').val() != $('#inputREmail').val()) {
                $('#mensaje').html('Los emails deben coincidir.');
                return;
            } 
            
            //validamos clave
             var regex_clave = /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{6,8})$/;
            if (!regex_clave.test(registro.password.trim())) {
                $('#mensaje').html('Password incorrecto (debe contener entre 6 y 8 caracteres alfanumericos)'); 
                return;
            } 
			
			var registro_json = JSON.stringify(registro);
			
			$.post('php/controllerAutenticacion.php', {registro:registro_json}, function(rta) {
				//var rta = JSON.parse(respuestaJson);
				if(rta == "Se ha creado la cuenta con exito.") { 
                    $('#mensaje').html(rta);
                    $('#inputNombre').val("");
                    $('#inputEmail').val("");
                    $('#inputREmail').val("");
                    $('#inputREmail').val("");
                    $('#inputPassword').val("");
					//alert(respuestaJson);
					//window.location.href = "login.html";
					return;
				} else {
                    $('#mensaje').html(rta);
                }
				
			}).error(
				function(e){
					console.log('Error al registrarse');
				}
			);
            
		});
		
		$('#formLogin').submit( function(e) {
			var login = {};
			login.email = $('#inputEmail').val();
			login.password = $('#inputPassword').val();
			
            var login_json = JSON.stringify(login);
		
			$.post('php/controllerAutenticacion.php', {login:login_json}, function(respuestaJson) {
				var rta = JSON.parse(respuestaJson);
					if(rta == "") 
						{
							$('#mensaje').html('Email o clave incorrecto.');
						} else if (rta == "exito"){
							//$('#nombreUsuario').text(login.email);
                            //$('#btnComprar').attr("style","display:inline");
                            window.location.href = "index.html";
							//alert('Bienvenido ' + login.email);
							return;
						} 
						
			}).error(
					function(e){
						console.log('Error al ejecutar la peticion');
					}
			);
            e.preventDefault();
		});
		
		_this.bindearBtnCerrarSesion();
			
           /* $.get('php/controllerAutenticacion.php', function(respuestaJson) {
				//var rta = JSON.parse(respuestaJson);
                $('#nombreUsuario').text('');
                $('.totalPedido').text('0');
                $('.totalCantidad').text('0');
                $('#contenedorCarro').text('');
                //$('#btnComprar').attr("style","display:none");
				alert('Log out');
                return;
				//$('#nombre_usuario').text("anonimo");
			}).error(function(e){
						console.log('Error al ejecutar la peticion');
					}
			);*/
		//});
		
    },
    bindearBtnCerrarSesion:function(){
        $('#btnCerrarSesion').click( function() {
            $.ajax({
                url: 'php/controllerAutenticacion.php',
                type: 'DELETE',
                success: function (respuestaJson) {
                    //var rta = JSON.parse(respuestaJson);
                    if(respuestaJson) {
                       $('#nombreUsuario').text('');
                        $('.totalPedido').text('0');
                        $('.totalCantidad').text('0');
                        $('#contenedorCarro').text('');
                        //$('#btnComprar').attr("style","display:none");
                        window.location.href = "index.html";
                        //alert('Log out');
                        return;
                    }
                    else {
                        alert(respuestaJson);
                    }
                },
                error: function(e){
                    console.log('Error al ejecutar la petición por:' + e);
                }
            });
        });
    },
	capturarEnter: function() {
	
        //si toca enter dentro del form de registro llama al btn directamente
    
		/*$('#form_reg_perfil').keypress(function(e){
			if(e.which == 13) {
				$('#btn_form_registrar').click();
			}
			
		});
		
		$('#form_log_password').keypress(function(e){
			if(e.which == 13) {
				$('#btn_form_login').click();
			}
		});*/
		
		
	}
}
	