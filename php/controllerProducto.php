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
        }
        echo json_encode($validator->getErrores());
        
        // Si se cargaron imágenes, almaceno el array retornado con las rutas
        if (!(empty($_FILES))) {
            $imagenes = array();
            $imagenes = Funciones::tratarImagenes($_FILES);
            
            foreach ($imagenes as $key => $value) {
                
                $imagen = new Imagen(null, $imagenes[$key]->nombre, $imagenes[$key]->tipo, 
                                     $imagenes[$key]->size, $imagenes[$key]->ruta,
                                     $imagenes[$key]->rutaThumbnail, $_SESSION['idProducto']);
                
                if ($result = Imagen::insert($imagen)) {
                    echo json_encode("Imagen insertada con éxito.");
                } else {
                    echo json_encode("No se han podido insertar las imágenes.");
                }
                
            }
        } else {
            echo json_encode("No hay imágenes cargadas.");
        }
        
        break;
        
    case 'put':
        break;
        
    case 'delete':   
        
        //parseo a un array la data que viene por delete
        parse_str(file_get_contents("php://input"), $delete_vars);

        if (isset($delete_vars['idImagen'])) {
            Imagen::borrarImagen($delete_vars['idImagen']);
            Funciones::borrarImagenFisica($delete_vars['pathImagen'], 
                                         $delete_vars['pathThumb']);
        }
        else if (isset($delete_vars['id'])) {
            Producto::delete($delete_vars['id']);
        }
        
        break;
        
    default:
        echo "Request Method NO reconocible";
}
?>