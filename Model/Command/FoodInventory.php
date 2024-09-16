<?php

require_once 'Inventory.php';

class FoodInventory extends Inventory {

    private $foodType; //meat, vege, fruit (the name is in the inventory)
    private $nutritionInfo;
    private $daily_quantity_required;
    private $batches; // store the batch they come together, only food have expiry date so only food use

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $foodType = null, $nutritionInfo = null, $daily_quantity_required = null, $batches = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);
        $this->foodType = $foodType;
        $this->nutritionInfo = $nutritionInfo;
        $this->daily_quantity_required = $daily_quantity_required;
        $this->batches = $batches;
    }

    public function getFoodType() {
        return $this->foodType;
    }

    public function getNutritionInfo() {
        return $this->nutritionInfo;
    }

    public function getUnit_measure() {
        return $this->unit_measure;
    }

    public function getExpiry_date() {
        return $this->expiry_date;
    }

    public function getDaily_quantity_required() {
        return $this->daily_quantity_required;
    }

    public function setFoodType($foodType): void {
        $this->foodType = $foodType;
    }

    public function setNutritionInfo($nutritionInfo): void {
        $this->nutritionInfo = $nutritionInfo;
    }

    public function setUnit_measure($unit_measure): void {
        $this->unit_measure = $unit_measure;
    }

    public function setExpiry_date($expiry_date): void {
        $this->expiry_date = $expiry_date;
    }

    public function setDaily_quantity_required($daily_quantity_required): void {
        $this->daily_quantity_required = $daily_quantity_required;
    }

    public function toString() {
        return "Food Item: " . $this->name . "<br>" .
                "Food Type: " . $this->foodType . "<br>" .
                "Expiration Date: " . $this->expirationDate . "<br>" .
                "Nutrition Info: " . $this->nutritionInfo . "<br>" .
                "Quantity: " . $this->quantity . "<br>";
    }

    public function update($newQuantity, $newUnitMeasure = null) {
        $this->quantity = $newQuantity;
        if ($newUnitMeasure !== null) {
            $this->unitMeasure = $newUnitMeasure;
        }
    }

    public function addBatch($batchId, $quantity, $expiryDate, $unitMeasure) {
        $batch = new Batch($batchId, $this->getInventoryId(), $quantity, $expiryDate, $unitMeasure);
        array_push($this->batches, $batch);
    }

    public function getBatches() {
        return $this->batches;
    }

    public function fetchBatches($conn) {
        try {
            $query = "SELECT * FROM batch WHERE inventory_id = :inventoryId ORDER BY expiry_date ASC";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":inventoryId", $this->getInventoryId());
            $stmt->execute();

            // Fetch batches and populate the $batches array
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $batch = new Batch($row['batch_id'], $row['inventory_id'], $row['quantity'], $row['expiry_date'], $row['unit_measure']);

                // Check if the batch is expired
                if ($this->isExpired($batch->getExpiryDate())) {
                    // Handle expired batch (e.g., remove from inventory, alert user)
                    echo "Batch ID " . $batch->getBatchId() . " is expired and should be removed from inventory.<br>";
                } else {
                    // Add non-expired batch to inventory
                    array_push($this->batches, $batch);
                }
            }

            return $this->batches;
        } catch (PDOException $exception) {
            echo "Error fetching batches: " . $exception->getMessage();
            return null;
        }
    }

    public function isExpired($expiryDate) {
        $currentDate = date('Y-m-d');
        return strtotime($expiryDate) < strtotime($currentDate);
    }

    public function updateBatchQuantity($batchId, $newQuantity) {
        foreach ($this->batches as $batch) {
            if ($batch->getBatchId() == $batchId) {
                $batch->updateQuantity($newQuantity);
                break;
            }
        }
    }

    public function removeExpiredBatch($batchId, $conn) {
        try {
            $query = "DELETE FROM batch WHERE batch_id = :batchId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":batchId", $batchId);
            $stmt->execute();
            echo "Batch ID $batchId removed from inventory.<br>";
        } catch (PDOException $exception) {
            echo "Error removing expired batch: " . $exception->getMessage();
        }
    }

    public function notifyExpiry($batch) {
        // Implement your notification logic here
        echo "Batch ID " . $batch->getBatchId() . " has expired.<br>";
    }

    public function logExpiredBatch($batchId, $conn) {
        try {
            $query = "INSERT INTO expired_batches_log (batch_id, removal_date) VALUES (:batchId, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":batchId", $batchId);
            $stmt->execute();
            echo "Logged removal of expired batch ID $batchId.<br>";
        } catch (PDOException $exception) {
            echo "Error logging expired batch: " . $exception->getMessage();
        }
    }

    public function addItemRecord($data) {

        $result = $this->addRecordInDB($this->itemType, $data);
        return $result;
    }

    public function removeItemRecord($records) {
        return $this->removeRecordFromDB($this->itemType, $records);
    }

    public function getLastRecordID() {
        return $this->getLastInsertedId("foodinventory");
    }

    public function updateImage($uniqueFileName, $itemId) {
        return $this->insertItemImage(null, null, $itemId, $uniqueFileName);
    }

    public function getRecordDetails($inventoryId, $itemId) {
        $result = $this->getRecordDetailsfromDB($inventoryId, $itemId, "foodinventory");

        if ($result) {
            return $result;
        } else {
            return ["error" => "No record found"];
        }
    }
    
    public function editItemRecord($data) {
        $result = $this->editItemRecordInDB("Food", $data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
