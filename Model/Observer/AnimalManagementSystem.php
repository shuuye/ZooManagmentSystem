
<?php

// AnimalManagementSystem.php
// This is Subject Class of Observer Design Pattern.
require_once '../../Config/databaseConfig.php';
require_once '../../Model/Command/AnimalInventory.php';
require_once 'Habitat.php';
require_once 'subject.php';

//require_once 'Animal.php';

class AnimalManagementSystem implements Subject {

    private $observers = array();
    private $animals = array(); // This will be a list of all animals
    private $habitats = array(); // This will be a list of all habitats

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
    
    public function getHabitats() {
        return $this->habitats;
    }

     public function addHabitat(Habitat $habitat) {
        $this->habitats[] = $habitat;
        $this->notify();
    }

    
    // Method to load all animals from the database
    public function loadAnimalsFromDatabase() {
         $db = new databaseConfig();
         $pdo = $db->getConnection();

        $stmt = $pdo->query("SELECT * FROM animalinventory"); // Adjust table name if necessary
        $results = $stmt->fetchAll();

        foreach ($results as $row) {
            $animal = new AnimalInventory(
                $row['id'],
                $row['name'],
                $row['species'],
                $row['subspecies'],
                $row['categories'],
                $row['age'],
                $row['gender'],
                $row['date_of_birth'],
                $row['avg_lifespan'],
                $row['description'],
                $row['height'],
                $row['weight'],
                $row['healthStatus'],
                $row['habitat_id']
            );
            $this->animals[] = $animal;
        }
        // Notify observers that the animal list has been updated
        $this->notify();
    }


    // Method to display animals by category
    public function displayAnimalsByCategory($categories) {
        $animals = $this->getAnimalsByCategory($categories);

        if (empty($animals)) {
            echo "No animals found in the categories: $categories.";
            return;
        }

        foreach ($animals as $animal) {
            echo "ID: " . $animal->getId() . "<br>";
            echo "Name: " . $animal->getName() . "<br>";
            echo "Species: " . $animal->getSpecies() . "<br>";
            echo "Subspecies: " . $animal->getSubspecies() . "<br>";
            echo "Categories: " . $animal->getCategories() . "<br>";
            echo "Age: " . $animal->getAge() . "<br>";
            echo "Gender: " . $animal->getGender() . "<br>";
            echo "Date of Birth: " . $animal->getDateOfBirth() . "<br>";
            echo "Average Lifespan: " . $animal->getAvgLifespan() . "<br>";
            echo "Description: " . $animal->getDescription() . "<br>";
            echo "Height: " . $animal->getHeight() . "<br>";
            echo "Weight: " . $animal->getWeight() . "<br>";
            echo "Health Status: " . $animal->getHealthStatus() . "<br>";
            echo "Habitat ID: " . $animal->getHabitatId() . "<br><br>";
        }
    }
    
   
     // Method to load all habitats from the database
    public function loadHabitatsFromDatabase() {
        $db = new databaseConfig();
        $pdo = $db->getConnection();

        $stmt = $pdo->query("SELECT * FROM habitats"); // Adjust table name if necessary
        $results = $stmt->fetchAll();

        foreach ($results as $row) {
            // Assuming you have a Habitat class with a constructor that takes database row data
            $habitat = new Habitat($row['habitat_id'], $row['habitat_name'], $row['availability'], $row['capacity'], $row['environment'], $row['description']);
            $this->habitats[] = $habitat;
        }

        // Notify observers that the habitat list has been updated
        $this->notify();
    }
   
    
    
}

?>
