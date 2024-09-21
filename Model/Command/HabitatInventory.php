<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';

class HabitatInventory extends Inventory {

    private $id;
    private $brandName;
    private $description;
    private $habitatType; // e.g. aquarium, terrarium, etc.
    private $material;
    private $expected_lifetime;
    private $installation_instructions;
    private $disposal_instructions;

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $brandName = null, $description = null, $habitatType = null, $material = null, $expected_lifetime = null, $installation_instructions = null, $disposal_instructions = null) {
        try {
            parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);
            $this->brandName = $this->sanitizeInput($brandName);
            $this->description = $this->sanitizeInput($description);
            $this->habitatType = $this->sanitizeInput($habitatType);
            $this->material = $this->sanitizeInput($material);
            $this->expected_lifetime = $this->sanitizeInput($expected_lifetime, 'numeric');
            $this->installation_instructions = $this->sanitizeInput($installation_instructions);
            $this->disposal_instructions = $this->sanitizeInput($disposal_instructions);
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            throw new Exception("Error initializing HabitatInventory: " . $e->getMessage());
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getBrandName() {
        return $this->brandName;
    }

    public function setBrandName($brandName): void {
        $this->brandName = $brandName;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHabitatType() {
        return $this->habitatType;
    }

    public function getMaterial() {
        return $this->material;
    }

    public function getExpected_lifetime() {
        return $this->expected_lifetime;
    }

    public function getInstallation_instructions() {
        return $this->installation_instructions;
    }

    public function getDisposal_instructions() {
        return $this->disposal_instructions;
    }

    public function setItemType($itemType): void {
        $this->itemType = $itemType;
    }

    public function setDescription($description): void {
        $this->description = $description;
    }

    public function setHabitatType($habitatType): void {
        $this->habitatType = $habitatType;
    }

    public function setMaterial($material): void {
        $this->material = $material;
    }

    public function setExpected_lifetime($expected_lifetime): void {
        $this->expected_lifetime = $expected_lifetime;
    }

    public function setInstallation_instructions($installation_instructions): void {
        $this->installation_instructions = $installation_instructions;
    }

    public function setDisposal_instructions($disposal_instructions): void {
        $this->disposal_instructions = $disposal_instructions;
    }

    public function toString() {
        return "Habitat Item: " . $this->name . "<br>" .
                "Brand Name: " . $this->brandName . "<br>" .
                "Item Type: " . $this->itemType . "<br>" .
                "Description: " . $this->description . "<br>" .
                "Habitat Type: " . $this->habitatType . "<br>";
    }

    public function addItemRecord($data) {
        try {
            // Sanitize and validate inputs
            $data = $this->sanitizeArray($data);
            $validationResult = $this->validateInputs($data);

            if (!$validationResult['success']) {
                $error = urlencode($validationResult['message']);
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorAddItem&error=$error");
                exit();
            }

            // Add the item to the database
            return $this->addRecordInDB($this->itemType, $data);
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return false; 
        }
    }

    public function removeItemRecord($records) {
        try {
            return $this->removeRecordFromDB($this->itemType, $records);
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return false; 
        }
    }

    public function getLastRecordID() {
        try {
            return $this->getLastInsertedId("habitatinventory");
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return false; 
        }
    }

    public function updateImage($uniqueFileName, $itemId) {
        try {
            return $this->insertItemImage(null, $itemId, null, $uniqueFileName);
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return false; 
        }
    }

    public function getRecordDetails($inventoryId, $itemId) {
        try {
            $result = $this->getRecordDetailsfromDB($inventoryId, $itemId, "habitatinventory");

            if ($result) {
                return $result;
            } else {
                return ["error" => "No record found"];
            }
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return ["error" => "An error occurred while fetching record details"];
        }
    }

    public function editItemRecord($data) {
        try {
            // Sanitize and validate inputs
            $data = $this->sanitizeArray($data);
            $validationResult = $this->validateInputs($data);

            if (!$validationResult['success']) {
                $error = urlencode($validationResult['message']);
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorEditItem&error=$error");
                exit();
            }

            return $this->editItemRecordInDB("Habitat", $data);
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return false; 
        }
    }

    // Validation logic for additional fields
    private function validateInputs($data) {
        $errors = [];

        // Validate brand name (required, min 1, max 50 characters)
        if (empty($data['brandName']) || strlen($data['brandName']) < 1 || strlen($data['brandName']) > 50) {
            $errors[] = 'Brand name must be between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate description (required, min 5, max 255 characters)
        if (empty($data['description']) || strlen($data['description']) < 1 || strlen($data['description']) > 255) {
            $errors[] = 'Description must be between 1 and 255 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate habitat type (required, min 1, max 50 characters)
        if (empty($data['habitatType']) || strlen($data['habitatType']) < 1 || strlen($data['habitatType']) > 50) {
            $errors[] = 'Habitat type must be between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate material (required, min 1, max 50 characters)
        if (empty($data['material']) || strlen($data['material']) < 1 || strlen($data['material']) > 50) {
            $errors[] = 'Material must be between 1 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate expected lifetime (required, numeric and non-negative)
        if (!is_numeric($data['lifeTime']) || $data['lifeTime'] < 0) {
            $errors[] = 'Expected lifetime must be a non-negative number.';
        }

        // Validate installation instructions (optional, max 500 characters)
        if (!empty($data['installationInstru']) && (strlen($data['installationInstru']) < 1 || strlen($data['installationInstru']) > 500)) {
            $errors[] = 'Installation instructions must be between 1 and 500 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate disposal instructions (optional, max 500 characters)
        if (!empty($data['disposalInstru']) && (strlen($data['disposalInstru']) < 1 || strlen($data['disposalInstru']) > 500)) {
            $errors[] = 'Disposal instructions must be between 1 and 500 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Return success or error
        if (count($errors) > 0) {
            return ['success' => false, 'message' => implode(' ', $errors)];
        }

        return ['success' => true];
    }

    // Input sanitization
    private function sanitizeInput($input, $type = 'string') {
        try {
            // Trim input to remove extra spaces
            $input = trim($input);

            if ($type === 'numeric') {
                // For numeric inputs, ensure it's a valid number
                return is_numeric($input) ? (float) $input : null;
            } else {
                // Allow only alphanumeric and some basic characters
                if (!preg_match('/^[a-zA-Z0-9 .\-]+$/', $input)) {
                    return ''; // Return empty string if input contains invalid characters
                }
                return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            }
        } catch (Exception $e) {
            
            error_log($e->getMessage());
            return ''; 
        }
    }

    // Sanitize an array of inputs
    private function sanitizeArray($data) {
        foreach ($data as $key => $value) {
            try {
                if ($key === 'expected_lifetime') {
                    $data[$key] = $this->sanitizeInput($value, 'numeric');
                } else {
                    $data[$key] = $this->sanitizeInput($value);
                }
            } catch (Exception $e) {
                
                error_log($e->getMessage());
                $data[$key] = ''; 
            }
        }
        return $data;
    }
}
