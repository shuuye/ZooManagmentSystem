<?php

class Inventory {
    protected $inventoryId; //the item not individual, each item exist in the system
    protected $itemName; // girraffe, beef
    protected $itemType; // which inventory it belongs to like cleaning, food
    protected $supplierId; 
    protected $storageLocation;
    protected $reorderThreshold;

    public function __construct($inventoryId, $itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold) {
        $this->inventoryId = $inventoryId;
        $this->itemName = $itemName;
        $this->itemType = $itemType;
        $this->supplierId = $supplierId;
        $this->storageLocation = $storageLocation;
        $this->reorderThreshold = $reorderThreshold;
    }

    public function getInventoryId() {
        return $this->inventoryId;
    }

    public function getItemName() {
        return $this->itemName;
    }

    public function getItemType() {
        return $this->itemType;
    }

    public function getSupplierId() {
        return $this->supplierId;
    }

    public function getStorageLocation() {
        return $this->storageLocation;
    }

    public function getReorderThreshold() {
        return $this->reorderThreshold;
    }

    public function setItemName($itemName): void {
        $this->itemName = $itemName;
    }

    public function setItemType($itemType): void {
        $this->itemType = $itemType;
    }

    public function setSupplierId($supplierId): void {
        $this->supplierId = $supplierId;
    }

    public function setStorageLocation($storageLocation): void {
        $this->storageLocation = $storageLocation;
    }

    public function setReorderThreshold($reorderThreshold): void {
        $this->reorderThreshold = $reorderThreshold;
    }


}
