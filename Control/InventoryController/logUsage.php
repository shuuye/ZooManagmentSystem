<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $dateTime = $_POST['date-time'];
    $inventoryType = $_POST['inventory-type'];
    $inventoryItemId = $_POST['inventory-item'];
    $quantityUsed = $_POST['quantity-used'];
    $reasonForUse = isset($_POST['reason-for-use']) ? $_POST['reason-for-use'] : null;

    $data = [
        'dateTime' => $dateTime,
        'inventoryType' => $inventoryType,
        'inventoryItemId' => $inventoryItemId,
        'quantityUsed' => $quantityUsed,
        'reasonForUse' => $reasonForUse
    ];

    // Validate required fields
    if (empty($inventoryItemId) || empty($quantityUsed)) {
        echo "Error: Inventory item and quantity are required.";
        exit;
    }

    // Instantiate control class
    include_once '../../Model/Command/Inventory.php';
    include_once '../../Model/Command/InventoryItemFactory.php';

    // Create item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($inventoryItemId, $inventoryType, NULL, Null);

    $item = $inventory->checkInventory($inventoryItemId); // Array ( [0] => Array ( [itemName] => Tiger [quantity] => 1 ) )
    $itemDetails = $item[0];

    if ($item) {
        $availableQuantity = $itemDetails['quantity'];

        // Check if the quantity used is less than or equal to the available quantity
        if ($quantityUsed > $availableQuantity) {
            echo "Error: The quantity used exceeds the available stock for " . $itemDetails['itemName'] . ".";
            exit;
        }

        // Update the inventory to reflect the new quantity after usage
        $newQuantity = $availableQuantity - $quantityUsed;

        // Running error handling
        include_once '../../Model/Command/InventoryManagement.php';
        include_once '../../Model/Command/InventoryCommand.php';
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new UpdateItemCommand($inventory, $newQuantity,$itemDetails['quantity']));
        
        $logsuccess = $inventory->logInventoryUsage($data);

        if ($logsuccess) {
            header("Location: ../../Control/InventoryController/index.php?controller=inventory&action=logusage&status=success&newQuantity=$newQuantity");
        } else {
            header("Location: ../../Control/InventoryController/index.php?controller=inventory&action=logusage&status=error&newQuantity=$newQuantity");
        }
    } else {
        header("Location: ../../Control/InventoryController/index.php?controller=inventory&action=logusage&status=itemNotfound");
    }
} else {
    header("Location: ../../Control/InventoryController/index.php?controller=inventory&action=logusage&status=invalidRequest");
}
?>
