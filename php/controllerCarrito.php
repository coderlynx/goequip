<?php
require_once 'autoload.php';
session_start();


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
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sesion'])){
    session_destroy();
    
    echo json_encode("ok");
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pagar'])){

    $_SESSION['pedido']['total'] = $_SESSION['carrito']->calculateTotal();
    $_SESSION['pedido']['cliente'] = 1;
    $_SESSION['pedido']['productos'] = $_SESSION['carrito']->showProductos();

}


?>