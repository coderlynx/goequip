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
               $total['envio'] = $_SESSION['entrega']->costo;
               $total['montoTotal'] = $total['monto'] + $_SESSION['entrega']->costo;
               
               
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
			if($_SESSION['carrito']->calculateCantidadTotal() == 0 ) die("No hay productos en el carrito");


            $_SESSION['pedido']['total'] = $_SESSION['carrito']->calculateMontoTotal() + $_SESSION['entrega']->costo;
            $_SESSION['pedido']['cantidad'] = $_SESSION['carrito']->calculateCantidadTotal();
            $_SESSION['pedido']['productos'] = $_SESSION['carrito']->showProductos();

            echo 'ok';
        
        } else {//cuando agrego al carrito productos o inicializo si no existe
           if(empty($_SESSION['carrito'])) {
                $carrito = new Carrito();  
                $entrega = new Entrega(null,0,0,'',0);
                $_SESSION["carrito"] = $carrito;
                $_SESSION['entrega'] = $entrega;
            }


            $prod = json_decode($_POST['producto']);

            //NOTA: por ahora el talle y color los manejo con las constantes porque en la pantalla muestro el nombre y no el id. En pedido despues busco el id por la constante
            $producto = new Producto($prod->id, $prod->modelo, $prod->descripcion, $prod->categoria, Constantes::$TALLE[ $prod->talle], Constantes::$COLOR[$prod->color], $prod->stock, $prod->precio);
            $producto->fotos = $prod->foto;

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