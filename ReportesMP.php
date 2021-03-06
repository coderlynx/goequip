<!doctype html>
<html>
    <head>
        <title>Search payments</title>
    </head>
    <body>
        <?php
        /**
         * MercadoPago SDK
         * Search payments
         * @date 2012/03/29
         * @author hcasatti
         */
        // Include Mercadopago library
       require_once ('php/lib/mercadopago.php');
        // Create an instance with your MercadoPago credentials (CLIENT_ID and CLIENT_SECRET): 
        // Argentina: https://www.mercadopago.com/mla/herramientas/aplicaciones 
        // Brasil: https://www.mercadopago.com/mlb/ferramentas/aplicacoes
        // Mexico: https://www.mercadopago.com/mlm/herramientas/aplicaciones 
        // Venezuela: https://www.mercadopago.com/mlv/herramientas/aplicaciones 
        $mp = new MP("5836268351908133", "8q3o4CY9gQKTx8LCz9clL4wQdMCBb1Zq");
        $mp->sandbox_mode(false);

        $payment_info = $mp->get_payment_info('1972640425');
        $data = $payment_info["response"]["collection"];
        //$status = $_GET["status_detail"];
        
        //print_r($data);
        //var_dump($data['status_detail']);
        //print_r($status);
        
        // Sets the filters you want
        $filters = array(
            "id" => 1972640425
            
        );
        // Search payment data according to filters
        $searchResult = $mp->search_payment($filters);
        // Show payment information
        print_r($searchResult);
        $balance = $mp->get ("/users/43258013/mercadopago_account/balance");

        print_r ($balance);
        
        ?>
        <table border='1'>
            <tr><th>id</th><th>site_id</th><th>date_created</th><th>operation_type</th><th>external_reference</th><th>status</th><th>Pago</th><th>Pago a recibir</th><th>tipo</th></tr>
            <?php
            //print_r($searchResult);
            //foreach ($searchResult["response"]["results"] as $payment) {
                ?>
                <tr>
                    <td><?php echo $data["id"]; ?></td>
                    <td><?php echo $data["site_id"]; ?></td>
                    <td><?php echo $data["date_created"]; ?></td>
                    <td><?php echo $data["operation_type"]; ?></td>
                    <td><?php echo $data["external_reference"]; ?></td>
                    <td><?php echo $data["status"] . ': ' . $data["status_detail"]; ?></td>
                    <td><?php echo $data["total_paid_amount"]; ?></td>
                    <td><?php echo $data["net_received_amount"]; ?></td>
                    <td><?php echo $data["payment_type"]; ?></td>
                </tr>
                <?php
           // }
            ?>
        </table>
        
        <table border='1'>
            <tr><th>id</th><th>site_id</th><th>date_created</th><th>operation_type</th><th>external_reference</th></tr>
            <?php
            //print_r($searchResult);
            //foreach ($searchResult["response"]["results"] as $payment) {
                ?>
                <tr>
                    <td><?php echo $payment["collection"]["id"]; ?></td>
                    <td><?php echo $payment["collection"]["site_id"]; ?></td>
                    <td><?php echo $payment["collection"]["date_created"]; ?></td>
                    <td><?php echo $payment["collection"]["operation_type"]; ?></td>
                    <td><?php echo $payment["collection"]["external_reference"]; ?></td>
                </tr>
                <?php
           // }
            ?>
        </table>
    </body>
</html>
