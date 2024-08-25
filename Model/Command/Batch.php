<?php

class Batch { //store different batch of inventory come
    private $batchId;
    private $inventoryId;
    private $quantity;
    private $expiryDate;
    private $unitMeasure;

    public function __construct($batchId, $inventoryId, $quantity, $expiryDate, $unitMeasure) {
        $this->batchId = $batchId;
        $this->inventoryId = $inventoryId;
        $this->quantity = $quantity;
        $this->expiryDate = $expiryDate;
        $this->unitMeasure = $unitMeasure;
    }

    public function getBatchId() {
        return $this->batchId;
    }

    public function getInventoryId() {
        return $this->inventoryId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getExpiryDate() {
        return $this->expiryDate;
    }

    public function getUnitMeasure() {
        return $this->unitMeasure;
    }

    public function updateQuantity($newQuantity) {
        $this->quantity = $newQuantity;
    }

    
}
