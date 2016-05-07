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
        $rutasImagenes = array();
        // Si se cargaron imágenes, almaceno el array retornado con las rutas
        if(!(empty($_FILES))) {
            $rutasImagenes = moverImagenes();
        }
        
        $prod = json_decode($_POST['producto']);
        $producto = new Producto($prod->Id, $prod->Modelo, $prod->Descripcion, $prod->Categoria, $prod->Talle, $prod->Color, $prod->Stock, $prod->Precio, $rutasImagenes);

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

// Valida formato, mueve imágenes y retorna un array con las rutas
function moverImagenes() {
    // Creo un array para almacenar las rutas
    $rutasImagenes = array();
    // Creo un array para almacenar errores
    $error = array();
    // Creo un array con los formatos permitidos
    $extensions_allowed = array("jpeg", "jpg", "png", "gif");
    
    foreach($_FILES["files"]["tmp_name"] as $key => $tmp_name){
        // ["name"] --> nombre archivo + extensión
        $file_name = $_FILES["files"]["name"][$key];
        // ["tmp_name"] --> ubicación temporal donde se almacena
        $file_tmp = $_FILES["files"]["tmp_name"][$key];
        // Extraigo la extensión del archivo
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        // Carpeta destino
        $destination = "..\\img\\productos\\";
        
        // Si el formato de la extensión del archivo está permitido...
        if(in_array($file_ext, $extensions_allowed)){
            // Si el archivo no existe en la carpeta entonces lo muevo del temporal
            if(!file_exists("$destination".$file_name)){
                move_uploaded_file($file_tmp, "$destination".$file_name);
                array_push($rutasImagenes, "$destination".$file_name);
            }else{
                // Obtengo el componente de la ruta, sin la extensión
                $filename = basename($file_name, $file_ext);
                // Creo una copia del archivo con la fecha unix actual
                $newFileName = $filename.time().".".$file_ext;
                // Muevo el archivo a la carpeta destino
                move_uploaded_file($file_tmp = $_FILES["files"]["tmp_name"][$key],"$destination".$newFileName);
                array_push($rutasImagenes, "$destination".$newFileName);
            }
        }else{
            // Si el archivo no tiene un formato valido, almaceno el archivo en el array 'error'
            // En principio no lo retorno...
            array_push($error, "$file_name, ");
        }
    }
    return $rutasImagenes;
}
