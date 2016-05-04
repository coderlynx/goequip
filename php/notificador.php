<?php
//require_once 'autoload.php';
require_once "lib/mercadopago.php";
session_start();

$mp = new MP ("5836268351908133", "8q3o4CY9gQKTx8LCz9clL4wQdMCBb1Zq");
$mp->sandbox_mode(false);

//$db = DBConnection::getConnection();
// VAMOS A BUSCAR A MP LOS DATOS DE LA OPERACION
try
{
	$payment_info = $mp->get_payment_info($_GET["id"]);
	$data = $payment_info["response"]["collection"];
    $status = $_GET["status_detail"];


	// Ahora vamos a guardar que este cliente pago el servicio $servicio.

	## Consulto la base de datos a ver si tengo guardado el ID del pago.
    /*$query = "SELECT count(*) as cantidad from pagos where collection_id = :collection_id";

    $stmt = DBConnection::getStatement($query);
    
    $stmt->bindParam(':collection_id', $data["id"],PDO::PARAM_INT);
    
    if(!$stmt->execute()) {
        throw new Exception("Error en buscar el pago.");
    }
    
    while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {

       if ($row["cantidad"] == 0) {
           //EN ESTE CASO DAR DE ALTA EN NUESTRA DB el registro del pago y asociar con lo que haga falta.
            $query = "INSERT INTO pagos (collection_id, estado, idPedido, fecha)
				VALUES(:collection_id, :estado, :idPedido, :fecha)";
           
            $stmt = DBConnection::getStatement($query);
    
            $fecha = date('Y/m/d H:i:s');
            $stmt->bindParam(':collection_id', $data,PDO::PARAM_INT);
            $stmt->bindParam(':estado', $status,PDO::PARAM_STR);
            $stmt->bindParam(':idPedido', $data,PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha,PDO::PARAM_STR);
       } else {
           ## ESTE ES IMPORTANTE
            ## SI EL ID DEL PAGO YA LO TENIAMOS REGISTRADO, ENTONCES ES QUE ####EXISTIO#### UN CAMBIO DE ESTADO ( $data["status"] y $data["status_detail"] lo explican ) 
            ## HACER EL PROCESO QUE SEA NECESARIO.

            /*EJ: SI un usuario nos paga por pagofacil, cuando extrae el cupon nos llega una notificacion y cuando se acredita el dinero de pagofacil nos llega otra.
            $query = "INSERT INTO pagos (collection_id, estado, idPedido, fecha)
				VALUES(:collection_id, :estado, :idPedido, :fecha)";
           
            $stmt = DBConnection::getStatement($query);
    
            $fecha = date('Y/m/d H:i:s');
            $stmt->bindParam(':collection_id', $data,PDO::PARAM_INT);
            $stmt->bindParam(':estado', $status,PDO::PARAM_STR);
            $stmt->bindParam(':idPedido', $data,PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $fecha,PDO::PARAM_STR);
           
       }*/

   // }
    
	//$sql = "select count(*) as cantidad from pagos where collection_id = '".$data["id"]."' ";
	//$rs = $conn->execute($sql);

	//if($rs->field("cantidad")==0)
	//{
		// EN ESTE CASO DAR DE ALTA EN NUESTRA DB el registro del pago y asociar con lo que haga falta.
	//}else
	//{
		## ESTE ES IMPORTANTE
		## SI EL ID DEL PAGO YA LO TENIAMOS REGISTRADO, ENTONCES ES QUE ####EXISTIO#### UN CAMBIO DE ESTADO ( $data["status"] y $data["status_detail"] lo explican ) 
		## HACER EL PROCESO QUE SEA NECESARIO.

	//	EJ: SI un usuario nos paga por pagofacil, cuando extrae el cupon nos llega una notificacion y cuando se acredita el dinero de pagofacil nos llega otra.
	//}
    
    
    //print_r($data);
    //header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
	//die();

	## NOTIFICAMOS VIA EMAIL QUE TODO ESTA BIEN Y QUE SALIO TODO COMO QUERIAMOS ( Y el dinero esta en nuestras cuentas ya )
    // the message
    $msg = print_r($data,1);
    // send email
    mail("fernandocaino84@gmail.com","My subject",$msg);

	/*$sendmail = new PHPMailer();
	$sendmail->IsHTML(false);
	$sendmail->IsSMTP();
	$sendmail->SMTPAuth = true;
	$sendmail->Host = ""; // SMTP a utilizar. Por ej. smtp.elserver.com
	$sendmail->Username = ""; // Correo completo a utilizar
	$sendmail->Password = ""; // Contraseña
	$sendmail->Port = 25; // Puerto a utilizar	

	$sendmail->From  = "";
	$sendmail->FromName = "";
	$sendmail->AddAddress("");	 // 
	
	$sendmail->Subject = "Notificacion IPN - PAGO EXTERNO PROCESADO".;		
	$sendmail->Body    = print_r($data,1);
	
	$sendmail->Send();

	## IMPORTANTE - ENVIAMOS 200 OK para que MP registre que ya lo procesamos y no intente avisarnos nuevamente este movimiento.
    */
	header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
	die();

}catch(Exception $e)
{

	// GENERALMENTE SI SALTA ESTA EXCEPTION ES POR TEMAS DE CONECTIVIDAD CON LOS SERVIDORES DE MP, asi que es que se intento hacer el proceso pero no pudo conectar.
	// ENVIAMOS UN 500 para que el servidor de MP intente notificar de nuevo, y avisamos al encargado para que este atento al problema, se envian los datos de
	// notificacion recibidas y la exception producida.

	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
    
    $msg = "GET:\n".print_r($_GET,1)."\n".$e->getMessage();
    // send email
    mail("fernandocaino84@gmail.com","My subject",$msg);
    
	/*$sendmail = new PHPMailer();
	$sendmail->IsHTML(false);
	$sendmail->IsSMTP();
	$sendmail->SMTPAuth = true;
	$sendmail->Host = ""; // SMTP a utilizar. Por ej. smtp.elserver.com
	$sendmail->Username = ""; // Correo completo a utilizar
	$sendmail->Password = ""; // Contraseña
	$sendmail->Port = 25; // Puerto a utilizar	

	$sendmail->From  = "";
	$sendmail->FromName = "";
	$sendmail->AddAddress("");	 // 

	
	$sendmail->Subject = "Notificacion IPN - Exception";		
	$sendmail->Body    = "GET:\n".print_r($_GET,1)."\n".$e->getMessage();
	$sendmail->Send();*/

	die();
}


