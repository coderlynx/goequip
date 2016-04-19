<?php
require_once 'autoload.php';
require_once ('lib/mercadopago.php');
session_start();

//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        
 
        break;
    case 'post':
        
        $filtros = json_decode($_POST['filtros']);
        
        //debemos realizar una generalización de métodos a través de la función call_user_func(), ya que sabemos que metodo se llamo. Con esto evitamos usar estructuras de decisión y solo llamamos el método de la clase necesitada.
        $datos = call_user_func(array('reporte', $filtros->tipo), $filtros);
           
        //$filtros->fechaDesde = '2016-04-13 00:00:00.000';
        
        //$datos = Reporte::getVentasMensuales($filtros);
        
        echo json_encode($datos);
           
        break;
    case 'put':
    case 'delete':
       
        break;
    default:
        echo 'Metodo no reconocible';

}


?>