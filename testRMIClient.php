<?php

$itemId = 'Client item123';
$quantityToReorder = 10; // Example quantity to reorder

// URL of the Spring Boot web service endpoint
$url = 'http://localhost:8080/api/reorder';

// Data to send in the request
$data = array(
    'itemId' => $itemId,
    'quantity' => $quantityToReorder
);

// Use curl to send a POST request to the web service
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

$response = curl_exec($ch);

if ($response === false) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'Response from web service: ' . $response;
}

curl_close($ch);




//$supplierEmail = 'mei.ling@example.com';
//$url = 'http://localhost:8080/checkStock?supplierEmail=' . urlencode($supplierEmail);
//
//$response = file_get_contents($url);
//
//if ($response === FALSE) {
//    echo "Failed to connect to the RMI service.";
//} else {
//    echo "Response from RMI service: " . $response;
//}
?>
