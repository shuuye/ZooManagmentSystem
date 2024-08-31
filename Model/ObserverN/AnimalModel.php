<?php

//This model represents the data for an animal. When the animal's information changes, it will notify all observers.
include_once '../../Config/databaseConfig.php';
require_once '../../Model/Inventory/InventoryModel.php';
require_once 'subject.php';
require_once 'HealthObserver.php';

class AnimalModel extends databaseConfig implements subject{

    private $db;
    private $observers = array(); // Observer pattern methods
    private $inventoryModel;
    private $habitatData;
    
    public function __construct() {
        $this->db = new databaseConfig();
        $this->inventoryModel = new InventoryModel();
        // Add observers
        $this->attach(new HealthObserver());
    }
    
    // Observer methods ---------------------------------------------------------------------
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    
    private function notifyObservers($animalId) { // need to remove , this is the animal parts
        foreach ($this->observers as $observer) {
            $observer->update($animalId);
        }
    }
    
    
  // Setter and Getter-------------------------------------------------------------------------
  
    // Set habitat data and notify observers
    public function setHabitatData($data) {
        $this->habitatData = $data;
        $this->notify();
    }

    public function getHabitatData() {
        return $this->habitatData;
    }
    
    
    // Animal function -------------------------------------------------------------------------------------------------------------------
    public function addNewAnimal($animalName, $species, $height, $weight, $habitatId, $healthStatus, $quantity) {
        // Step 1: Add the animal to the Inventory table
        $itemType = 'Animal';
        $supplierId = 4; // Default value
        $storageLocation = 'Enclose'; // Default value
        $reorderThreshold = 5; // Default value
        $this->inventoryModel->addInventoryIntoDB($animalName, $itemType, $supplierId, $storageLocation, $reorderThreshold, $quantity);

        // Step 2: Retrieve the inventoryId for the newly added animal
        $inventoryId = $this->inventoryModel->getInventoryIdByName($animalName);

        // Check if inventoryId was successfully retrieved
        if (!$inventoryId) {
            throw new Exception("Failed to retrieve inventoryId for animal: $animalName");
        }

        // Step 3: Add the animal details to the animalinventory table
        $query = "INSERT INTO animalinventory (inventoryId, species, height, weight, habitat_id, healthStatus) 
                  VALUES (:inventoryId, :species, :height, :weight, :habitat_id, :healthStatus)";
        $stmt = $this->db->getConnection()->prepare($query);

        // Bind parameters
        $stmt->bindParam(':inventoryId', $inventoryId, PDO::PARAM_INT);
        $stmt->bindParam(':species', $species, PDO::PARAM_STR);
        $stmt->bindParam(':height', $height, PDO::PARAM_STR);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_STR);
        $stmt->bindParam(':habitat_id', $habitatId, PDO::PARAM_INT);
        $stmt->bindParam(':healthStatus', $healthStatus, PDO::PARAM_STR);

        // Execute query
        $stmt->execute();

        // Notify observers
        $this->notifyObservers($inventoryId);

        // Return success
        return true;
    }

   public function getAnimalsByCategory($category = null) {
        $query = "SELECT * FROM animalinventory";
        if ($category) {
            $query .= " WHERE categories = :category";
        }
        $query .= " ORDER BY species ASC";

        $stmt = $this->db->getConnection()->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Function for habitat-------------------------------------------------------------------------------------------------------
    // Function to insert a new habitat
    public function insertNewHabitat($habitat_name, $availability, $capacity, $environment, $description) {
        try {
            $pdo = $this->db->getConnection();
            $sql = "INSERT INTO habitats (habitat_name, availability, capacity, environment, description) 
                    VALUES (:habitat_name, :availability, :capacity, :environment, :description)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':environment', $environment);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "New habitat added successfully.";
                header('Location: list_habitats.php');
            } else {
                echo "Failed to add new habitat.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function getHabitatById($habitat_id) {
      try {
          $pdo = $this->db->getConnection();
          $sql = "SELECT * FROM habitats WHERE habitat_id = :habitat_id";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':habitat_id', $habitat_id);
          $stmt->execute();
          $habitat = $stmt->fetch(PDO::FETCH_ASSOC);
          if (!$habitat) {
              throw new Exception("Habitat not found with ID: $habitat_id");
          }
          return $habitat;
      } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
      }
  }
    // Function to update an existing habitat
    public function updateHabitat($habitat_id, $habitat_name, $availability, $capacity, $environment, $description) {
        try {
            $pdo = $this->db->getConnection();
            $sql = "UPDATE habitats SET habitat_name = :habitat_name, availability = :availability,
                    capacity = :capacity, environment = :environment, description = :description 
                    WHERE habitat_id = :habitat_id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_id', $habitat_id);
            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':environment', $environment);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "Habitat updated successfully.";
                
            } else {
                echo "Failed to update habitat.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Function to get all habitats
    public function getAllHabitats() {
        try {
            $pdo = $this->db->getConnection();
            $sql = "SELECT * FROM habitats";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function deleteHabitat($habitat_id) {
        $sql = "DELETE FROM habitats WHERE habitat_id = :habitat_id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':habitat_id', $habitat_id);
        return $stmt->execute();
    }
    
    
    // health function ----------------Use Xml and database update both-----------------------------------------------------------------------------
    
}
?>

