<?php
require_once 'autoload.php';
session_start();


	
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

    
}

