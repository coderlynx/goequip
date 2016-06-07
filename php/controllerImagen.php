<?php
require_once 'autoload.php';

session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);
switch ($metodo) {
    case 'get':
        break;
    case 'post':   
        // Si se cargaron imágenes, almaceno el array retornado con las rutas
        if (!(empty($_FILES))) {
            require_once "modelo".DIRECTORY_SEPARATOR."endpoint.php";
            
            $orden = 0;
            $rutasImagenes = $uploader->rutasImagenes;
            
            if (!isset($_SESSION['idProducto'])) {
                
            }
            
            $imagen = new Imagen($rutasImagenes, $_SESSION['idProducto'], $orden);
            
            if ($result = Imagen::insert($imagen)) {
                echo json_encode($result);
                //echo json_encode("Imagen insertada con éxito.");
                exit;
            } else {
                //echo json_encode("No se han podido insertar las imágenes.");
            }
        } else {
            //echo json_encode("No hay imágenes cargadas.");
        }
        break;
    case 'put':
        break;
    case 'delete':        
        break; 
    default:
        //echo json_encode("Request Method NO reconocible.");
}
?>
