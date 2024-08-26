<?php

require_once __DIR__ . '/../Command/FoodInventory.php';
require_once 'InventoryManager.php';

class FoodInventoryFactory extends InventoryManager {
   
    public function getItem(){
        //use inventory id = that inventory, then add record by adding , add 1 for to the quantity
    }
    
    public function createItem() {
        echo "food added";
        return new FoodInventory();
    }
}
