<?php

//This model represents the data for an animal. When the animal's information changes, it will notify all observers.
include_once '../../Config/databaseConfig.php';
require_once '../../Model/Inventory/InventoryModel.php';
require_once 'subject.php';
require_once 'HealthObserver.php';

class AnimalModel extends databaseConfig {

    private $db;
    private $observers = array(); // Observer pattern methods
    private $inventoryModel;
    
    public function __construct() {
        $this->db = new databaseConfig();
        $this->inventoryModel = new InventoryModel();
        // Add observers
        $this->addObserver(new HealthObserver());
    }
    
    
    public function addObserver($observer) {
        $this->observers[] = $observer;
    }

    private function notifyObservers($animalId) {
        foreach ($this->observers as $observer) {
            $observer->update($animalId);
        }
    }

    public function addNewAnimal($animalName, $species, $height, $weight, $habitatId, $healthStatus, $supplierId, $storageLocation, $reorderThreshold, $quantity) {
        try {
            // Step 1: Add the animal to the Inventory table
            $itemType = 'animal';
            $lastInventoryId = $this->inventoryModel->addInventoryIntoDB($animalName, $itemType, $supplierId, $storageLocation, $reorderThreshold, $quantity);

            // Step 2: Add the animal details to the animalinventory table
            $query = "INSERT INTO animalinventory (inventory_id, species, height, weight, habitat_id, health_status) VALUES (:inventory_id, :species, :height, :weight, :habitat_id, :health_status)";
            $stmt = $this->db->getConnection()->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(':inventory_id', $lastInventoryId, PDO::PARAM_INT);
            $stmt->bindParam(':species', $species, PDO::PARAM_STR);
            $stmt->bindParam(':height', $height, PDO::PARAM_STR);
            $stmt->bindParam(':weight', $weight, PDO::PARAM_STR);
            $stmt->bindParam(':habitat_id', $habitatId, PDO::PARAM_INT);
            $stmt->bindParam(':health_status', $healthStatus, PDO::PARAM_STR);
            
            // Execute query
            $stmt->execute();
            
            // Notify observers
            $this->notifyObservers($lastInventoryId);
            
            // Return success
            return true;
        } catch (PDOException $e) {
            // Handle errors
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
//            header("Location: /ZooManagementSystem/userLoginPage.php");
            echo 'add new animal fail';
            exit();
        }
    }
    
    public function getAnimals($category = null) {
        $query = "SELECT * FROM animalinventory";
        if ($category) {
            $query .= " WHERE $categories = :$categories";
        }
        $query .= " ORDER BY animalName ASC";

        $stmt = $this->db->getConnection()->prepare($query);
        if ($category) {
            $stmt->bindParam(':category', $categories);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>

