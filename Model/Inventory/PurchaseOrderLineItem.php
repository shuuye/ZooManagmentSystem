<?php

class PurchaseOrderLineItem {
    private $poLineItemId;
    private $poId;
    private $batchId;
    private $quantity;
    private $costPerUnit;
    private $purchaseDate;

    public function __construct($poLineItemId, $poId, $batchId, $quantity, $costPerUnit, $purchaseDate) {
        $this->poLineItemId = $poLineItemId;
        $this->poId = $poId;
        $this->batchId = $batchId;
        $this->quantity = $quantity;
        $this->costPerUnit = $costPerUnit;
        $this->purchaseDate = $purchaseDate;
    }

    public function getBatchId() {
        return $this->batchId;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getCostPerUnit() {
        return $this->costPerUnit;
    }
}
