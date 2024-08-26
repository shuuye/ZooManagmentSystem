<?php

// grab data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];
    $item_type = $_POST["item_type"];
    $supplier_id = $_POST["supplier_id"];
    $storageLocation = $_POST["storageLocation"];
    $reorder_threshold = $_POST["reorder_threshold"];
   

//instantiate control class
    include_once '../../Model/Command/Inventory.php';
    include_once '../../Model/Command/CleaningInventory.php';
    include_once '../../Model/Command/HabitatInventory.php';
    include_once '../../Model/Command/FoodInventory.php';
    include_once '../../Model/Command/AnimalInventory.php';
    $inventory = new Inventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

//running error handling
    echo $inventory->getItemName();
    $inventory->addNewItem();

//going back to front page
    echo '<script>alert("New Item successfully added!");</script>';
    header("location: ../../View/InventoryView/index.php");

}