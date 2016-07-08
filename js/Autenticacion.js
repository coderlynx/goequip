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
            
            //valido campos clave iguales
            if ($('#inputPasswordRepetida').val() != registro.password) {
                $('#mensaje').html('Las claves deben coincidir.');
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
			
        $('#formCambiarClave').submit(function(e) {
            e.preventDefault();
            var mail_input = $('#inputEmailRecuperar').val();

            //Utilizamos una expresion regular para validar mail
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            //Se utiliza la funcion test() nativa de JavaScript
            if (!regex.test($('#inputEmailRecuperar').val().trim())) {
                $('#mensaje').html('Email invalido incorrecto.');
                return;
            }
            
            $.ajax({
                url: 'php/controllerAutenticacion.php',
                type: 'PUT',
                data:  {recuperarMail:mail_input },
                success: function (rta) {
                    if(rta == 'ok') {
                        $('#mensaje').html('Se te envio un link a tu mail para cambiar la password.');
                    } else {
                        $('#mensaje').html(rta);
                    }		
                },
                error: function(e){
                    console.log('Error al ejecutar la petición por:' + e);
                }
            });
        });
        
        $('#formCambioConClave').submit(function(e) {
            e.preventDefault();
			var password = $('#inputClaveNueva').val();
            var mailURL = _this.getUrlParameter('mail');
            var hash = _this.getUrlParameter('id');
            
            //validamos clave
            var regex_clave = /(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{6,8})$/;
            if (!regex_clave.test(password.trim())) {
                $('#mensaje').html('Password incorrecto (debe contener entre 6 y 8 caracteres alfanumericos)'); 
                return;
            } 
            
            $.ajax({
                url: 'php/controllerAutenticacion.php',
                type: 'PUT',
                data: {passNuevo:password, mailURL:mailURL, id:hash},
                success: function (rta) {
                   if(rta == 'ok') {
                        $('#mensaje').html("Cambio de clave exitoso.");
						$('#linkLogin').show();
						$('#inputClaveNueva').val("");
                    } else {
                        $('#mensaje').html(rta);
                    }			
                },
                error: function(e){
                    console.log('Error al ejecutar la petición por:' + e);
                }
            });
        });
			
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
		
		
	},
    getUrlParameter: function(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    }
}
	