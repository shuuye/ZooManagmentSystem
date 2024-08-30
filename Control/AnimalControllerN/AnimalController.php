<?php

include_once '../../Model/ObserverN/AnimalModel.php';

class AnimalController{

    private $animalModel;

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $this->handleRequest();
    }

    private function handleRequest() {
    if (isset($_POST['submit'])) {
        $animalName = $_POST['animalName'];
        $species = $_POST['species'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $habitatId = $_POST['habitatId'];
        $healthStatus = $_POST['healthStatus'];
        $quantity = $_POST['quantity'];

        // Call the model method with default values for supplierId, storageLocation, and reorderThreshold
        $success = $this->animalModel->addNewAnimal($animalName, $species, $height, $weight, $habitatId, $healthStatus, $quantity);

        if ($success) {
            // Redirect or display success message
            // header("Location: /ZooManagementSystem/success.php");
            echo 'Successfully added animal';
        } else {
            // Handle the failure
            $_SESSION['error_message'] = "Failed to add animal.";
            header("Location: ../../View/AnimalView/add_animal.php");
        }
    }
}

    
     public function displayAnimals($category = null) {
        if ($category) {
            $animals = $this->animalModel->getAnimalsByCategory($category);
        } else {
            $animals = $this->animalModel->getAnimalsByCategory(); // Assuming you want to fetch all if no category is provided
        }
        return $animals;
    }
}

// Initialize controller
new AnimalController();
?>
