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
        if (isset($_GET["idCategoria"])) {
            $orden = 'ordenarPor' . $_GET["orden"];
            $productos = Producto::getByCategoria($_GET["idCategoria"],$orden);
            echo json_encode($productos);   
            break;
        }
        $productos = Producto::getAll();
        echo json_encode($productos);
        break;
    case 'post':   
        $prod = json_decode($_POST['producto']);
        $producto = new Producto($prod->Id, $prod->Modelo, $prod->Descripcion, $prod->Categoria, 
                                 $prod->Talle, $prod->Color, $prod->Stock, $prod->Precio);

        $validator = new Validator;
        $validator->validate($producto, Producto::$reglas);

        if ($validator->tuvoExito() && Producto::insert($producto)) {
            $_SESSION['idProducto'] = $producto->id;
            
            echo json_encode($_SESSION['idProducto']);
            exit;
        }
        echo json_encode($validator->getErrores());
        break;
    case 'put':
    case 'delete':        
        //parseo a un array la data que viene por delete
        parse_str(file_get_contents("php://input"),$delete_vars);
        $id = $delete_vars['id'];
        $rutaFoto = $delete_vars['ruta'];

        if (!isset($id)) die("Error: no hay un id");
        if (Producto::delete($id)) die("exito");
        
        break;
    default:
        echo "Request Method NO reconocible";
}
?>