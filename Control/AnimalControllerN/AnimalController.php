<?php
include_once '../../Model/ObserverN/AnimalModel.php';

class AnimalController {

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
            $supplierId = $_POST['supplierId'];
            $storageLocation = $_POST['storageLocation'];
            $reorderThreshold = $_POST['reorderThreshold'];
            $quantity = $_POST['quantity'];

            $success = $this->animalModel->addNewAnimal($animalName, $species, $height, $weight, $habitatId, $healthStatus, $supplierId, $storageLocation, $reorderThreshold, $quantity);

            if ($success) {
//                header("Location: /ZooManagementSystem/success.php");
                echo 'successful adding animal';
            } else {
                $_SESSION['error_message'] = "Failed to add animal.";
                header("Location: ../../View/AnimalView/add_animal.php");
            }
        }
    }
    
    public function displayAnimals($category = null) {
        $animals = $this->animalModel->getAnimals($category);
        include '../../View/AnimalView/animal_list.php';
    }
}

// Initialize controller
new AnimalController();
?>
