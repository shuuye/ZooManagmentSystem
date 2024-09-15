<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['inventoryId']) && isset($_POST['itemType'])) {
        $inventoryId = intval($_POST['inventoryId']);
        $itemType = $_POST['itemType'];

        // Instantiate control class
        include_once '../../Model/Command/Inventory.php';
        include_once '../../Model/Command/InventoryItemFactory.php';

        // Create item
        $inventoryCreater = new InventoryItemFactory();
        $inventory = $inventoryCreater->createItem($inventoryId, $itemType, Null, Null);

        include_once '../../Model/Command/InventoryManagement.php';
        include_once '../../Model/Command/InventoryCommand.php';

        // Execute delete command with single ID
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new deleteInventoryCommand($inventory, $inventoryId));

        // Redirect back or show a success message
        if ($success) {
            header("Location: ../../Control/InventoryController/index.php?action=index&status=successRemoveInv");
        } else {
            header("Location: ../../Control/InventoryController/index.php?action=index&status=errorRemoveInv");
        }
        exit;
    } else {
        header('Location: ../../Control/InventoryController/index.php?action=index&status=invalidRequest');
        exit();
    }
} else {
    echo "Invalid request method.";
}
?>
