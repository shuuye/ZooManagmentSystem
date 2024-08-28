<?php
//used as the inventory controller -> interact with the model(call function from model) and view(called by logic page controller)
//initialize id by getting it from database!!! for all inventory
//put abstract function that has to exist in each inventory(factory pattern)
//

require_once '../../Model/Inventory/InventoryModel.php';

abstract class Inventory extends InventoryModel  {
    protected $inventoryId; //the item not individual, each item exist in the system
    protected $itemName; // girraffe, beef
    protected $itemType; // which inventory it belongs to like cleaning, food
    protected $supplierId; 
    protected $storageLocation;
    protected $reorderThreshold;

    public function __construct($itemName, $itemType, $supplierId, $storageLocation, $reorderThreshold) {
        $this->itemName = $itemName;
        $this->itemType = $itemType;
        $this->supplierId = $supplierId;
        $this->storageLocation = $storageLocation;
        $this->reorderThreshold = $reorderThreshold;
    }
    
    //put abstract function that has to exist in each inventory(factory pattern)
    
    public function addNewItem(){
        if($this->emptyInput() == false){
            echo '<script>alert("Required fields cannot be empty!");</script>';
//            header("location: ../../View/InventoryView/index3.php");
            exit();
        }else{
            echo '<script>alert("Successfull added!");</script>';
             $this->addInventoryIntoDB($this->itemName, $this->itemType, $this->supplierId, $this->storageLocation, $this->reorderThreshold);
             //            header("location: ../../View/InventoryView/index3.php");
        }
        
       
        //initialize id by getting it from database
    }
    
    public function removeItem(){
        if($this->emptyInput() == false){
            echo '<script>alert("Required fields cannot be empty!");</script>';
//            header("location: ../../View/InventoryView/index3.php");
            exit();
        }
        
        $this->removeInventoryIntoDB($this->inventoryId);// retrieve id and remove from record
    }
    
    private function emptyInput(){
        $result;
        if(empty($this->itemName) || empty($this->itemType) || empty($this->storageLocation)){
            echo $this->itemName;
            echo empty($this->itemName);
            echo empty($this->itemType);
            echo empty($this->storageLocation);
            $result = false;
        }else{
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
