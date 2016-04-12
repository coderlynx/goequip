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
    //$obj = new Carrito();
    //$obj = $_SESSION['carrito'];
    //$carrito = $_SESSION['carrito'];
    
    //$_SESSION['obj'] = serialize($obj);
    $_SESSION['monto'] = $_SESSION['carrito']->calculateTotal();

    //$obj = unserialize($_SESSION['obj']);
    //$_SESSION['preCompra'] = serialize($_SESSION["carrito"]);
    
    //$_SESSION['carrito'] = serialize($carrito);
        //$carrito = serialize($_SESSION["carrito"]);
    // almacenamos $s en algún lugar en el que page2.php puede encontrarlo.
        
    //serialize($_SESSION["carrito"]); 
     
    //header("Location: ../Pagar.php");
    
    //die();

}

  /*
$prod2 = new Producto();  
$prod2->codigo = 2;  
$prod2->stock = 1;  
$prod2->nombre = "Silla";  
$prod2->precio = 23;  */
  

  
/*echo "Modificando el producto de codigo 1...  
";  
$auxProd = $_SESSION["carrito"]->getProducto("1");  
$auxProd->stock++;  
  
echo $_SESSION["carrito"]->trace();  
  
echo "Eliminando el producto de codigo 2...  
";  
$_SESSION["carrito"]->eliminarProducto("2");  
  
echo $_SESSION["carrito"]->trace();  
  
echo "Listo...";  */

?>