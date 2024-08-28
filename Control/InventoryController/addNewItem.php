<?php
//controller(logic page) for add new item page

// grab data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];
    $item_type = $_POST["item_type"];
    $supplier_id = $_POST["supplier_id"];
    $storageLocation = $_POST["storageLocation"];
    $reorder_threshold = $_POST["reorder_threshold"];
   

//instantiate control class
    include_once '../../Model/Command/Inventory.php';
    include_once '../../Model/Command/InventoryItemFactory.php';
    //createItem
    echo 'line 17';
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

    echo 'line 20';
//running error handling
    include_once '../../Model/Command/InventoryManagement.php';
    include_once '../../Model/Command/InventoryCommand.php';
    $inventoryManager = new InventoryManagement();
    $inventoryManager->executeCommand(new AddItemCommand($inventory));

    echo 'line 28';
//going back to front page
    echo '<script>alert("New Item successfully added!");</script>';
    header("location: ../../View/InventoryView/index.php");

}