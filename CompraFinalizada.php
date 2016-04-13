<?php
require_once ('php/autoload.php');
session_start();

//$payment_info = $mp->get_payment_info($_GET["collection_id"]);
/*
if ($payment_info["status"] == 200) {
    print_r("Imprimo get_payment_info");
	print_r($payment_info["response"]);
}*/

// Get the payment and the corresponding merchant_order reported by the IPN.
/*if($_GET["topic"] == 'payment'){
	$payment_info = $mp->get("/collections/notifications/" . $_GET["id"]);
	$merchant_order_info = $mp->get("/merchant_orders/" . $payment_info["response"]["collection"]["merchant_order_id"]);
// Get the merchant_order reported by the IPN.
} else if($_GET["topic"] == 'merchant_order'){
	$merchant_order_info = $mp->get("/merchant_orders/" . $_GET["id"]);
}

if ($merchant_order_info["status"] == 200) {
	// If the payment's transaction amount is equal (or bigger) than the merchant_order's amount you can release your items 
	$paid_amount = 0;

	foreach ($merchant_order_info["response"]["payments"] as  $payment) {
		if ($payment['status'] == 'approved'){
			$paid_amount += $payment['transaction_amount'];
		}	
	}

	if($paid_amount >= $merchant_order_info["response"]["total_amount"]){
		if(count($merchant_order_info["response"]["shipments"]) > 0) { // The merchant_order has shipments
			if($merchant_order_info["response"]["shipments"][0]["status"] == "ready_to_ship"){
				print_r("Totally paid. Print the label and release your item.");
			}
		} else { // The merchant_order don't has any shipments
			print_r("Totally paid. Release your item.");
		}
	} else {
		print_r("Not paid yet. Do not release your item.");
	}
}*/
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Compra Finalizada</title>
       
    </head>
    <body>
       
       <h1>Felicitaciones!! Su compra ha finalizado.</h1>
       <h2>Su numero de Pedido es: <span id="nroPedido"></span></h2>
       <?php include('scripts.html') ?>
       
     <script>
    
   $( document ).ready(function() {
       
       $("#nroPedido").html(GetURLParameter('nroPedido'))
    
        function GetURLParameter(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }
            }
        }

    });
        


    
    </script>
    </body>
    
   
</html>