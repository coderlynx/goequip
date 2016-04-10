<?php
require_once 'autoload.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	$productos = Producto::getTodos();

	echo json_encode($productos);

	
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	$prod = json_decode($_POST['producto']);

	$producto = new Producto(null, $prod->Modelo, $prod->Descripcion,$prod->Talle, $prod->Color, $prod->Stock, $prod->Precio);
    
						
	$validator = new Validator;

	$validator->validate($producto, Producto::$reglas);

	if($validator->tuvoExito() && Producto::insertProducto($producto)) {
		echo json_encode("exito");
		exit;
	}
	
	echo json_encode($validator->getErrores());
	

}