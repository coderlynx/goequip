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
    <style>
      #contenedor {
        display: none;
        border: 1px solid black;
        padding: 10px;
      }
      #contenedor div:nth-child(even) {
        background-color: beige;
      }
    </style>
  </head>

  <body>
      <div id="contenedor"></div>
      <h3 id="nombre_formulario">Agregar Cliente</h3>
      <form id="">
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

    <?php include('scripts.html') ?>
    <script src="js/Cliente.js"></script>
    <script>
        $( document ).ready(function() {
          Cliente.init();
        });
    </script>	
  </body>
</html>