<?php
session_start();
//if(!isset($_SESSION["nombre"]))	
  //header("Location: home");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
      <meta charset="utf-8">
      <title>Agregar Cliente</title>
  </head>

  <body>
      <div id="contenedor"></div>
      <h3 id="nombre_formulario">Agregar Cliente</h3>
      <form id="">
          <input id="dni" type="text" placeholder="DNI" value="">
          <input id="cuil" type="text" placeholder="CUIL / CUIT" value="">
          <input id="apellido" type="text" placeholder="Apellido" value="">
          <input id="nombre" type="text" placeholder="Nombre" value="">
          <input id="telefono" type="text" placeholder="Télefono" value="">
          <input id="email" type="text" placeholder="E-mail" value="">
          <input type="button" id="btnAltaCliente" value="Guardar">
      </form>

    <?php include('scripts.html') ?>
    <script src="js/Cliente.js"></script>
    <script>
        $( document ).ready(function() {
          Cliente.init();
        });
    </script>	
  </body>
</html>