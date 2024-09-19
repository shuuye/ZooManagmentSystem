<?php

require_once '../Model/Inventory/PurchaseOrder.php';
//https://localhost/ZooManagementSystem/public/apiWebService.php?email=mei.ling@example.com
header("Content-Type:application/json");

if (!empty($_GET['email'])) {
    $email = $_GET['email'];
    $purchaseOrder = new PurchaseOrder();
    $PO = $purchaseOrder->gePODetailsDB($email);

    if (empty($PO)) {
        response(200, "PO Found", NULL);
    } else {
        response(200, "PO Found", $PO);
    }
} else {
    response(400, "Invalid Request", NULL);
}

function response($status, $status_message, $data) {
    header("HTTP/1.1 " . $status);

    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response ['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}



?>