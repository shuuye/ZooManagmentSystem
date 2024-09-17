<?php

if (isset($_GET['inventoryId']) && isset($_GET['itemId']) && $itemType = $_GET['itemType']) {
    $inventoryId = $_GET['inventoryId'];
    $itemId = $_GET['itemId'];
    $itemType = $_GET['itemType'];

    // Instantiate control class
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryId, $itemType, $itemID, Null);

    // Call method to get item details
    $result = $inventory->getRecordDetails($inventoryId, $itemId);

    // Output result
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Missing parameters"]);
}
?>
