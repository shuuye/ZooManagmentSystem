<?php
/*Author name: Lim Shuye*/


include_once 'C:\xampp\htdocs\ZooManagementSystem\Model\Inventory\InventoryModel.php';

class PurchaseOrder extends InventoryModel {

    private $poId;
    private ?Supplier $supplier;
    private $orderDate;
    private $deliveryDate;
    private $billingAddress;
    private $shippingAddress;
    private $totalAmount;
    private $status;
    private $lineItems = []; // Array of PurchaseOrderLineItem objects

    public function __construct(
            ?Supplier $supplier = null,
            $orderDate = null,
            $deliveryDate = null,
            $billingAddress = null,
            $shippingAddress = null,
            $totalAmount = null,
            $status = null
    ) {
        $this->supplier = $supplier;
        $this->orderDate = $orderDate;
        $this->deliveryDate = $deliveryDate;
        $this->totalAmount = $totalAmount;
        $this->status = $status;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
    }

    public function addLineItem($poId, $inventoryId, $quantity, $unitPrice, Inventory $inventory) {
        $lineItem = new PurchaseOrderLineItem($poId, $inventoryId, $inventory, $quantity, $unitPrice);
        $this->lineItems[] = $lineItem;

        return $lineItem;
    }

    public function addNewPO() {
        if ($this->emptyInput()) {
            $this->poId = $this->addPOIntoDB($this->supplier->getId(), $this->orderDate, $this->deliveryDate, $this->billingAddress, $this->shippingAddress, $this->totalAmount, $this->status);
            return $this->poId;
        } else {
            return null;
        }
    }

    public function deletePurchaseOrder($poId) {
        $success = $this->removePOfromDB($poId);
        return $success;
    }

    public function updatePurchaseOrder($poId, $status) {
        $success = $this->updatePOStatusDB($poId, $status);
        return $success;
    }

    public function updateInventoryQuantity($poId) {
        $success = $this->updateInventoryQuantityDB($poId);
        return $success;
    }

    public function gePODetailsDB($email) {
        $data = $this->getPODetails($email);
        return $data;
    }

    public function addNewItem() {
        // Input validation
        $validationResult = $this->validateInputs();

        if (!$validationResult['success']) {
            // Output validation error message
            $error = urlencode($validationResult['message']); // Encode error message for URL
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&status=errorAddItem&error=$error");
            exit();
        }

        // Proceed if validation is successful
        $this->inventoryId = $this->addInventoryIntoDB($this->itemName, $this->itemType, $this->storageLocation, $this->reorderThreshold, 0);
    }

// Validate inputs using the validateInput function
    private function validateInputs() {
        $errors = [];

        // Validate item name (string, min 5, max 100)
        if (!$this->validateInput($this->itemName, 'string', 5, 100)) {
            $errors[] = 'Item name must be between 5 and 100 characters, and only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate storage location (string, min 5, max 100)
        if (!$this->validateInput($this->storageLocation, 'string', 5, 100)) {
            $errors[] = 'Storage location must be between 5 and 100 characters, and only letters, numbers, spaces, commas, periods, and hyphens are allowed.';
        }

        // Validate reorder threshold (numeric, min 0)
        if (!$this->validateInput($this->reorderThreshold, 'numeric') || $this->reorderThreshold < 0) {
            $errors[] = 'Reorder threshold must be a non-negative number.';
        }

        if (count($errors) > 0) {
            return ['success' => false, 'message' => implode(' ', $errors)];
        }

        return ['success' => true];
    }

// validateInput function (already provided by you)
    private function validateInput($input, $type = 'string', $minLength = 1, $maxLength = 255) {
        // Trim input to remove extra spaces
        $input = trim($input);

        // Check the length of the input
        if (strlen($input) < $minLength || strlen($input) > $maxLength) {
            return false;
        }

        // Switch to handle different types of input
        switch ($type) {
            case 'string':
                // Only allow letters, numbers, spaces, and basic punctuation for addresses and status
                return preg_replace("/[^a-zA-Z0-9\s,.-]/", "", $input);

            case 'numeric':
                // Only allow numbers and check if it's numeric
                return is_numeric($input) ? $input : false;

            case 'date':
                // Check if it's a valid date format (YYYY-MM-DD)

                return (DateTime::createFromFormat('Y-m-d H:i:s', $input) !== false) ? $input : false;

            case 'amount':
                // Check for a valid decimal number (money format)
                return preg_match("/^\d+(\.\d{2})?$/", $input) ? $input : false;

            default:
                return false;
        }
    }

    private function emptyInput() {
        // Validate each input based on its type and length
        $supplierIdValid = $this->validateInput($this->supplier->getId(), 'numeric', 1, 20); // assuming ID max 20 digits
        $orderDateValid = $this->validateInput($this->orderDate, 'date'); // no need for length on date
        $deliveryDateValid = $this->validateInput($this->deliveryDate, 'date'); // no need for length on date
        $billingAddressValid = $this->validateInput($this->billingAddress, 'string', 5, 100); // address length 5-255
        $shippingAddressValid = $this->validateInput($this->shippingAddress, 'string', 5, 100); // address length 5-255
        $totalAmountValid = $this->validateInput($this->totalAmount, 'amount'); // no length check for amount

        if (
                $supplierIdValid === false ||
                $orderDateValid === false ||
                $deliveryDateValid === false ||
                $billingAddressValid === false ||
                $shippingAddressValid === false ||
                $totalAmountValid === false
        ) {
            return false;
        }

        return true;
    }
}