// SI LLEGA A EST PUNTO, ES que no fue ni una exception ni fue procesado, asi que es un IPN que no tiene registro en mi sistema.
// ESTO ES que existio un movimiento en la cuenta ( un pago ) pero nuestro sistema no es capaz de reconocerlo como tal. Aqui se envia 
//un mail al responsable para que evalue porque el sistema no lo registra.
header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

$msg = "GET:\n".print_r($_GET,1)."\n"."SERVER:\n".print_r($_SERVER,1);
// send email
mail("fernandocaino84@gmail.com","My subject",$msg);

/*$sendmail = new PHPMailer();
$sendmail->IsHTML(false);
$sendmail->IsSMTP();
$sendmail->SMTPAuth = true;
$sendmail->Host = ""; // SMTP a utilizar. Por ej. smtp.elserver.com
$sendmail->Username = ""; // Correo completo a utilizar
$sendmail->Password = ""; // Contraseña
$sendmail->Port = 25; // Puerto a utilizar	

$sendmail->From  = "";
$sendmail->FromName = "";
$sendmail->AddAddress("");	 // 

$sendmail->Subject = "Notificacion IPN - FINAL SIN PROCESO";		
$sendmail->Body    = "GET:\n".print_r($_GET,1)."\n"."SERVER:\n".print_r($_SERVER,1);

$sendmail->Send();*/


die();

?>