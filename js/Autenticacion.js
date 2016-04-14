var Autenticacion = {
    init: function () {

        var _this = this;
		
		_this.capturarEnter();

		//botones de los formularios de registro y login y logout
		$('#btn_form_registrar').click( function() {
		
			var registro = {};
			registro.nombre = $('#form_reg_usuario').val();
			registro.mail = $('#form_reg_mail').val();
			registro.password = $('#form_reg_password').val();
			
			
			//Utilizamos una expresion regular para validar mail
			var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		 
			//Se utiliza la funcion test() nativa de JavaScript
			if (!regex.test($('#form_reg_mail').val().trim())) {
				//mensaje de invalido
                alert("formato de mail invalido");
				return;
			}
			
			var registro_json = JSON.stringify(registro);
			
			$.post('php/controllerAutenticacion.php', {registro:registro_json}, function(respuestaJson) {
				//var rta = JSON.parse(respuestaJson);
				//if(rta == "exito") { 
					alert(respuestaJson);
					
					return;
				//} 
				
			}).error(
				function(e){
					console.log('Error al registrarse');
				}
			);
		});
		
		$('#btn_form_login').click( function() {
			var login = {};
			login.nombre = $('#form_log_usuario').val();
			login.password = $('#form_log_password').val();
			
            var login_json = JSON.stringify(login);
		
			$.post('php/controllerAutenticacion.php', {login:login_json}, function(respuestaJson) {
				var rta = JSON.parse(respuestaJson);
					if(rta == "") 
						{
							alert('No se encontro al usuario o el mail');
						} else if (rta == "exito"){
							//$('#nombre_usuario').text(login.nombre);
							alert('Bienvenido ' + login.nombre);
							return;
						} 
						
			}).error(
					function(e){
						console.log('Error al ejecutar la peticion');
					}
			);
		});
		
		$('#btnCerrarSesion').click( function() {
			$.get('php/controllerAutenticacion.php', function(respuestaJson) {
				//var rta = JSON.parse(respuestaJson);
				alert('Log out');
                return;
				//$('#nombre_usuario').text("anonimo");
			}).error(function(e){
						console.log('Error al ejecutar la peticion');
					}
			);
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
	