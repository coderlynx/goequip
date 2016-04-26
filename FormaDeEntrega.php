<?php
session_start();

if(!isset($_SESSION["nombre"]))	{
    echo "Tenes que estar logueado";
    exit;
    //header("Location: index.php");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FormaDeEntrega</title>
</head>
<body>
    <div >
       <h2>Forma de Entrega</h2>
        <h4>Opciones disponibles</h4>

            <div id="div_forma_entrega">
                <div >
                    <input name="entrega" value="2"  id="delivery" type="radio">
                    <label for="delivery">Envío a Domicilio</label>
                    <span>(Costo de envío $199)</span>
                </div>
                <div >
                    <input name="entrega" value="1" id="retiro" type="radio">
                    <label for="retiro">Retiro en Sucursal</label>
                    <span>(Gratis)</span>
                </div>
            </div>
        </div>
        
        <div id="domicilio_entrega" >
            <p>Ingrese los datos donde se debe entregar.</p>
             <input type="text" id="calle" placeholder="Calle" required>
              <input type="text" id="numero" placeholder="Número" required>
              <input type="text" id="piso" placeholder="Piso">
              <input type="text" id="depto" placeholder="Departamento">
              <input type="text" id="localidad" placeholder="Localidad" required>
              <input type="text" id="provincia" placeholder="Provincia" required>
              <input type="text" id="pais" placeholder="País" required>
              <input type="text" id="cp" placeholder="Código Postal" required> 
              <input type="button" id="btnCambiarDomicilio" value="Cambiar domicilio" />
      </div>
      
      
      <br />
      
      <label class="">
        <input id="terminos" type="checkbox" required="required">
        Acepto los <span><a href="Terminos.php">términos y condiciones</a></span> de la compra.
    </label>
     
     <br />
      
        <input type="button" value="Continuar" id="btnFormaEntrega" />
        <?php include('scripts.html') ?>
        <script src="js/Cliente.js"></script>
    <script>
        $( document ).ready(function() {
            
            $("#domicilio_entrega").hide();
            
            $("#delivery").click(function() {
                $("#domicilio_entrega").show();
            });
            
            $("#retiro").click(function() {
                $("#domicilio_entrega").hide();
            });
            
            Cliente.getClienteByIdUsuario();
            
            $("#btnFormaEntrega").click(function() {
                
                if($("#terminos").is(':checked')) {  
                
                    var radioFormaDeEntrega = $('input[name=entrega]:checked', '#div_forma_entrega').val();
                    
                    if(!radioFormaDeEntrega) {
                        alert("Debe seleccionar una forma de entrega");
                        return;
                    }

                    $.post('php/controllerPedido.php', {formaDeEntrega:radioFormaDeEntrega}, function(respuesta) {
                        //var rta = JSON.parse(respuestaJson);
                        if(respuesta == 'ok') {
                            window.location.href = "Pagar.php";
                        } else {
                            alert(respuesta);
                        }
                            
                        

                    }).error(function(e){
                            console.log('Error al ejecutar la petición por:' + e);
                        }
                    );
                } else {
                    alert("Debe aceptar los terminos y condiciones para continuar");
                }
            });
        });
    </script>	
</body>

</html>