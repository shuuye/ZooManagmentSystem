<?php
/*Author name: Lim Shuye*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['inventoryId']) && isset($_POST['itemID'])) {
        $inventoryId = intval($_POST['inventoryId']);
        $itemID = intval($_POST['itemID']);
        $itemType = $_POST['itemType'];

        // Instantiate control class
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

        // Create item
        $inventoryCreater = new InventoryItemFactory();
        $inventory = $inventoryCreater->createItem($inventoryId, $itemType, $itemID, Null);

        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
        include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';

        // Execute delete command with single ID
        $inventoryManager = new InventoryManagement();
        $success = $inventoryManager->executeCommand(new deleteItemRecordCommand($inventory, $itemID));

        // Redirect back or show a success message
        if ($success) {
            header("Location: index.php?controller=inventory&action=viewItembasedOnInventoryID&status=successRemove&inventoryId=$inventoryId&itemType=$itemType");
        } else {
            header("Location: index.php?controller=inventory&action=viewItembasedOnInventoryID&status=errorRemove&inventoryId=$inventoryId&itemType=$itemType");
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
