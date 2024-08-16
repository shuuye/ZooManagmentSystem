<?php

abstract class InventoryManager {

    public function addItem() {
        $item = createItem();
        return $item;
    }
    
    abstract public function createItem();
}
