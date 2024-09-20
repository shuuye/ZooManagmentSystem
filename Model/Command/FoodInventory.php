<?php

require_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Command\Inventory.php';

class FoodInventory extends Inventory {

    private $id;
    private $foodType; // meat, vege, fruit
    private $nutritionInfo;
    private $daily_quantity_required;

    public function __construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity = null, $foodType = null, $nutritionInfo = null, $daily_quantity_required = null) {
        parent::__construct($itemName, $itemType, $storageLocation, $reorderThreshold, $quantity);

        // Sanitize and assign properties
        $this->foodType = $this->sanitizeInput($foodType);
        $this->nutritionInfo = $this->sanitizeInput($nutritionInfo);
        $this->daily_quantity_required = $this->sanitizeInput($daily_quantity_required, 'numeric');
    }

    // Getters and Setters

    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    public function getFoodType() {
        return $this->foodType;
    }

    public function getNutritionInfo() {
        return $this->nutritionInfo;
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

    public function setDaily_quantity_required($daily_quantity_required): void {
        $this->daily_quantity_required = $daily_quantity_required;
    }

    // Validation for foodType, nutritionInfo, daily_quantity_required, and batches
    public function validateInputs($data) {
        $errors = [];

        // Validate food type (min 3, max 50 characters)
        if (empty($data['brandName']) || strlen($data['brandName']) < 3 || strlen($data['brandName']) > 50) {
            $errors[] = 'Brand name must be between 3 and 50 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        if (empty($data['nutritionInfo']) || strlen($data['nutritionInfo']) < 3 || strlen($data['nutritionInfo']) > 200) {
            $errors[] = 'Nutrition info must be between 3 and 200 characters, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate daily quantity required (numeric and non-negative)
        if (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            $errors[] = 'Daily quantity required must be a positive number, only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        if (count($errors) > 0) {
            return ['success' => false, 'message' => implode(' ', $errors)];
        }

        return ['success' => true];
    }

    // Sanitization of input fields
    public function sanitizeInput($input, $type = 'string') {
        // Trim input to remove extra spaces
        $input = trim($input);

        if ($type === 'numeric') {
            return is_numeric($input) ? (float) $input : 0;
        } else {
            // Allow only alphanumeric and some basic characters
            if (!preg_match('/^[a-zA-Z0-9 .\-]+$/', $input)) {
                return ''; // Return empty string if input contains invalid characters
            }
            return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
    }

    // Sanitizing an array of inputs (e.g., for batches)
    public function sanitizeArray($data) {
        foreach ($data as $key => $value) {
            $data[$key] = $this->sanitizeInput($value);
        }
        return $data;
    }

    // Add new food item record with validation
    public function addItemRecord($data) {
        // Sanitize and validate input data
        $data['brandName'] = $this->sanitizeInput($data['brandName']);
        $data['nutritionInfo'] = $this->sanitizeInput($data['nutritionInfo']);
        $data['quantity'] = $this->sanitizeInput($data['quantity'], 'numeric');

        $validationResult = $this->validateInputs($data);

        if (!$validationResult['success']) {
            // Handle validation error
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorAddItem&error=$error");
            exit();
        }

        // Add the item to the database
        return $this->addRecordInDB($this->itemType, $data);
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
        // Sanitize and validate input data
        $data['brandName'] = $this->sanitizeInput($data['brandName']);
        $data['nutritionInfo'] = $this->sanitizeInput($data['nutritionInfo']);
        $data['quantity'] = $this->sanitizeInput($data['quantity'], 'numeric');
        $validationResult = $this->validateInputs($data);

        if (!$validationResult['success']) {
            // Handle validation error
            $error = urlencode($validationResult['message']);
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorEditItem&error=$error");
            exit();
        }

        // Edit the item in the database
        return $this->editItemRecordInDB("Food", $data);
    }
}
