<?php

class PurchaseOrderLineItem {
    private $poLineItemId;
    private $poId;
    private $inventoryId;
    private $quantity;
    private $costPerUnit;
    private $totalAmount;


    public function __construct($poLineItemId, $poId, $inventoryId, $quantity, $costPerUnit, $totalAmount) {
        $this->poLineItemId = $poLineItemId;
        $this->poId = $poId;
        $this->inventoryId = $inventoryId;
        $this->quantity = $quantity;
        $this->costPerUnit = $costPerUnit;
        $this->totalAmount = $totalAmount;
    }
    
    public function getQuantity() {
        return $this->quantity;
    }

    public function getCostPerUnit() {
        return $this->costPerUnit;
    }
}
