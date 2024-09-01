<?php

class Batch { //store different batch of inventory come

    private $batchId;
    private $inventoryId;
    private $quantity;
    private $manufactureDate;
    private $expiryDate;
    private $supplierId;
    private $status;

    public function __construct($batchId, $inventoryId, $quantity, $manufactureDate, $expiryDate, $supplierId, $status) {
        $this->batchId = $batchId;
        $this->inventoryId = $inventoryId;
        $this->quantity = $quantity;
        $this->manufactureDate = $manufactureDate;
        $this->expiryDate = $expiryDate;
        $this->supplierId = $supplierId;
        $this->status = $status;
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
}
