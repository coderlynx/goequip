<?php
require_once ('php/lib/mercadopago.php');


session_start();

if(!isset($_SESSION["nombre"]))	{
    echo "Tenes que estar logueado";
    exit;
    //header("Location: index.php");  //Si no hay sesión activa, lo direccionamos al index.php (inicio de sesión) 
}
    
   


if(isset($_SESSION['pedido']['total'])) {
    /** UNSERIALIZE **/
    $montoTotal =  $_SESSION['pedido']['total'];
    //$cantidadTotal =  $_SESSION['pedido']['cantidad'];

    $cliente = json_decode($_SESSION['pedido']['cliente']);

    //var_dump ($cliente);

    //Mis datos secretos Client_id y Client_secret
    $mp = new MP('5836268351908133', '8q3o4CY9gQKTx8LCz9clL4wQdMCBb1Zq');

    $mp->sandbox_mode(TRUE);
    //Get an existent Checkout preference
    //$preference = $mp->get_preference("110542");

    //print_r ($preference);

    //Create a Checkout preference
    $preference_data = array(
        "items" => array( 
            array(
                "id"=> "item-ID-1234",
                "title"=> "GO Equipamientos.",//Title of what you are paying for. It will be displayed in the payment process.
                "currency_id"=> "ARS",
                "picture_url"=> "http://localhost/coderlynx/goequip/goequip/img/logo.jpg",
                "description"=> "Equipamientos",
                "category_id"=> "art", // Available categories at https://api.mercadopago.com/item_categories
                "quantity"=> 1,//le tengo que dejar uno para que compute un total ya calculado por mi.
                "unit_price"=> $montoTotal          
                 )
        ),
        "payer" => array(
            array(
                "name"=> $cliente->nombre,
                "surname"=> $cliente->apellido,
                "email"=> $cliente->email,
                "date_created"=> date('Y/m/d H:i:s'),
                "phone"=>[
                    "area_code"=> "11",
                    "number"=> $cliente->telefono
                    ],
                "identification"=> [
                    "type"=> "DNI",
                    "number"=> $cliente->dni
                ],
                "address"=> [
                    "street_name"=> $cliente->domicilio->calle,
                    "street_number"=> $cliente->domicilio->numero,
                    "zip_code"=> $cliente->domicilio->cp
                ] 
            )
        ),                     
        "notification_url" => "http://www.hoysesale.club/wsparques/Notificacion.php",
        "external_reference" => "1"
        /*"auto_return" => "approved",
        "back_urls" => array(
             "failure" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php",
             "pending" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php",
             "success" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php"
             )*/
    );

    $preference = $mp->create_preference ($preference_data);

    //$access_token = $mp->get_access_token();//"APP_USR-5836268351908133-040920-10062c9f055f8bc1b369e188ef27c07b__E_F__-43258013";// $mp->get_access_token();

    //print_r ($access_token);

//$payment_info = $mp->get_payment_info('1146523287');

//if ($payment_info["status"] == 200) {
//	print_r($payment_info["response"]);
//}

//Search for payments
/*$filters = array (
	"payment_type" => "credit_card",
	"operation_type" => "regular_payment",
	"range" => "date_created",
	"begin_date" => "2014-10-21T00:00:00Z",
	"end_date" => "2014-10-25T24:00:00Z"
);

$search_result = $mp->search_payment ($filters, 0, 10);

print_r ($search_result);*/


//Get payment data
//$paymentInfo = $mp->get_payment ('1146523287');

//print_r ($paymentInfo);


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Outlet Gym</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="css/normalize.css" />
	<link rel="stylesheet" href="css/normalize.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/lib/modernizr-2.8.3-respond-1.4.2.min.js"></script>

	<!-- Google Font -->
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

	<!-- Font Awesome -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <!-- Estilos -->
	<link rel="stylesheet" href="css/estilos.css">

</head>
    <body>
       <main>
    <div class="contenedor">
    	<header id="header">  	</header>

	    <nav id="nav" class="navbar navbar-default">
		  
		</nav>

		<!-- /.CATEGORIA -->
		<div class="contenedor carrito">	
			<div class="container-fluid">
				<div class="row text-center">
					<h1><i class="fa fa-usd" aria-hidden="true"></i></i> Realizar el pago</h1>
<!--					<a href="<?php echo $preference['response']['sandbox_init_point']; ?>" name="MP-Checkout" class="blue-rn-m" onreturn="execute_my_onreturn">Comprar</a>-->
					<a href="<?php echo $preference['response']['sandbox_init_point']; ?>" name="MP-Checkout" class="btn btn-default btn-custom btn-pagar" role="button" onreturn="execute_my_onreturn">Pagar</a>
<!--					<a class="btn btn-default btn-custom btn-pagar" href="#" role="button">PAGAR</a>-->
				</div>
				<section class="row">
					<div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 detalle-carrito col-lg-push-4 col-md-push-4 col-sm-push-2">
						<article class="detalle-carrito-resumen">
							<h2><i class="fa fa-shopping-cart" aria-hidden="true"></i> Resúmen de tu compra:</h2>
							<div class="row">
								<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Cantidad:</span></p></div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p class="totalCantidad"> </p></div>

								<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Total:</span></p></div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p> $ <span class="totalPedido"></span></p></div>

								<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Envío:</span></p></div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p> $ <span class="totalEnvio"></span></p></div>

								<div class="col-lg-9 col-sm-9 col-xs-9"><p><span class="detalle-items">Total:</span></p></div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"><p class="precio"> $ <span class="totalPedidoFinal"></span></p></div>
							</div>
						</article>
					</div>
				</section>
			</div>
		</div>
    </div><!-- /.contenedor global -->
    
	<footer id="footer"></footer>
       
       
       
        
    
    
    </body>
    
    <?php include('scripts.html') ?>
    <script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>
    <script src="js/lib/jquery.js"></script>
	<script src="js/lib/bootstrap.min.js"></script>
    <script src="js/Pedido.js"></script>
    <script src="js/Carrito.js"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            
             //codigo repetido extraido
             $( "#header" ).load( "codigoComun.html #header", function() {
                Carrito.getTotal();
             }); 
            
             $( "#nav" ).load( "codigoComun.html #nav" );
             $( "#footer" ).load( "codigoComun.html #footer" );
             $( "#destacados" ).load( "codigoComun.html #destacados" );

		});
  
        
        
      
        function execute_my_onreturn (json) {
              /*{
    "back_url":"url-segun-failure-pending-o-success-configurada-en-la-preferencia-de-checkout" || null,
    "collection_id":"collection_id-creado-por-el-flujo-de-cobro" || null,
    "collection_status":"collection_status" || null,
    "external_reference":"external-reference-configurada-en-la-preferencia-de-checkout" || null,
    "preference_id":"preference-id-creado-en-la-preferencia-de-checkout"
}*/
            if (json.collection_status=='approved'){
                console.log("Pago acreditado");
                
                Pedido.insertPedido(json.collection_id, json.payment_type, json.collection_status);
            } else if(json.collection_status=='pending'){
                console.log("El usuario no completó el pago");
                //alert ('El usuario no completó el pago');
                Pedido.insertPedido(json.collection_id, json.payment_type, json.collection_status);
            } else if(json.collection_status=='in_process'){    
                alert ('El pago está siendo revisado');    
            } else if(json.collection_status=='rejected'){
                alert ('El pago fué rechazado. Se ha producido una incidencia en el pago y el pedido ha sido cancelado. Le rogamos que contacte con su entidad financiera para resolver la situación y realizar el nuevo pedido.');
            } else if(json.collection_status==null){
                alert ('El usuario no completó el proceso de pago, no se ha generado ningún pago');
            }
        }
    </script>
<?php
} else {
    echo "No hay productos en el carrito";
} 
    ?>
</html>

