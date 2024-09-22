<?php

require_once '../../Model/ObserverN/AnimalModel.php';

class HabitatControllerObserver {
    
    private $animalModel;
    private ?HabitatInventory $HabitatInventory;
    private $habitatList = [];
    

    public function __construct() {
        $this->animalModel = new AnimalModel();
    }

    // Function to add or update a habitat based on ID
        public function saveHabitat($habitatData) {
          
        $habitatData = array_map('htmlspecialchars', $habitatData);// Sanitize input data, Memory Management
           
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
        
        $habitatData['description'] = substr($habitatData['description'], 0, 12); // Truncate input strings
        
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
        
        unset($habitatData);// Free memory, Memory Management
        header('Location: ../../View/AnimalView/habitatViewOnly.php');
        exit();
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
            
             // Check if the habitat can be deleted
           if ($AnimalModel->hasAnimalsInHabitat($habitat_id)) {
                $_SESSION['error_message'] = "This Habitat still has animals and cannot be removed currently.";
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }

            // Proceed with deletion if no animals are associated
            $AnimalModel->deleteHabitat($habitat_id);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $habitatData = $_POST;
            $errors = $this->validateHabitatData($habitatData);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo $error . '<br>';
                }
            } else {
                $this->saveHabitat($habitatData);
                header('Location: ../../View/AnimalView/habitatViewOnly.php');
                exit();
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

