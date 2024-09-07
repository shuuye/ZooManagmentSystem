<?php

// Ensure form data has been sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST request
    $itemName = isset($_POST['itemName']) ? $_POST['itemName'] : '';
    $itemImage = isset($_POST['itemImage']) ? $_POST['itemImage'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;
    $supplierId = isset($_POST['supplierId']) ? $_POST['supplierId'] : '';
    $billingAddress = isset($_POST['billingAddress']) ? $_POST['billingAddress'] : '';
    $shippingAddress = isset($_POST['shippingAddress']) ? $_POST['shippingAddress'] : '';
    $shippingMethod = isset($_POST['shippingMethod']) ? $_POST['shippingMethod'] : '';
    $shippingDate = isset($_POST['shippingDate']) ? $_POST['shippingDate'] : '';
    $inventoryId = isset($_GET['inventoryId']) ? htmlspecialchars($_GET['inventoryId']) : null;
    $itemType = isset($_GET['itemType']) ? htmlspecialchars($_GET['itemType']) : null;
    $itemID = isset($_GET['itemID']) ? htmlspecialchars($_GET['itemID']) : null;

    include_once '../../Model/Inventory/PurchaseOrder.php';
    include_once '../../Model/Inventory/PurchaseOrderLineItem.php';

    $orderDate = date('Y-m-d H:i:s');
    $foodId = null;
    $habitatId = null;
    $cleaningId = null;
    switch ($itemType) {
        case 'Food':
            $foodId = $itemID;
            break;
        case 'Habitat':
            $habitatId = $itemID;
            break;
        case 'Cleaning':
            $cleaningId = $itemID;
            break;
        default:
            throw new Exception("Unknown itemType: $itemType");
    }

    $newPO = new PurchaseOrder($supplierId, $orderDate, $shippingDate, $total, "Draft");
    $newPOId = $newPO->addNewPO();
    $lineItem = $newPO->addLineItem($newPOId, $inventoryId, $quantity, $price, $cleaningId, $habitatId, $foodId);
    $lineItem->addNewPOLine();

//     $data = [
//            'POid' => $POid,
//            'itemName' => $itemName,
//            'itemID' => $itemID,
//            'price' => $price,
//            'supplierDetails' => $supplierDetails,
//            'image' => "../../assests/InventoryImages/" . $itemType . "_" . $inventoryId . "_" . $itemID . ".jpg",
//            'activePage' => 'Inventory Management',
//            'pageCss' => 'purchaseorder.css',
//            'xslt_transform' => false
//        ];
} else {
    echo "<p>Invalid request method.</p>";
}
?>
