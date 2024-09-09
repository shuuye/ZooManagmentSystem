<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['record']) && is_array($_POST['record'])) {
        $idsToDelete = $_POST['record'];
        $itemType = $_POST['itemType'];
        // Convert IDs to a comma-separated string for SQL query
        $idsToDelete = implode(',', array_map('intval', $idsToDelete));
        
        // Instantiate control class
        include_once '../../Model/Command/Inventory.php';
        include_once '../../Model/Command/InventoryItemFactory.php';
        // Create item
        $inventoryCreater = new InventoryItemFactory();
        $inventory = $inventoryCreater->createItem(Null, $itemType, Null, Null);

        include_once '../../Model/Command/InventoryManagement.php';
        include_once '../../Model/Command/InventoryCommand.php';
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new deleteItemRecordCommand($inventory));

        // Prepare and execute the DELETE query
        $stmt = $pdo->prepare("DELETE FROM cleaninginventory WHERE inventoryId IN ($idsToDelete)");
        $stmt->execute();

        // Redirect back or show a success message
        header('Location: your_page.php');
        exit;
    }
}
?>
