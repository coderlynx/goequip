<?php
require_once 'autoload.php';
session_start();


//EXTRAER el metodo de la peticion (GET,POST,PUT,DELETE)
$metodo = strtolower($_SERVER['REQUEST_METHOD']);

// Filtrar método
switch ($metodo) {
    case 'get':
        $nombreUsuario = 'Invitado';
        if(isset($_SESSION['nombre']))
            $nombreUsuario = $_SESSION['nombre'];
        
        echo $nombreUsuario;
        break;
    case 'post':
        if(isset($_POST['login'])) {
            $login = json_decode($_POST['login']);
            //$log = new Login($login->nombre, $login->password);
            $usuario = Usuario::soloMailYClave($login->email, $login->password);

            $validator = new Validator;

            $validator->validate($usuario, Usuario::$reglasLogin);

            if($validator->tuvoExito() && Usuario::login($usuario)) {

                echo json_encode("exito");
                exit;
            }
            echo json_encode($validator->getErrores());
            break;
        }
        
        if(isset($_POST['registro'])) {
            $usuario = json_decode($_POST['registro']);
            //$reg = new Registro($registro->nombre, $registro->password, $registro->mail, $registro->perfil);

            $usuario = new Usuario(null, $usuario->nombre, $usuario->password, 2,$usuario->mail,null);

            $validator = new Validator;

            $validator->validate($usuario, Usuario::$reglasRegitrar);

            if($validator->tuvoExito() && Usuario::registrar($usuario)) {

                echo json_encode("exito");
                exit;
            }

            echo json_encode(Usuario::$MENSAJE);

            echo json_encode($validator->getErrores());
            break;
        }
        break;
    case 'put':
        parse_str(file_get_contents("php://input"),$put_vars);
        
        if(isset($put_vars['recuperarMail']))
            $mail = $put_vars['recuperarMail'];
        
        if(isset($put_vars['id']))
            $hashEnviado = $put_vars['id'];
        if(isset($put_vars['mailURL']))
            $mailURL = $put_vars['mailURL'];
        if(isset($put_vars['passNuevo']))
            $passNuevo = $put_vars['passNuevo'];
        
        if(isset($mail)) {	
		    //$mail = $_POST['recuperarMail'];
            
            $fila = Usuario::validoSiExisteUsuario($mail);
            
            if(!$fila)
                die('Mail inexistente.');

            $hash = md5(md5($fila['nombre']).md5($fila['password']));

            $headers = "From:Recuperar password <recuperar@outletgym.com>\r\n";  
            $message = "Para recuperar tu clave debe hacerle click en el link de abajo.
            http://outletgym.ws58.host4g.com/cambioConClave.html?id=".$hash."&mail=".$mail;

            if (mail($mail,"Recuperar password",$message,$headers)){
                echo 'ok';
                break;
            } else {
                die('No se pudo enviar el mail');
            }
            break;
        } else if(isset($passNuevo)) {	
            //$hashEnviado = $_POST['id'];
            //$mail = $_POST['mail'];
            //$pass = $_POST['passNuevo'];
            
	
            if(isset($hashEnviado) && isset($mailURL)){
                
                $fila = Usuario::validoSiExisteUsuario($mailURL);
            
                if(!$fila)
                    die('Mail inexistente.');

                echo Usuario::actualizarPassword($hashEnviado,$passNuevo,$fila);
                break; 
            }
	            echo 'Parametros no seteados en la url';
                break;
        }
        break;
    case 'delete':        
        $_SESSION = array(); 
        session_unset();
	    session_destroy();
	    echo json_encode($_SESSION);

        break;

    default:
        echo 'Metodo no reconocible';
}

	/*
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $_SESSION = array(); 
    session_unset();
	session_destroy();
	echo json_encode($_SESSION);
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    
    $login = json_decode($_POST['login']);
    //$log = new Login($login->nombre, $login->password);
    $usuario = Usuario::soloMailYClave($login->email, $login->password);

    $validator = new Validator;

    $validator->validate($usuario, Usuario::$reglasLogin);

    if($validator->tuvoExito() && Usuario::login($usuario)) {

        echo json_encode("exito");
        exit;
    }

    echo json_encode($validator->getErrores());
	
    
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registro'])) {
    
    $usuario = json_decode($_POST['registro']);
	//$reg = new Registro($registro->nombre, $registro->password, $registro->mail, $registro->perfil);

    $usuario = new Usuario(null, $usuario->nombre, $usuario->password, 2,$usuario->mail,null);
    
	$validator = new Validator;

	$validator->validate($usuario, Usuario::$reglasRegitrar);

	if($validator->tuvoExito() && Usuario::registrar($usuario)) {
		
		echo json_encode("exito");
		exit;
	}

    echo json_encode(Usuario::$MENSAJE);
    
	echo json_encode($validator->getErrores());

    
}*/

?>