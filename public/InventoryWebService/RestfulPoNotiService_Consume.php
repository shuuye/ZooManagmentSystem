<?php

require_once '../../Config/databaseConfig.php';

class RestfulPoNotiService_Consume extends databaseConfig {

    private $apiUrl = 'http://localhost:8080/api/reorder'; // API URL

    public function __construct() {
        
    }

    // Method to send PO to the supplier via web service
    public function sendPOtoSupplier($poId, $itemName, $quantity, $orderDate, $shippingDate, $shippingAddress, $total) {
        $url = $this->apiUrl;

        // Data to send in the POST request
        $data = array(
            'poId' => $poId,
            'itemName' => $itemName,
            'quantity' => $quantity,
            'orderDate' => $orderDate,
            'shippingDate' => $shippingDate,
            'shippingAddress' => $shippingAddress,
            'total' => $total
        );

        // Use curl to send the POST request to the web service
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors and handle the response
        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            echo 'Response from web service: ' . $response;
        }

        curl_close($ch);
    }
}

//// Example usage
//$poId = 2;
//$itemName = 'Client item123';
//$quantityToReorder = 10;
//
//$service = new RestfulPoNotiService_Consume();
//$service->sendPOtoSupplier($poId, $itemName, $quantityToReorder);
?>
