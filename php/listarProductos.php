<?php
session_start();
require_once 'autoload.php';

//TRAIGO TODOS LOS PROD
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	$productos = Producto::getTodos();
//echo $criterio;
	echo json_encode($productos);

	
} 


