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
        //$mp->sandbox_mode(FALSE);
        
        // Sets the filters you want
        $filters = array(
            "status" => "approved",
            "operation_type" => "regular_payment"
        );
        // Search payment data according to filters
        $searchResult = $mp->search_payment($filters);
        // Show payment information
        ?>
        <table border='1'>
            <tr><th>id</th><th>site_id</th><th>date_created</th><th>operation_type</th><th>external_reference</th></tr>
            <?php
            foreach ($searchResult["response"]["results"] as $payment) {
                ?>
                <tr>
                    <td><?php echo $payment["collection"]["id"]; ?></td>
                    <td><?php echo $payment["collection"]["site_id"]; ?></td>
                    <td><?php echo $payment["collection"]["date_created"]; ?></td>
                    <td><?php echo $payment["collection"]["operation_type"]; ?></td>
                    <td><?php echo $payment["collection"]["external_reference"]; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>
