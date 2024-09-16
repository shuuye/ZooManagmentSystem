<?php

require_once '../Config/databaseConfig.php';
//https://localhost/ZooManagementSystem/apiWebService.php?email=mei.ling@example.com
header("Content-Type:application/json");

if (!empty($_GET['email'])) {
    $email = $_GET['email'];
    $PO = getPODetails($email);

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

function getPODetails($email) {

    $db = new databaseConfig();

    $query = "
        SELECT 
            po.poId,
            po.orderDate,
            po.deliveryDate,
            po.totalAmount,
            po.status,
            poli.poLineItemId,
            poli.inventoryId,
            poli.cleaningId,
            poli.habitatId,
            poli.foodId,
            poli.quantity,
            poli.unitPrice
        FROM 
            purchaseorder po
        JOIN 
            purchaseorderlineitem poli ON po.poId = poli.poId
        JOIN 
            supplier s ON po.supplierId = s.supplierId
        WHERE 
            s.contactEmail = ?";
    $result = $db->getConnection()->prepare($query);

    $result->execute(array($email));

    // Fetch all results into an array of objects
    $data = array();
    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        $data[] = $row;
    }

    return $data;
}

?>