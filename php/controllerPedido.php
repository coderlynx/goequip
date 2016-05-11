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

        //PASO 2: cuando el cliente confirma sus datos o los crea
        if (isset($_POST['idCliente'])) {
            //valido el stock antes de comprar
            Pedido::validateStockDelPedido($_SESSION['pedido']['productos']);
            
            if(!isset($_SESSION['idUsuario'])) die("Usuario no logueado");
            $_SESSION['pedido']['cliente'] = json_encode(Cliente::getByIdUsuario($_SESSION['idUsuario'])); 
            echo 'ok';
            break;
        }
        
        //PASO 3: cuando elijo la forma de entrega y valido que haya stock antes de comprar
        if (isset($_POST['formaDeEntrega'])) {
            
            
            $entrega = json_decode($_POST['formaDeEntrega']);
            
            $_SESSION['entrega']->costo = $entrega->costo;
            $_SESSION['entrega']->idZona = $entrega->idLugar;
            $_SESSION['entrega']->nombreZona = $entrega->nombreLugar;
            $_SESSION["pedido"]["formaDeEntrega"] = $_SESSION['entrega'];
            echo 'ok';
            break;
        }
        
        //valido antes de seguir que exista el cliente
        Cliente::getByIdUsuario($_SESSION['idUsuario']); 
            
        
        $montoTotal = $_SESSION['pedido']['total'];
        $cantidadTotal = $_SESSION['pedido']['cantidad'];
        $idCliente =  json_decode($_SESSION['pedido']['cliente'])->id;
        $productos = $_SESSION['pedido']['productos']; 
        $formaDeEntrega =  $_SESSION["pedido"]["formaDeEntrega"];

        $ped = json_decode($_POST['pedido']);

        $formaDePago = Constantes::PAGO_TARJETA;

        if($ped->formaDePago != 'credit_card') $formaDePago = Constantes::PAGO_FACTURA;

        $pedido = new Pedido(null, $ped->nroPedido, $idCliente ,$montoTotal, $cantidadTotal, $formaDePago, $formaDeEntrega,$ped->estadoDePago, $productos,null);


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



?>