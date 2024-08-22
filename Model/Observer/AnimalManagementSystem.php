
<?php


// AnimalManagementSystem.php
// This is Subject Class of Observer Design Pattern.
require_once '../../Config/AnimalDB/dbConnection.php';
require_once '../../Model/Command/AnimalInventory.php';
require_once 'subject.php';
//require_once 'Animal.php';

class AnimalManagementSystem implements Subject {
    private $observers = array();
    private $animals = array(); // This will be a list of all animals

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        foreach ($this->observers as $key => $o) {
            if ($o === $observer) {
                unset($this->observers[$key]);
            }
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function addAnimal(Animal $animal) {
        $this->animals[] = $animal;
        $this->notify();
    }

    public function getAnimals() {
        return $this->animals;
    }
    
    // Method to load all animals from the database
    public function loadAnimalsFromDatabase() {
        $db = new dbConnection();
        $pdo = $db->getPDO();

        $stmt = $pdo->query("SELECT * FROM animals"); // Adjust table name if necessary
        $results = $stmt->fetchAll();

        foreach ($results as $row) {
            // Assuming you have an Animal class with a constructor that takes database row data
            $animal = new AnimalInventory(
            $row['animal_id'], 
            $row['animal_name'], 
            $row['category'],
            $row['species'], 
            $row['age'], 
            $row['gender'], 
            $row['description']
            
        );
            $this->animals[] = $animal;
        }
        // Notify observers that the animal list has been updated
        $this->notify();
    }
    
}
?>
