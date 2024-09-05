<?php

require_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../Model/ObserverN/HabitatObserver.php';

class HabitatControllerObserver {
    
     private $animalModel;
    

    public function __construct() {
        $this->animalModel = new AnimalModel();
        $habitatObserver = new HabitatObserver();
        $this->animalModel->attach($habitatObserver);
    }
    
      public function route(){
        $action = isset($_GET['action']) ? $_GET['action'] : 'home';

        switch ($action) {
            case 'home' :
                include '../../View/AnimalView/animal_home.php';
                break;
            case 'addHabitat':
                include '../../View/AnimalView/add_habitat.php';
                exit();
                break;
            case 'editHabitat':
                include '../../View/AnimalView/list_habitats.php';
                exit();
                break;
            case 'ViewHabitat':
                include '../../View/AnimalView/habitatViewOnly.php';
                exit();
                break;
            default:
                echo "Invalid action.";
                break;
        }
    }

    // Function to add or update a habitat based on ID
        public function saveHabitat($habitatData) {
        $availabilityOptions = array('Available', 'Unavailable');
        $environmentOptions = array('hot', 'cold', 'water');

        if (!in_array($habitatData['availability'], $availabilityOptions)) {
            throw new Exception('Invalid availability value');
        }

        if (!in_array($habitatData['environment'], $environmentOptions)) {
            throw new Exception('Invalid environment value');
        }

        if (!is_numeric($habitatData['capacity']) || $habitatData['capacity'] < 1) {
            throw new Exception('Invalid capacity value');
        }

        // Save habitat data
        if (isset($habitatData['habitat_id']) && !empty($habitatData['habitat_id'])) {
            // Update existing habitat
            $this->animalModel->updateHabitat(
                $habitatData['habitat_id'],
                $habitatData['habitat_name'],
                $habitatData['availability'],
                $habitatData['capacity'],
                $habitatData['environment'],
                $habitatData['description']
            );
        } else {
            // Insert new habitat
            $this->animalModel->insertNewHabitat(
                $habitatData['habitat_name'],
                $habitatData['availability'],
                $habitatData['capacity'],
                $habitatData['environment'],
                $habitatData['description']
            );
        }
        $this->animalModel->setHabitatData($habitatData); // Set habitatData on AnimalModel instance for observer notify
    }
    
        private function validateHabitatData($habitatData) {
        $errors = array();

        if (empty($habitatData['habitat_name'])) {
            $errors[] = 'Habitat name is required.';
        }

        if (empty($habitatData['availability'])) {
            $errors[] = 'Availability is required.';
        } elseif (!in_array($habitatData['availability'], array('Available', 'Unavailable'))) {
            $errors[] = 'Invalid availability value.';
        }

        if (empty($habitatData['capacity'])) {
            $errors[] = 'Capacity is required.';
        } elseif (!is_numeric($habitatData['capacity']) || $habitatData['capacity'] < 1) {
            $errors[] = 'Invalid capacity value.';
        }

        if (empty($habitatData['environment'])) {
            $errors[] = 'Environment is required.';
        } elseif (!in_array($habitatData['environment'], array('hot', 'cold', 'water'))) {
            $errors[] = 'Invalid environment value.';
        }

        if (empty($habitatData['description'])) {
            $errors[] = 'Description is required.';
        }

        return $errors;
    }

        public function handleFormSubmission() {
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                    $habitat_id = $_POST['habitat_id'];
                    $AnimalModel = new AnimalModel(); // assuming you have a HabitatModel class
                    $AnimalModel->deleteHabitat($habitat_id);
                    header('Location: ' . $_SERVER['PHP_SELF']); // redirect back to the list page
                    exit;
                } else {
                    $habitatData = $_POST;

                    $errors = $this->validateHabitatData($habitatData);

                    if (!empty($errors)) {
                        // Display error messages
                        foreach ($errors as $error) {
                            echo $error . '<br>';
                        }
                    } else {
                        $this->saveHabitat($habitatData);
                        header('Location: list_habitats.php');
                        exit;
                    }
                }
            }
        }

        // Function to fetch all habitats
        public function displayHabitats() {
            return $this->animalModel->getAllHabitats();
        }

       public function getHabitatById($habitat_id) {
        if (empty($habitat_id)) {
            throw new Exception("Habitat ID is required");
        }
        return $this->animalModel->getHabitatById($habitat_id);
    }

    
}

new HabitatControllerObserver();

