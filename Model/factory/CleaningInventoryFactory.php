<?php
require_once 'InventoryManager.php';

class CleaningInventoryFactory extends InventoryManager{
     public function createItem() {
         echo "Cleaning added";
        return new CleanInventory();
    }
}
