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
               echo json_encode($_SESSION['carrito']->calculateTotal());
            } 
        } else {
            if (!empty($_SESSION['carrito'])) {
                echo json_encode($_SESSION['carrito']->showProductos());
            } 
        }
        
        break;
    case 'post':
        if (isset($_POST["pagar"])) { 
            if(!isset($_SESSION['nombre'])) die("Debes loguearte.");
            if(!isset($_SESSION['carrito'])) die("No hay productos en el carrito");



            $_SESSION['pedido']['total'] = $_SESSION['carrito']->calculateTotal();
            $_SESSION['pedido']['productos'] = $_SESSION['carrito']->showProductos();

            echo 'ok';
        
        } else {
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

/*
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['show'])) {
    
    if (!empty($_SESSION['carrito'])) {
       echo json_encode($_SESSION['carrito']->showProductos());
    } 

} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['calculate'])) {
    
    if (!empty($_SESSION['carrito'])) {
       echo json_encode($_SESSION['carrito']->calculateTotal());
    } 

} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['A'])) {
    
    if(empty($_SESSION['carrito'])) {
        $carrito = new Carrito();  
        $_SESSION["carrito"] = $carrito; 
    }


    $prod = json_decode($_POST['producto']);

    $producto = new Producto($prod->id, $prod->modelo, $prod->descripcion,$prod->talle, $prod->color, $prod->stock, $prod->precio);

	//echo "Agregando dos productos...";  
    $_SESSION["carrito"]->addProducto($producto);  
    //$_SESSION["carrito"]->agregarProducto($prod2);  

    echo json_encode($_SESSION["carrito"]->getProducto($prod->id)); 
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['B'])) {
    
    $id = $_POST['id'];
 
    $_SESSION["carrito"]->removeProducto($id);  

    echo json_encode($_SESSION["carrito"]->getProducto($id)); 
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagar'])){
    
    if(!isset($_SESSION['nombre'])) die("Debes loguearte.");
    if(!isset($_SESSION['carrito'])) die("No hay productos en el carrito");
    
    

    $_SESSION['pedido']['total'] = $_SESSION['carrito']->calculateTotal();
    $_SESSION['pedido']['cliente'] = 1;
    $_SESSION['pedido']['productos'] = $_SESSION['carrito']->showProductos();
    
    echo 'ok';

}*/


?>