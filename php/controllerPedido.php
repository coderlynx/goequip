<?php
require_once 'autoload.php';
require_once ('lib/mercadopago.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
    $montoTotal = $_SESSION['pedido']['total'];
    $idCliente =  $_SESSION['pedido']['cliente'];
    $productos = $_SESSION['pedido']['productos']; 
    $formaDeEntrega = $_SESSION["pedido"]["formaDeEntrega"];
    
    $ped = json_decode($_POST['pedido']);
    
    $formaDePago = Constantes::$PAGO_TARJETA;
    
    if($ped->formaDePago != 'credit_card') $formaDePago = Constantes::$PAGO_FACTURA;
    
    $pedido = new Pedido(null, $ped->nroPedido, $idCliente ,$montoTotal,$formaDePago, $formaDeEntrega,$ped->estadoDePago, $productos,null);
    
        
    $validator = new Validator;

	$validator->validate($pedido, Pedido::$reglas);

	if($validator->tuvoExito() && Pedido::insert($pedido)) {
		echo $pedido->nroPedido;
		exit;
	}
	
	echo json_encode($validator->getErrores());
   
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['G'])) {
    
    $pedidos = Pedido::getAll();
    
    echo json_encode($pedidos);
    
}  else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    
    
    $pedido = Pedido::getById($_GET['id']);
    
    echo json_encode($pedido);
    
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['formaDeEntrega'])) {
    $_SESSION["pedido"]["formaDeEntrega"] = $_GET['formaDeEntrega'];
    
    echo "ok";
}


?>