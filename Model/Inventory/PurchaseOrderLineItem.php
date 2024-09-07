<?php
include_once '../../Model/Inventory/InventoryModel.php';
class PurchaseOrderLineItem extends InventoryModel{
    private $poLineItemId;
    private $poId;
    private $inventoryId;
    private $cleaningId;
    private $habitatId;
    private $foodId;
    private $quantity;
    private $unitPrice;


    function __construct($poId, $inventoryId, $cleaningId, $habitatId, $foodId, $quantity, $unitPrice) {
        $this->poId = $poId;
        $this->inventoryId = $inventoryId;
        $this->cleaningId = $cleaningId;
        $this->habitatId = $habitatId;
        $this->foodId = $foodId;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }
    
    public function addNewPOLine() {
        $this->$poLineItemId = $this->addPOLineIntoDB($this->poId, $this->inventoryId, $this->cleaningId, $this->habitatId, $this->foodId, $this->quantity, $this->unitPrice);

        //initialize id by getting it from database
        
        return $this->$poLineItemId;
    }
}
