<?php

class PurchaseOrder {

    private $poId;
    private $orderDate;
    private $supplierId;
    private $deliveryDate;
    private $totalAmount;
    private $lineItems = []; // Array of PurchaseOrderLineItem objects


    public function __construct($poId, $orderDate, $supplierId, $deliveryDate = null, $totalAmount = null) {
        $this->poId = $poId;
        $this->orderDate = $orderDate;
        $this->supplierId = $supplierId;
        $this->deliveryDate = $deliveryDate;
        $this->totalAmount = $totalAmount;
    }

    public function addLineItem($inventoryId, $quantity, $costPerUnit) {
        $lineItem = new PurchaseOrderLineItem($inventoryId, $quantity, $costPerUnit);
        $this->lineItems[] = $lineItem;
    }

    public function getLineItems() {
        return $this->lineItems;
    }

    // Getters and setters for PO attributes
    public function getPoId() {
        return $this->poId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getSupplierId() {
        return $this->supplierId;
    }

}
