<?php
session_start();

if(!isset($_SESSION["nombre"]))	{
    echo "Tenes que estar logueado";
    exit;
    //header("Location: index.php");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
}
//echo $_SESSION["idUsuario"];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirmacion de datos</title>
</head>
<body>
    <div >
       <h2>Confirmar datos personales</h2>
        <h3 id="nombre_formulario">Datos Personales</h3>
        <p>Debe completar sus datos personas antes de proseguir.</p>
        <p>Si ya esta registrado, puede seguir adelante o editar sus datos si es que cambiaron</p>
        
        <p id="mensaje_cliente" style="color:red;"></p>
        
      <form id="">
          <input id="idCliente" type="hidden">
          <input id="dni" type="text" placeholder="DNI" required>
          <input id="cuil" type="text" placeholder="CUIL / CUIT" value="">
          <input id="apellido" type="text" placeholder="Apellido" required>
          <input id="nombre" type="text" placeholder="Nombre" required>
          <input id="telefono" type="text" placeholder="Télefono">
          <input id="email" type="text" placeholder="E-mail" required>
          <br>
          <input type="text" id="calle" placeholder="Calle" required>
          <input type="text" id="numero" placeholder="Número" required>
          <input type="text" id="piso" placeholder="Piso">
          <input type="text" id="depto" placeholder="Departamento">
          <input type="text" id="localidad" placeholder="Localidad" required>
          <input type="text" id="provincia" placeholder="Provincia" required>
          <input type="text" id="pais" placeholder="País" required>
          <input type="text" id="cp" placeholder="Código Postal" required>
          <br>
          <input type="button" id="btnAltaCliente" value="Guardar">
      </form>
      <br />
      
      <input type="button" value="Continuar" id="btnDatosPersonales" />
    </div>
        <?php include('scripts.html') ?>
        <script src="js/Cliente.js"></script>
        <script>
            $( document ).ready(function() {
                
                Cliente.getClienteByIdUsuario();
                
                $("#btnAltaCliente").click(function() {
                    if( Cliente.validarCampos()) {
                        var cliente = Cliente.armarObjetoCliente();
                        Cliente.insertCliente(cliente);
                    }
                });
                
                
                $("#btnDatosPersonales").click(function() {
                    
                    var idCliente = 1;
                    
                    $.post('php/controllerPedido.php', {idCliente:idCliente}, function(respuesta) {
                        //var rta = JSON.parse(respuestaJson);
                        if(respuesta == 'ok') {
                           window.location.href = "FormaDeEntrega.php";
                        } else {
                            alert(respuesta);
                        }
                            
                        

                    }).error(function(e){
                            console.log('Error al ejecutar la petición por:' + e);
                        }
                    );
                    
                    

                });
            });
        </script>	
</body>
</html>