<?php

require_once 'Config/databaseConfig.php';
try {
    $db = new databaseConfig();

    $query = "
            SELECT 
        s.supplierId,
        s.supplierName,
        i.itemName,
        i.quantity AS currentQuantity,
        b.quantity AS batchQuantity,
        b.manufactureDate,
        b.expiryDate,
        po.poId,
        po.orderDate,
        po.status
    FROM 
        Supplier s
    JOIN 
        supplierRecord sr ON s.supplierId = sr.supplierId
    JOIN 
        Inventory i ON sr.inventoryId = i.inventoryId
    LEFT JOIN 
        batch b ON i.inventoryId = b.inventoryId
    JOIN 
        purchaseorderlineitem poli ON sr.inventoryId = poli.inventoryId
    JOIN 
        purchaseorder po ON poli.poId = po.poId
    WHERE 
        i.quantity < i.reorderThreshold
        OR (i.quantity - COALESCE(b.quantity, 0)) < i.reorderThreshold
    ORDER BY 
        s.supplierId, po.orderDate DESC;";
    $stmt = $db->getConnection()->prepare($query);
    $stmt->execute();
    $lowStockItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

function sendRestockingAlert($supplierId, $itemName, $quantity) {
    $url = "http://localhost:8080/RMI/restocking";

    $response = file_get_contents($url);

    if ($response === FALSE) {
        echo "Failed to connect to $url";
    } else {
        echo "Successfully connected to $url";
    }

    $data = [
        'supplierId' => $supplierId,
        'itemName' => $itemName,
        'quantity' => $quantity
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
// Handle error
        echo "Error sending restocking alert.";
    }

// Process the response from the Java web service if needed
    echo $result;
}

// Loop through the low stock items and call the sendRestockingAlert function
foreach ($lowStockItems as $item) {
    sendRestockingAlert($item['supplierId'], $item['itemName'], $item['currentQuantity']);
}
?>