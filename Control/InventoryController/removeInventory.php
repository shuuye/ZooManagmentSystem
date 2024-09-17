<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['inventoryId']) && isset($_POST['itemType'])) {
        $inventoryId = intval($_POST['inventoryId']);
        $itemType = $_POST['itemType'];

        // Instantiate control class
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

        // Create item
        $inventoryCreater = new InventoryItemFactory();
        $inventory = $inventoryCreater->createItem($inventoryId, $itemType, Null, Null);

        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';

        // Execute delete command with single ID
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new deleteInventoryCommand($inventory, $inventoryId));

        // Redirect back or show a success message
        if ($success) {
            header("Location: index.php?controller=inventory&action=inventoryTracking&status=successRemoveInv");
        } else {
            header("Location: index.php?controller=inventory&action=inventoryTracking&status=errorRemoveInv");
        }
        exit;
    } else {
        header('Location: index.php?controller=inventory&action=inventoryTracking&status=invalidRequest');
        exit();
    }
} else {
    echo "Invalid request method.";
}
?>
