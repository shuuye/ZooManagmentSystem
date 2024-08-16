<?php

require_once 'InventoryManager.php';
require_once 'FoodInventory.php';
class FoodInventoryFactory extends InventoryManager{
   
    public function createItem() {
        echo "food added";
        return new FoodInventory();
    }
}
