<?php

require_once 'Inventory.php';

class CleaningInventory extends Inventory {

    private $cleaningType; //which type of cleaning item it is
    private $size;
    private $usageInstructions;

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $cleaningType = null, $size = null, $usageInstructions = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);
        $this->cleaningType = $cleaningType;
        $this->size = $size;
        $this->usageInstructions = $usageInstructions;
    }

    public function getCleaningType() {
        return $this->cleaningType;
    }

    public function getSize() {
        return $this->size;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    public function getUsageInstructions() {
        return $this->usageInstructions;
    }

    public function update($newQuantity, $newSize = null) {
        $this->quantity = $newQuantity;
        if ($newSize !== null) {
            $this->size = $newSize;
        }
    }
    
    public function getLastRecordID(){
        return $this->getLastInsertedId("cleaninginventory");
    }

    public function addItemRecord($data) {
        
        $result = $this->addRecordInDB($this->itemType, $data);
        return $result;
        
    }

    public function removeItemRecord($records) {
        return $this->removeRecordFromDB($this->itemType, $records);
    }
    
    public function updateImage($uniqueFileName, $itemId){
        return $this->insertItemImage($itemId, null, null, $uniqueFileName);
    }
}
