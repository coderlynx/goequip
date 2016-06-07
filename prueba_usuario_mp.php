<?php
/*require_once ('php/lib/mercadopago.php');
 $mp = new MP('5836268351908133', '8q3o4CY9gQKTx8LCz9clL4wQdMCBb1Zq');
//$access_token = $mp->get_access_token();

$access_token = "APP_USR-5836268351908133-040912-b5ef7edd175e5f4ee76ce21cc89ef467__LD_LA__-43258013";

$url ="https://api.mercadolibre.com/users/test_user?access_token=".$access_token;
$valor1 = "MLA";

$parametros_post = json_encode(array(
    "site_id" => $valor1
    ));

$sesion = curl_init($url);

curl_setopt($sesion, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Type:  application/x-www-form-urlencoded"));


// definir tipo de petici&oacute;n a realizar: POST
curl_setopt ($sesion, CURLOPT_POST, true);
// Le pasamos los par&aacute;metros definidos anteriormente
curl_setopt ($sesion, CURLOPT_POSTFIELDS, $parametros_post);
// s&oacute;lo queremos que nos devuelva la respuesta
curl_setopt($sesion, CURLOPT_HEADER, false);
curl_setopt($sesion, CURLOPT_RETURNTRANSFER, true);
// ejecutamos la petici&oacute;n
$respuesta = curl_exec($sesion);
// cerramos conexi&oacute;n
curl_close($sesion);


echo $respuesta;

echo "aaa";

$usuario = json_decode($respuesta);*/





// add your access token
$access_token = "APP_USR-5836268351908133-040912-b5ef7edd175e5f4ee76ce21cc89ef467__LD_LA__-43258013";
$data = array("site_id" => "MLA", "type" => "income");

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_URL,"https://api.mercadopago.com/mercadopago_account/movements/search?access_token=$access_token");
//curl_setopt($ch, CURLOPT_URL,"https://api.mercadopago.com/users/test_user?access_token=$access_token");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

$response = curl_exec($ch);

// json response    
echo $response;

/*echo $usuario->id;
echo "\n";
echo $usuario->site_status;
echo "\n";
echo $usuario->nickname;
echo "\n";
echo $usuario->email;
echo "\n";
echo $usuario->password;
echo "\n";*/
?>