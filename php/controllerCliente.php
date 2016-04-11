<?php
require_once 'autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$clientes = Cliente::getTodos();
	echo json_encode($clientes);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$cli = json_decode($_POST['cliente']);
	$cliente = new Cliente(null, $cli->Dni, $cli->Cuil,$cli->Apellido, $cli->Nombre, $cli->Telefono, $cli->Email);
	$validator = new Validator;
	$validator->validate($cliente, Cliente::$reglas);

	if($validator->tuvoExito() && Cliente::insertCliente($cliente)) {
		echo json_encode("exito");
		exit;
	}
	echo json_encode($validator->getErrores());
}