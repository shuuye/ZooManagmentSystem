<?php

require_once '../../Config/databaseConfig.php';
require_once 'subject.php';

class HabitatModel extends databaseConfig implements subject {
    private $observers = [];
    private $habitatData;

    // Observer methods
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

    // Set habitat data and notify observers
    public function setHabitatData($data) {
        $this->habitatData = $data;
        $this->notify();
    }

    public function getHabitatData() {
        return $this->habitatData;
    }

    // Function to insert a new habitat
    public function insertNewHabitat($habitat_name, $availability, $type, $capacity, $environment, $description) {
        try {
            $pdo = $this->connect();
            $sql = "INSERT INTO habitats (habitat_name, availability, type, capacity, environment, description) 
                    VALUES (:habitat_name, :availability, :type, :capacity, :environment, :description)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':capacity', $capacity);
            $stmt->bindParam(':environment', $environment);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "New habitat added successfully.";
            } else {
                echo "Failed to add new habitat.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Function to update an existing habitat
    public function updateHabitat($habitat_id, $habitat_name, $availability, $type, $capacity, $environment, $description) {
        try {
            $pdo = $this->connect();
            $sql = "UPDATE habitats SET habitat_name = :habitat_name, availability = :availability, type = :type,
                    capacity = :capacity, environment = :environment, description = :description 
                    WHERE habitat_id = :habitat_id";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':habitat_id', $habitat_id);
            $stmt->bindParam(':habitat_name', $habitat_name);
            $stmt->bindParam(':availability', $availability);
            $stmt->bindParam(':type', $type);
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
            $pdo = $this->connect();
            $sql = "SELECT * FROM habitats";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}


