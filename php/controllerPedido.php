<?php
require_once 'autoload.php';
require_once ('lib/mercadopago.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    $montoTotal = $_SESSION['pedido']['total'];
    $idCliente =  $_SESSION['pedido']['cliente'];
    $productos = $_SESSION['pedido']['productos']; 
    
    $ped = json_decode($_POST['pedido']);
    
    $formaDePago = 1;
    
    if($ped->formaDePago != 'credit_card') $formaDePago = 2;
    
    $pedido = new Pedido(null, $ped->nroPedido, $idCliente ,$montoTotal,$formaDePago, 1,$ped->estadoDePago, $productos,null);
    
        
    $validator = new Validator;

	$validator->validate($pedido, Pedido::$reglas);

	if($validator->tuvoExito() && Pedido::insert($pedido)) {
		echo $pedido->nroPedido;
		exit;
	}
	
	echo json_encode($validator->getErrores());
   
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && (!isset($_GET['id']))) {
    
    $pedidos = Pedido::getAll();
    
    echo json_encode($pedidos);
    
}  else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    
    
    $pedido = Pedido::getById($_GET['id']);
    
    echo json_encode($pedido);
    
}


?>