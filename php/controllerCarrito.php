<?php
require_once 'autoload.php';
session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        if (!empty($_GET["calculate"])) {
           if (isset($_SESSION['carrito'])) {
               $total['monto'] = $_SESSION['carrito']->calculateMontoTotal();
               $total['cantidad'] = $_SESSION['carrito']->calculateCantidadTotal();
               
               echo json_encode($total);
            } 
        } else {
            if (!empty($_SESSION['carrito'])) {
                echo json_encode($_SESSION['carrito']->showProductos());
            } 
        }
        
        break;
    case 'post':
        //cuando realizo el pago paso el carrito al pedido
        if (isset($_POST["pagar"])) { 
            if(!isset($_SESSION['nombre'])) die("Debes loguearte.");
            if(!isset($_SESSION['carrito'])) die("No hay productos en el carrito");



            $_SESSION['pedido']['total'] = $_SESSION['carrito']->calculateMontoTotal();
            $_SESSION['pedido']['cantidad'] = $_SESSION['carrito']->calculateCantidadTotal();
            $_SESSION['pedido']['productos'] = $_SESSION['carrito']->showProductos();

            echo 'ok';
        
        } else {//cuando agrego al carrito productos o inicializo si no existe
           if(empty($_SESSION['carrito'])) {
                $carrito = new Carrito();  
                $_SESSION["carrito"] = $carrito; 
            }


            $prod = json_decode($_POST['producto']);

            $producto = new Producto($prod->id, $prod->modelo, $prod->descripcion, $prod->categoria, $prod->talle, $prod->color, $prod->stock, $prod->precio);

            //echo "Agregando dos productos...";  
            $_SESSION["carrito"]->addProducto($producto);  
            //$_SESSION["carrito"]->agregarProducto($prod2);  

            echo json_encode($_SESSION["carrito"]->getProducto($prod->id)); 
        }
        break;
    case 'put':
    case 'delete':
        //parseo a un array la data que viene por delete
        parse_str(file_get_contents("php://input"),$delete_vars);
        $id = $delete_vars['id'];
        
        //$id = $_POST['id'];
 
        $_SESSION["carrito"]->removeProducto($id);  

        echo json_encode($_SESSION["carrito"]->getProducto($id)); 
        
        break; 
    default:
        echo 'Metodo no reconocible';

}


?>