<?php
/*Author name: Lim Shuye*/
// Ensure form data has been sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the POST request
    $itemName = isset($_POST['itemName']) ? trim($_POST['itemName']) : '';
    $itemImage = isset($_POST['itemImage']) ? trim($_POST['itemImage']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
    $total = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;
    $supplierId = isset($_POST['supplierId']) ? trim($_POST['supplierId']) : '';
    $billingAddress = isset($_POST['billingAddress']) ? trim($_POST['billingAddress']) : '';
    $shippingAddress = isset($_POST['shippingAddress']) ? trim($_POST['shippingAddress']) : '';
    $shippingMethod = isset($_POST['shippingMethod']) ? trim($_POST['shippingMethod']) : '';
    $shippingDate = isset($_POST['shippingDate']) ? trim($_POST['shippingDate']) : '';
    $shippingTime = isset($_POST['shippingTime']) ? trim($_POST['shippingTime']) : '';
    $inventoryId = isset($_GET['inventoryId']) ? htmlspecialchars($_GET['inventoryId']) : null;
    $itemType = isset($_GET['itemType']) ? htmlspecialchars($_GET['itemType']) : null;
    $itemID = isset($_GET['itemID']) ? htmlspecialchars($_GET['itemID']) : null;

    // Ensure all required fields are present
    if (empty($supplierId) || empty($billingAddress) || empty($shippingAddress) || empty($shippingDate) || empty($shippingTime) || !in_array($itemType, ['Food', 'Habitat', 'Cleaning'])) {
        echo "<p class='alert'>Required fields are missing or invalid item type.</p>";
        exit();
    }

    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\Supplier.php';
    $supplier = new Supplier($supplierId);

    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\PurchaseOrder.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\PurchaseOrderLineItem.php';

    // Format the date and time
    $orderDate = date('Y-m-d H:i:s');
    $combinedDatetime = $shippingDate . ' ' . $shippingTime;

    try {
        $dateObj = DateTime::createFromFormat('Y-m-d', $shippingDate);
        $formattedDatetime = DateTime::createFromFormat('Y-m-d H:i', $combinedDatetime)->format('Y-m-d H:i:s');
    } catch (Exception $e) {
        echo "<p class='alert'>Error formatting dates: " . htmlspecialchars($e->getMessage()) . "</p>";
        exit();
    }

    // Instantiate control classes
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create the appropriate item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryId, $itemType, null, null);
    $inventory->setId($itemID);
    
    try {
        // Create a new Purchase Order
        $newPO = new PurchaseOrder($supplier, $orderDate, $formattedDatetime, $billingAddress, $shippingAddress, $total, "Draft");
        $newPOId = $newPO->addNewPO();

        if ($newPOId != null) {

            // Add Line Item to the Purchase Order
            $lineItem = $newPO->addLineItem($newPOId, $inventoryId, $quantity, $price, $inventory);
            $lineItem->addNewPOLine();
            // Redirect to success page
            header("Location: index.php?controller=inventory&action=showPO&status=successPO");
            exit();
        } else {
            // Redirect back with a failure message
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=POCreationFailed");
            exit();
        }
    } catch (Exception $e) {
        // Optionally redirect to an error page
        header("Location: index.php?controller=inventory&action=showPO&status=errorPO");
        exit();
    }
} else {
    echo "<p class='alert'>Invalid request method.</p>";
}
?>
