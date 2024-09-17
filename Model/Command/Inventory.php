<?php

//used as the inventory controller -> interact with the model(call function from model) and view(called by logic page controller)
//put abstract function that has to exist in each inventory(factory pattern)
//

require_once '../../Model/Inventory/InventoryModel.php';

abstract class Inventory extends InventoryModel {

    protected $inventoryId; //the item not individual, each item exist in the system
    protected $itemName; // girraffe, beef
    protected $itemType; // which inventory it belongs to like cleaning, food
    protected $storageLocation;
    protected $reorderThreshold;
    protected $quantity;

    public function __construct($itemName=null, $itemType=null, $storageLocation=null, $reorderThreshold=null, $quantity = null) {
        $this->itemName = $itemName;
        $this->itemType = $itemType;
        $this->storageLocation = $storageLocation;
        $this->reorderThreshold = $reorderThreshold;
        $this->quantity = $quantity;
    }

    //put abstract function that has to exist in each inventory(factory pattern)
    abstract function addItemRecord($data);
    abstract function removeItemRecord($records);
    abstract function getLastRecordID();
    abstract function updateImage($uniqueFileName, $itemId);
    abstract function getRecordDetails($inventoryId, $itemId);
    abstract function editItemRecord($data);
    
     public function checkInventory($inventoryId){
        return $this->getInventoryQuantityDB($inventoryId);
    }
    
    public function updateInventoryQuantity($newQuantity, $inventoryId){
        return $this->updateInventoryQuantityDBbasedInventId($newQuantity,$inventoryId);
    }
    
    public function logInventoryUsage($data){
        return $this->updateInventoryLog($data);
    }
    
    
    public function editItem($data){
        return $this->editItemInDB($data);
    }
    
    public function removeInventoryRecord($records) {
        return $this->removeInventoryRecordDB( $records);
    }

    public function addNewItem() {
        if ($this->emptyInput() == false) {
            echo '<script>alert("Required fields cannot be empty!");</script>';
            exit();
        } else {
            $this->inventoryId = $this->addInventoryIntoDB($this->itemName, $this->itemType, $this->storageLocation, $this->reorderThreshold, 0);
        }

        //initialize id by getting it from database
        echo '<script>alert("New inventory item added with ID: ' . $this->inventoryId . '");</script>';
    }

    public function removeItem() {
        if ($this->emptyInput() == false) {
            echo '<script>alert("Required fields cannot be empty!");</script>';
//            header("location: ../../View/InventoryView/index3.php");
            exit();
        }

        $this->removeInventoryIntoDB($this->inventoryId); // retrieve id and remove from record
    }

    private function emptyInput() {
        $result;
        if (empty($this->itemName) || empty($this->itemType) || empty($this->storageLocation)) {
            echo $this->itemName;
            echo empty($this->itemName);
            echo empty($this->itemType);
            echo empty($this->storageLocation);
            $result = false;
        } else {
            $result = true;
        }
        return $result;
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

    public function setStorageLocation($storageLocation): void {
        $this->storageLocation = $storageLocation;
    }

    public function setReorderThreshold($reorderThreshold): void {
        $this->reorderThreshold = $reorderThreshold;
    }
}
