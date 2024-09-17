<?php

// Ensure form data has been sent via GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve values from the GET request
    $POid = isset($_GET['POid']) ? trim($_GET['POid']) : '';

    // Validate POid
    if (!empty($POid) && is_numeric($POid)) {
        include_once '../../Model/Inventory/PurchaseOrder.php';

        // Create an instance of the PurchaseOrder model
        $purchaseOrder = new PurchaseOrder(); // No arguments needed

        // Call a method to delete the purchase order
        $result = $purchaseOrder->updatePurchaseOrder($POid,"Pending");

        // Redirect based on the result
        if ($result) {
            header('Location: ../../Control/InventoryController/index.php?controller=inventory&action=showPO&status=updateSuccess');
        } else {
            header('Location: ../../Control/InventoryController/index.php?controller=inventory&action&status=updateError');
        }
        exit(); // Ensure no further code is executed after redirect
    } else {
        header('Location: ../../Control/InventoryController/index.php?controller=inventory&action=showPO&status=invalidPOid');
        exit();
    }
} else {
    header('Location: ../../Control/InventoryController/index.php?controller=inventory&action=showPO&status=invalidRequest');
    exit();
}
?>
