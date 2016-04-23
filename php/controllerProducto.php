<?php
require_once 'autoload.php';
session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        if (isset($_GET["id"])) {
            $producto = Producto::getById($_GET["id"]);
            echo json_encode($producto);
            
            break;
        }
        
        if (isset($_GET["buscar"])) {
            $productos = Producto::getByTexto($_GET["buscar"]);
            echo json_encode($productos);
            
            break;
        }
        
        $productos = Producto::getAll();

        echo json_encode($productos);
       
 
        break;
    case 'post':
        $prod = json_decode($_POST['producto']);

	   $producto = new Producto($prod->Id, $prod->Modelo, $prod->Descripcion,$prod->Talle, $prod->Color, $prod->Stock, $prod->Precio);
    
						
	   $validator = new Validator;

	   $validator->validate($producto, Producto::$reglas);

	   if($validator->tuvoExito() && Producto::insert($producto)) {
		  echo json_encode("exito");
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
        
        /*if (method_exists($recurso, $metodo)) {
			//debemos realizar una generalización de métodos a través de la función call_user_func(), ya que no sabemos que recurso fue accedido desde la url. Con esto evitamos usar estructuras de decisión y solo llamamos el método de la clase necesitada.
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }*/
    default:
        echo 'Metodo no reconocible';

}

/*
if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_GET['id'])) {

	$productos = Producto::getAll();

	echo json_encode($productos);

	
} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["id"])) {

	$producto = Producto::getById($_GET["id"]);

	echo json_encode($producto);

	
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["AM"])) {
	
	$prod = json_decode($_POST['producto']);

	$producto = new Producto($prod->Id, $prod->Modelo, $prod->Descripcion,$prod->Talle, $prod->Color, $prod->Stock, $prod->Precio);
    
						
	$validator = new Validator;

	$validator->validate($producto, Producto::$reglas);

	if($validator->tuvoExito() && Producto::insert($producto)) {
		echo json_encode("exito");
		exit;
	}
	
	echo json_encode($validator->getErrores());
	

} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["B"])) {
	
	$id = json_decode($_POST['id']);

	if(Producto::delete($id)) {
		echo json_encode("exito");
		exit;
	}
	
	

}*/