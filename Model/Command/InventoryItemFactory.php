<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\AnimalInventory.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\CleaningInventory.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\FoodInventory.php';
include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\HabitatInventory.php';

//require_once(__DIR__ . '../Command/HabitatInventory.php');

class InventoryItemFactory {

    public static function createItem($item_name, $item_type, $storageLocation, $reorder_threshold): Inventory {
        switch ($item_type) {
            case 'Animal':
                return new AnimalInventory($item_name, $item_type, $storageLocation, $reorder_threshold);

            case 'Food':
                return new FoodInventory($item_name, $item_type, $storageLocation, $reorder_threshold);

            case 'Cleaning':
                return new CleaningInventory($item_name, $item_type, $storageLocation, $reorder_threshold);

            case 'Habitat':
                return new HabitatInventory($item_name, $item_type, $storageLocation, $reorder_threshold);

            default:
                throw new Exception("Unknown inventory item type");
        }
    }
}
