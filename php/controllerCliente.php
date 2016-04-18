<?php
require_once 'autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {
	$clientes = Cliente::getAll();
	echo json_encode($clientes);
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["id"])) {
	$cliente = Cliente::getById($_GET["id"]);
	echo json_encode($cliente);
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["idUsuario"])) {
	$cliente = Cliente::getByIdUsuario($_SESSION['idUsuario']);
	echo json_encode($cliente);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["idCliente"])) {
    $idCliente = $_POST['idCliente'];
    if(Cliente::eliminar($idCliente)) {
      echo json_encode("exito");
      exit;
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$cli = json_decode($_POST['cliente']);
    $domicilio = new Domicilio($cli->Calle, $cli->Numero, $cli->Piso, $cli->Depto, $cli->Localidad, $cli->Provincia, 
                     $cli->Pais, $cli->CP);
    // Paso el objeto domicilio a cliente
    $cliente = new Cliente(null, $cli->Dni, $cli->Cuil,$cli->Apellido, $cli->Nombre, $cli->Telefono, $cli->Email, $domicilio);
  
    /* VALIDACIONES */
	$validator = new Validator;
    // Validación datos cliente
	$validator->validate($cliente, Cliente::$reglas);
    if(!$validator->tuvoExito()){
      echo json_encode($validator->getErrores());
      exit;
    }
    // Validación datos domicilio
    $validator->validate($domicilio, Domicilio::$reglas);
    if(!$validator->tuvoExito()){
      echo json_encode($validator->getErrores());
      exit;
    }
    // Validación INSERT en tablas clientes y domicilios
	if(Cliente::insertCliente($cliente)) {
      echo json_encode("exito");
      exit;
	}
}