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
    $inventoryCreater = new InventoryItemFactory();
    $inventory = $inventoryCreater->createItem($item_name, $item_type, $storageLocation, $reorder_threshold);
    
//running error handling
    include_once '../../Model/Command/InventoryManagement.php';
    include_once '../../Model/Command/InventoryCommand.php';
    $inventoryManager = new InventoryManagement();
    $inventoryManager->executeCommand(new AddItemCommand($inventory));

    //going back to front page
    header("location: ../../Control/InventoryController/index.php");

}