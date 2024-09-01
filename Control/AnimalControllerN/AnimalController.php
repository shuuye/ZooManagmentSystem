<?php

include_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../Model/Inventory/InventoryModel.php';

class AnimalController{

    private $animalModel;
    private $inventoryModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->inventoryModel = new InventoryModel();
//        $this->handleRequest();
    }
    
      // Show the form to select item names and add animal details
    public function showForm() {
        $itemNames = $this->inventoryModel->getAnimalItemNames();
        include '../../View/AnimalView/animal_form.php'; // Pass the item names to the view
    }

    // Process the form submission
    public function processForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemName = $_POST['itemName'];

            // Get inventory ID
            $inventoryId = $this->inventoryModel->getInventoryIdByName($itemName);

            if ($inventoryId) {
                // Collect additional animal details from the form
                $animalDetails = [
                    'name' => $_POST['name'],
                    'species' => $_POST['species'],
                    'subspecies' => $_POST['subspecies'],
                    'categories' => $_POST['categories'],
                    'age' => $_POST['age'],
                    'gender' => $_POST['gender'],
                    'date_of_birth' => $_POST['date_of_birth'],
                    'avg_lifespan' => $_POST['avg_lifespan'],
                    'description' => $_POST['description'],
                    'height' => $_POST['height'],
                    'weight' => $_POST['weight'],
                    'habitat_id' => $_POST['habitat_id']
                ];

                // Add animal details
                $success = $this->animalModel->addAnimal($inventoryId, $animalDetails);

                if ($success) {
                    $message = "Animal added successfully.";
                } else {
                    $message = "Failed to add animal.";
                }
            } else {
                $message = "Invalid item name.";
            }

            include '../../View/AnimalView/animal_result.php'; // Show the result to the user
        }
    }

    
     public function displayAnimals($category = null) {
        if ($category) {
            $animals = $this->animalModel->getAnimalsByCategory($category);
        } else {
            $animals = $this->animalModel->getAnimalsByCategory(); //  all if no category is provided
        }
        return $animals;
    }
}

// Initialize controller
$controller = new AnimalController();

?>
