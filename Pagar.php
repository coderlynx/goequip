<?php
require_once ('php/autoload.php');
require_once ('php/lib/mercadopago.php');

session_start();


if(isset($_SESSION['pedido'])) {
    /** UNSERIALIZE **/
   $montoTotal =  $_SESSION['pedido']['total'];
       
    //print_r ($carrito);

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
                "picture_url"=> "css/goequip_logo.gif",
                "description"=> "Equipamientos",
                "category_id"=> "art", // Available categories at https://api.mercadopago.com/item_categories
                "quantity"=> 1,
                "unit_price"=> $montoTotal          
                 )
        ),
        "payer" => array(
            array(
                "name"=> "user-name",
                "surname"=> "user-surname",
                "email"=> "user@email.com",
                "date_created"=> "2015-06-02T12:58:41.425-04:00",
                "phone"=>[
                    "area_code"=> "11",
                    "number"=> "4444-4444"
                    ],
                "identification"=> [
                    "type"=> "DNI",
                    "number"=> "12345678"
                ],
                "address"=> [
                    "street_name"=> "Street",
                    "street_number"=> 123,
                    "zip_code"=> "5700"
                ] 
            )
        ),
        "external_reference" => "1",
        "auto_return" => "approved",
        "back_urls" => array(
             "failure" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php",
             "pending" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php",
             "success" => "http://localhost/coderlynx/goequip/goequip/CompraFinalizada.php"
             )
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
        <title>Pagar</title>
       
    </head>
    <body>
        <a href="<?php echo $preference['response']['sandbox_init_point']; ?>" name="MP-Checkout" class="blue-rn-m" onreturn="execute_my_onreturn">Pagar</a>
        <script type="text/javascript" src="https://www.mercadopago.com/org-img/jsapi/mptools/buttons/render.js"></script>
    
    </body>
    
    <script type="text/javascript">
      
        function execute_my_onreturn (json) {
              /*{
    "back_url":"url-segun-failure-pending-o-success-configurada-en-la-preferencia-de-checkout" || null,
    "collection_id":"collection_id-creado-por-el-flujo-de-cobro" || null,
    "collection_status":"collection_status" || null,
    "external_reference":"external-reference-configurada-en-la-preferencia-de-checkout" || null,
    "preference_id":"preference-id-creado-en-la-preferencia-de-checkout"
}*/
            if (json.collection_status=='approved'){
                alert ('Pago acreditado');
                    $.ajax({
                          async:false,    
                          cache:false,   
                          type: 'POST', 
                          data: {info: jsonRta },
                          url: "php/controllerPedido.php",
                          success:  function(respuestaJson){  
                             var rta = JSON.parse(respuestaJson);
                               
                          },
                          error:function(objXMLHttpRequest){
                               console.log('Error al ejecutar la petición por:' + e);
                          }
                        });
            } else if(json.collection_status=='pending'){
                //alert ('El usuario no completó el pago');
                var jsonRta = JSON.stringify(json);
                        $.ajax({
                          async:false,    
                          cache:false,   
                          type: 'POST', 
                          data: {info: jsonRta },
                          url: "php/controllerPedido.php",
                          success:  function(respuestaJson){  
                             var rta = JSON.parse(respuestaJson);
                               
                          },
                          error:function(objXMLHttpRequest){
                               console.log('Error al ejecutar la petición por:' + e);
                          }
                        });
                /* $.post( "php/controllerPedido.php", { info: jsonRta }, function(responseJSON){
                    alert(responseJSON);
                    alert( "Pedido Creado");
                });*/
            } else if(json.collection_status=='in_process'){    
                alert ('El pago está siendo revisado');    
            } else if(json.collection_status=='rejected'){
                alert ('El pago fué rechazado, el usuario puede intentar nuevamente el pago');
            } else if(json.collection_status==null){
                alert ('El usuario no completó el proceso de pago, no se ha generado ningún pago');
            }
        }
    </script>
<?php
} else {
    echo "Sesion vencida";
}
    ?>
</html>

