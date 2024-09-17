<?php
// Controller for adding a new item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Grab data
    $item_name = isset($_POST["item_name"]) ? trim($_POST["item_name"]) : '';
    $item_type = isset($_POST["item_type"]) ? trim($_POST["item_type"]) : '';
    $supplier_id = isset($_POST["supplier_id"]) ? trim($_POST["supplier_id"]) : '';
    $storageLocation = isset($_POST["storageLocation"]) ? trim($_POST["storageLocation"]) : '';
    $reorder_threshold = isset($_POST["reorder_threshold"]) ? trim($_POST["reorder_threshold"]) : '';

    // Instantiate control class
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryItemFactory.php';

    // Create item
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($item_name, $item_type, $storageLocation, $reorder_threshold);

    // Running error handling
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryManagement.php';
    include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\InventoryCommand.php';
    $inventoryManager = new InventoryManagement();
    $success = $inventoryManager->executeCommand(new AddItemCommand($inventory));

    // Redirect with status message
    if ($success) {
        header("Location:index.php?controller=inventory&action=inventoryTracking&status=success");
    } else {
        header("Location: index.php?controller=inventory&action=inventoryTracking&status=error");
    }
    exit();
}
?>
