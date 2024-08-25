<?php

require_once 'HabitatObserver.php';
require_once '../../Config/AnimalDB/dbConnection.php';

class Habitat {
    private $observers = [];
    private $habitatId;
    private $name;
    private $cleaningStatus;
    private $availability;
    private $pdo;

    public function __construct($habitatId = null, $name = null, $cleaningStatus = 'Clean', $availability = 'Available') {
        $this->habitatId = $habitatId;
        $this->name = $name;
        $this->cleaningStatus = $cleaningStatus;
        $this->availability = $availability;
        $db = new dbConnection();
        $this->pdo = $db->getPDO();
    }

    // Observer pattern: Attach observers
    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    // Observer pattern: Notify all observers of state change
    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    // Getters and Setters
    public function getId() {
        return $this->habitatId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        $this->notify();
    }

    public function getCleaningStatus() {
        return $this->cleaningStatus;
    }

    public function setCleaningStatus($cleaningStatus) {
        $this->cleaningStatus = $cleaningStatus;
        $this->notify();
    }

    public function getAvailability() {
        return $this->availability;
    }

    public function setAvailability($availability) {
        $this->availability = $availability;
        $this->notify();
    }

    // Function to add a new habitat
    public function addHabitat($habitat_name, $availability) {
        $sql = "INSERT INTO habitats (habitat_name, availability) VALUES (:habitat_name, :availability)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['habitat_name' => $habitat_name, 'availability' => $availability]);

        return $this->pdo->lastInsertId();  // Return the last inserted ID for confirmation
    }

    // Function to edit an existing habitat
    public function editHabitat($habitat_id, $habitat_name, $availability) {
        $sql = "UPDATE habitats SET habitat_name = :habitat_name, availability = :availability WHERE habitat_id = :habitat_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'habitat_id' => $habitat_id,
            'habitat_name' => $habitat_name,
            'availability' => $availability
        ]);

        return $stmt->rowCount();  // Return the number of affected rows
    }

    // Function to fetch all habitats
    public function getAllHabitats() {
        $sql = "SELECT * FROM habitats";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    // Function to fetch a single habitat by ID
    public function getHabitatById($habitat_id) {
        $sql = "SELECT * FROM habitats WHERE habitat_id = :habitat_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['habitat_id' => $habitat_id]);
        return $stmt->fetch();
    }
}
?>
