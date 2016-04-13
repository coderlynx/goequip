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
    //echo json_encode($productos);
    
    /*
    $mp = new MP('5836268351908133', '8q3o4CY9gQKTx8LCz9clL4wQdMCBb1Zq');

    $mp->sandbox_mode(TRUE);

    $payment_info = $mp->get_payment_info($info->collection_id);

    if ($payment_info["status"] == 200) {
	   echo json_encode($payment_info["response"]); //print_r($payment_info["response"]);
    }*/
   
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $pedidos = Pedido::getAll();
    
    echo json_encode($pedidos);
    
}  else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    
    
    $pedido = Pedido::getById($_GET['id']);
    
    echo json_encode($pedido);
    
}


?>