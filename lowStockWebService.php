<?php

require_once 'Config/databaseConfig.php';
$poId = 2;
try {
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
            po.poId = ? AND
            po.status = 'Pending'";

    $stmt = $db->getConnection()->prepare($query);
    $stmt->execute(array(31)); //$poId
    $lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function sendPOtoSupplier($lowStockItems) {
    $url = 'http://localhost:8080/api/reorder';

    // Data to send in the request
    $data = array();
    while ($row = $lowStockItems) {
        $data[] = $row;
    }

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
}


sendPOtoSupplier($lowStockItems);