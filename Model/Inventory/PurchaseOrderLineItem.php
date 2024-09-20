<?php

include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';

class PurchaseOrderLineItem extends InventoryModel {

    private $poLineItemId;
    private $poId;
    private $inventoryId;
    private Inventory $inventory;
    private $quantity;
    private $unitPrice;

    function __construct($poId, $inventoryId, Inventory $inventory, $quantity, $unitPrice) {
        $this->poId = $poId;
        $this->inventoryId = $inventoryId;
        $this->inventory = $inventory;
        $this->quantity = $quantity;
        $this->unitPrice = $unitPrice;
    }

    public function addNewPOLine() {
        $foodId = $this->inventory->getItemType() === 'Food' ? $this->inventory->getId() : null;
        $habitatId = $this->inventory->getItemType() === 'Habitat' ? $this->inventory->getId() : null;
        $cleaningId = $this->inventory->getItemType() === 'Cleaning' ? $this->inventory->getId() : null;

        $this->poLineItemId = $this->addPOLineIntoDB($this->poId, $this->inventoryId, $cleaningId, $habitatId, $foodId, $this->quantity, $this->unitPrice);

        return $this->poLineItemId;
    }
}
