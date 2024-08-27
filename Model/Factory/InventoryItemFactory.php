<?php

class InventoryItemFactory {

    public static function createItem($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold): Inventory {
        switch ($type) {
            case 'animal':
                return new AnimalInventory($item_name, $item_type, $supplier_id, $storageLocation, $reorder_threshold);

                break;
            case 'food':
                return new FoodInventory($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold);
                break;
            case 'cleaning':
                return new CleaningInventory($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold);
                break;
            case 'habitat':
                return new HabitatInventory($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold);
                break;
            default:
                throw new Exception("Unknown inventory item type");
                break;
        }
    }
}
