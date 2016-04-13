<?php
require_once 'autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {

	$productos = Producto::getAll();

	echo json_encode($productos);

	
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["id"])) {

	$producto = Producto::getById($_GET["id"]);

	echo json_encode($producto);

	
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["AM"])) {
	
	$prod = json_decode($_POST['producto']);

	$producto = new Producto($prod->Id, $prod->Modelo, $prod->Descripcion,$prod->Talle, $prod->Color, $prod->Stock, $prod->Precio);
    
						
	$validator = new Validator;

	$validator->validate($producto, Producto::$reglas);

	if($validator->tuvoExito() && Producto::insert($producto)) {
		echo json_encode("exito");
		exit;
	}
	
	echo json_encode($validator->getErrores());
	

} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["B"])) {
	
	$id = json_decode($_POST['id']);

	if(Producto::delete($id)) {
		echo json_encode("exito");
		exit;
	}
	
	

}