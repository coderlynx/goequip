<?php
require_once 'autoload.php';
session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        if(isset($_GET["id"])) {
           $cliente = Cliente::getById($_GET["id"]);
           echo json_encode($cliente);
           break;
        }
        
        if(isset($_GET["idUsuario"])) {
           $cliente = Cliente::getByIdUsuario($_SESSION['idUsuario']);
	       echo json_encode($cliente);
           break;
        }
        
        $clientes = Cliente::getAll();
        echo json_encode($clientes);
 
        break;
    case 'post':
        // Almaceno idUsuario
        $idUsuario = $_SESSION['idUsuario'];
        //$cli = Cliente::getByIdUsuario($idUsuario);
        // Valido si el usuario tiene un perfil de cliente creado
        //if($cli == null) {
            $cli = json_decode($_POST['cliente']);
            $domicilio = new Domicilio($cli->Calle, $cli->Numero, $cli->Piso, $cli->Depto, $cli->Localidad, $cli->Provincia, 
                             $cli->Pais, $cli->CP);
            // Paso el objeto domicilio a cliente
            $cliente = new Cliente($cli->Id, $cli->Dni, $cli->Cuil,$cli->Apellido, $cli->Nombre, $cli->Telefono, $cli->Email, 
                                   $domicilio, $idUsuario, null);

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
            }/*
        } else {
            echo json_encode("El usuario ya tiene un perfil de cliente activo.");
            exit;
        }*/
           
        break;
    case 'put':
    case 'delete':
        //parseo a un array la data que viene por delete
        parse_str(file_get_contents("php://input"),$delete_vars);
        $idCliente = $delete_vars['idCliente'];
        
        if (!isset($idCliente)) die("Error: no hay un id");

        if(Cliente::eliminar($idCliente)) {
            echo json_encode("exito");
            exit;
        }
        break;
    default:
        echo 'Metodo no reconocible';

}

/*

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
  
    /* VALIDACIONES 
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
}*/