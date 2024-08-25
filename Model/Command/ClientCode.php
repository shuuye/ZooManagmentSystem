<?php
require_once 'Inventory.php';
require_once 'InventoryCommand.php';
require_once 'AnimalInventory.php';
require_once 'HabitatInventory.php';
require_once 'CleaningInventory.php';
require_once 'FoodInventory.php';
require_once 'InventoryManagement.php';
require_once 'AddItemCommand.php';

$inventoryManager = new InventoryManagement();

// Create an animal inventory item
$lionInventory = new AnimalInventory(
    "ANIMAL-001",
    "Lion",
    "Panthera leo",
    5,
    "Male",
    "2018-06-01",
    "12 years",
    "A majestic lion",
    1.2, // height in meters
    190.5, // weight in kilograms
    "Healthy",
    "Savannah Habitat",
    2 // initial quantity
);

// Print some details
echo "Animal: " . $lionInventory->getItemName() . "\n";
echo "Species: " . $lionInventory->getSpecies() . "\n";
echo "Quantity: " . $lionInventory->getQuantity() . "\n";
echo "<br><br>";
// Add more lions to the inventory
$addCommand = new AddItemCommand($lionInventory, 1);
//execute the add command
$inventoryManager->executeCommand($addCommand);
echo "<br><br>";
// undo the executed command
$inventoryManager->undoCommand();
echo "<br><br>".$lionInventory->getQuantity();
echo "<br><br>";

//unable to undo beccause no move already
$inventoryManager->undoCommand();
$inventoryManager->undoCommand();
echo "hello";

// Instantiate database and get a connection
$database = new Database();
$conn = $database->getConnection();

// Create a FoodInventory object
$foodInventory = new FoodInventory(1, "Apples", "Food", 101, "Cold Storage", 50, "Fruit", "Vitamins, Fiber", 5);

// Fetch batches for this inventory item
$batches = $foodInventory->fetchBatches($conn);

// Display batch details
if ($batches) {
    foreach ($batches as $batch) {
        echo "Batch ID: " . $batch->getBatchId() . "<br>";
        echo "Quantity: " . $batch->getQuantity() . "<br>";
        echo "Expiry Date: " . $batch->getExpiryDate() . "<br>";
        echo "Unit Measure: " . $batch->getUnitMeasure() . "<br><br>";
    }
} else {
    echo "No batches found for this inventory item.";
}

