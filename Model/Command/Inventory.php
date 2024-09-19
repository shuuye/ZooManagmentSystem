<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';

abstract class Inventory extends InventoryModel {

    public function __construct($itemName = null, $itemType = null, $storageLocation = null, $reorderThreshold = null, $quantity = null) {
        $this->itemName = $this->sanitizeInput($itemName);
        $this->itemType = $this->sanitizeInput($itemType);
        $this->storageLocation = $this->sanitizeInput($storageLocation);
        $this->reorderThreshold = $this->sanitizeInput($reorderThreshold, 'numeric');
        $this->quantity = $this->sanitizeInput($quantity, 'numeric');
    }

    // Abstract methods
    abstract function addItemRecord($data);

    abstract function removeItemRecord($records);

    abstract function getLastRecordID();

    abstract function updateImage($uniqueFileName, $itemId);

    abstract function getRecordDetails($inventoryId, $itemId);

    abstract function editItemRecord($data);

    // Add new item
    public function addNewItem() {
        $validationResult = $this->validateInputs($this->toArray());

        if (!$validationResult['success']) {
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorAddItem&error=$error");
            exit();
        }

        $this->inventoryId = $this->addInventoryIntoDB(
                $this->itemName,
                $this->itemType,
                $this->storageLocation,
                $this->reorderThreshold,
                0
        );
    }

    // Edit existing item
    public function editItem($data) {
        $data = $this->sanitizeArray($data); // Sanitize the input data

        $validationResult = $this->validateInputs($data);

        if (!$validationResult['success']) {
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorEditItem&error=$error");
            exit();
        }

        return $this->editItemInDB($data); // Assuming this uses prepared statements
    }

    // Convert object properties to an array (for validation purposes)
    private function toArray() {
        return [
            'itemName' => $this->itemName,
            'itemType' => $this->itemType,
            'storageLocation' => $this->storageLocation,
            'reorderThreshold' => $this->reorderThreshold,
        ];
    }

    // Validation logic
    private function validateInputs($data) {
        $errors = [];

        // Validate item name (min 5, max 100 characters)
        if (empty($data['itemName']) || strlen($data['itemName']) < 5 || strlen($data['itemName']) > 100) {
            $errors[] = 'Item name must be between 5 and 100 characters,  only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate storage location (min 5, max 100 characters)
        if (empty($data['storageLocation']) || strlen($data['storageLocation']) < 5 || strlen($data['storageLocation']) > 100) {
            $errors[] = 'Storage location must be between 5 and 100 characters,  only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate reorder threshold (numeric and non-negative)
        if (!is_numeric($data['reorderThreshold']) || $data['reorderThreshold'] < 0) {
            $errors[] = 'Reorder threshold must be a non-negative number.';
        }

        // Return success or error
        if (count($errors) > 0) {
            return ['success' => false, 'message' => implode(' ', $errors)];
        }

        return ['success' => true];
    }

    // Input sanitization
    private function sanitizeInput($input, $type = 'string') {
        // Trim input to remove extra spaces
        $input = trim($input);

        if ($type === 'numeric') {
            // For numeric inputs, ensure it's a valid number
            return is_numeric($input) ? (float) $input : 0;
        } else {
            // Allow only alphanumeric characters
            if (!preg_match('/^[a-zA-Z0-9 ]+$/', $input)) { // Space included for readability
                return ''; // Return empty string if input contains special characters
            }
            // Sanitize the input (for HTML contexts, though we now ensure alphanumeric)
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
    }

    // Sanitize an array of inputs
    private function sanitizeArray($data) {
        foreach ($data as $key => $value) {
            if ($key === 'reorderThreshold') {
                $data[$key] = $this->sanitizeInput($value, 'numeric');
            } else {
                $data[$key] = $this->sanitizeInput($value);
            }
        }
        return $data;
    }

    public function updateInventoryQuantity($newQuantity, $inventoryId) {
        return $this->updateInventoryQuantityDBbasedInventId($newQuantity, $inventoryId);
    }

    public function logInventoryUsage($data) {
        return $this->updateInventoryLog($data);
    }

    public function removeInventoryRecord($records) {
        return $this->removeInventoryRecordDB($records);
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
