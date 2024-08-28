<?php

include_once 'AnimalInventory.php';
include_once 'CleaningInventory.php';
include_once 'FoodInventory.php';
include_once 'HabitatInventory.php';

//require_once(__DIR__ . '../Command/HabitatInventory.php');

class InventoryItemFactory {

    public static function createItem($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold): Inventory {
        echo $item_type;
        switch ($item_type) {
            case 'animal':
                return new AnimalInventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

            case 'food':
                return new FoodInventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

            case 'cleaning':
                return new CleaningInventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

            case 'habitat':
                return new HabitatInventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

            default:
                throw new Exception("Unknown inventory item type");
        }
    }
}
