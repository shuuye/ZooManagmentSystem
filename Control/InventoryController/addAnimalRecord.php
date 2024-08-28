<?php
//controller(logic page) for add new animal record page
// grab data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];//like lion
    $item_type = $_POST["item_type"];//like animal, habitat (remember to check spelling and capital)
//    other datas too
    
//get data from model
    $retrieve_data = new InventoryModel();
    //example to retrieve record by type
    $allInventoryByType = $retrieve_data->getAllRecordsByType($itemType);
    //example to retrieve inventory id by inventory name
    $getIDbyName = $retrieve_data->getInventoryIdByName($itemName);

//instantiate control class
    //createItem
    $itemObject = $retrieve_data->getObjectById($inventoryId);
    $itemObject->setCategories($categories); //then you can use itemObject to excess the functions in AnimalInventory already
    
    
//running error handling
    include_once '../../Model/Command/InventoryManagement.php';
    include_once '../../Model/Command/InventoryCommand.php';
    $inventoryManager = new InventoryManagement();
    $inventoryManager->executeCommand(new AddItemRecordCommand($inventory));
//havent check if it will direclty go to animal inventory by abstract function, 
//if cannot just use traditional way dont use my design patter
//for example, put function inside your animal inventory, then directly call from line 19 $itemObject->addItemRecord()

 

}