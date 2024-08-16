<?php

require_once 'InventoryManager.php';
class HabitatInventoryFactory extends InventoryManager{
   
    public function createItem() {
        echo "Habitat added";
        return new HabitatInventory();
    }
}
