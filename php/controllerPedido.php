<?php
require_once 'autoload.php';
require_once ('lib/mercadopago.php');
session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        if (isset($_GET["id"])) {
            $pedido = Pedido::getById($_GET['id']);
    
            echo json_encode($pedido);
        } else {
            $pedidos = Pedido::getAll();
    
            echo json_encode($pedidos);
        }
 
        break;
    case 'post':
        
        if (isset($_POST['formaDeEntrega'])) {
            $_SESSION["pedido"]["formaDeEntrega"] = $_POST['formaDeEntrega'];
            echo 'ok';
            break;
        }
        
        if (isset($_POST['idCliente'])) {
            $_SESSION['pedido']['cliente'] = json_encode(Cliente::getByIdUsuario($_SESSION['idUsuario'])); 
            echo 'ok';
            break;
        }
        
        $montoTotal = $_SESSION['pedido']['total'];
        $idCliente =  json_decode($_SESSION['pedido']['cliente'])->id;
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
           
        break;
    case 'put':
    case 'delete':
        //parseo a un array la data que viene por delete
        parse_str(file_get_contents("php://input"),$delete_vars);
        $id = $delete_vars['id'];
        
        if (!isset($id)) die("Error: no hay un id");

        if(Producto::delete($id)) {
		  die("exito");
	   }
        break;
    default:
        echo 'Metodo no reconocible';

}

/*
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
}*/


?>