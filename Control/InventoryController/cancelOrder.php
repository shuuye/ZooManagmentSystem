<?php
/*Author name: Lim Shuye*/
// Ensure form data has been sent via GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve values from the GET request
    $POid = isset($_GET['POid']) ? trim($_GET['POid']) : '';

    // Validate POid
    if (!empty($POid) && is_numeric($POid)) {
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\PurchaseOrder.php';

        // Create an instance of the PurchaseOrder model
        $purchaseOrder = new PurchaseOrder(); // No arguments needed

        // Call a method to delete the purchase order
        $result = $purchaseOrder->deletePurchaseOrder($POid);

        // Redirect based on the result
        if ($result) {
            header('Location: index.php?controller=inventory&action=showPO&status=success');
        } else {
            header('Location: index.php?controller=inventory&action&status=error');
        }
        exit(); // Ensure no further code is executed after redirect
    } else {
        header('Location: index.php?controller=inventory&action=showPO&status=invalidPOid');
        exit();
    }
} else {
    header('Location: index.php?controller=inventory&action=showPO&status=invalidRequest');
    exit();
}
?>
