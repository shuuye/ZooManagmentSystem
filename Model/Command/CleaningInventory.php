<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';

class CleaningInventory extends Inventory {

    private $cleaningType; // which type of cleaning item it is
    private $size;
    private $usageInstructions;

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $cleaningType = null, $size = null, $usageInstructions = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);

        // Sanitize and assign the additional properties
        $this->cleaningType = $this->sanitizeInput($cleaningType);
        $this->size = $this->sanitizeInput($size);
        $this->usageInstructions = $this->sanitizeInput($usageInstructions);
    }

    // Getters for the additional properties
    public function getCleaningType() {
        return $this->cleaningType;
    }

    public function getSize() {
        return $this->size;
    }

    public function getUsageInstructions() {
        return $this->usageInstructions;
    }

    // Update quantity and optionally size
    public function update($newQuantity, $newSize = null) {
        $this->quantity = $newQuantity;
        if ($newSize !== null) {
            $this->size = $this->sanitizeInput($newSize, 'numeric'); // Sanitize new size input
        }
    }

    public function getLastRecordID() {
        return $this->getLastInsertedId("cleaninginventory");
    }

    // Add new item record with validation
    public function addItemRecord($data) {
        // Sanitize and validate inputs before adding record
        $data['brandName'] = $this->sanitizeInput($data['brandName']);
        $data['size'] = $this->sanitizeInput($data['size']);
        $data['instruction'] = $this->sanitizeInput($data['instruction']);

        $validationResult = $this->validateInputs($data);

        if (!$validationResult['success']) {
            // Handle validation error
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorAddItem&error=$error");
            exit();
        }

        // Add the item to the database
        $result = $this->addRecordInDB($this->itemType, $data);
        return $result;
    }

    public function removeItemRecord($records) {
        return $this->removeRecordFromDB($this->itemType, $records);
    }

    public function updateImage($uniqueFileName, $itemId) {
        return $this->insertItemImage($itemId, null, null, $uniqueFileName);
    }

    public function getRecordDetails($inventoryId, $itemId) {
        $result = $this->getRecordDetailsfromDB($inventoryId, $itemId, "cleaninginventory");

        if ($result) {
            return $result;
        } else {
            return ["error" => "No record found"];
        }
    }

    public function editItemRecord($data) {

        $data['brandName'] = $this->sanitizeInput($data['brandName']);
        $data['size'] = $this->sanitizeInput($data['size']);
        $data['instruction'] = $this->sanitizeInput($data['instruction']);
        
        $validationResult = $this->validateInputs($data);

        if (!$validationResult['success']) {
            // Handle validation error
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorEditItem&error=$error");
            exit();
        }

        $result = $this->editItemRecordInDB("Cleaning", $data);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Validation logic for additional fields
    private function validateInputs($data) {
        $errors = [];

        // Validate cleaning type (min 3, max 50 characters)
        if (empty($data['brandName']) || strlen($data['brandName']) < 1 || strlen($data['brandName']) > 50) {
            $errors[] = 'Brand name must be between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        if (empty($data['size']) || strlen($data['size']) < 1 || strlen($data['size']) > 50) {
            $errors[] = 'Size must be between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate usage instructions (optional, max 200 characters)
        if (empty($data['instruction']) || strlen($data['instruction']) < 1 || strlen($data['instruction']) > 200) {
            $errors[] = 'Usage instructions must between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
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
            $errors[] = 'Brand name must be between 1 and 50 characters.';
            return is_numeric($input) ? (float) $input : 0;
        } else {
            // Allow only alphanumeric characters (with spaces for text inputs)
            if (!preg_match('/^[a-zA-Z0-9 .\-]+$/', $input)) {
                return ''; // Return empty string if input contains special characters
            }
            // Sanitize the input (for HTML contexts, though we now ensure alphanumeric)
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            
        }
    }
}
