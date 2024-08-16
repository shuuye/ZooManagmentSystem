<?php
require_once 'Inventory.php';
require_once 'CleaningInventory.php';

class CleaningInventory extends Inventory {
    private $cleaningType;
    private $size;
    private $usageInstructions;

    public function __construct($id, $name, $quantity, $cleaningType, $size, $usageInstructions = null) {
        parent::__construct($id, $name, $quantity);
        $this->cleaningType = $cleaningType;
        $this->size = $size;
        $this->usageInstructions = $usageInstructions;
    }

    public function getCleaningType() {
        return $this->cleaningType;
    }

    public function getSize() {
        return $this->size;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    public function getUsageInstructions() {
        return $this->usageInstructions;
    }

    public function update($newQuantity) {
        // override update method to update quantity based on size
        $this->quantity = $newQuantity * $this->size;
    }
}