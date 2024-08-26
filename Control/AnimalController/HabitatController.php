<?php

require_once '../../Model/Observer/AnimalManagementSystem.php';
require_once '../../Model/Observer/HabitatObserver.php';

class HabitatController {
    private $habitatManagementSystem;

    public function __construct() {
        $this->habitatManagementSystem = new AnimalManagementSystem();

        // Attach observers (e.g., for logging or UI updates)
        $this->habitatManagementSystem->attach(new HabitatObserver());

        // Load all habitats from the database
        $this->habitatManagementSystem->loadHabitatsFromDatabase();
    }

    public function getHabitats() {
        return $this->habitatManagementSystem->getHabitats();
    }
    
    public function addHabitat($name, $availability, $capacity, $environment, $description) {
        $db = new databaseConfig();
        $pdo = $db->getConnection();

        // Insert the new habitat into the database
        $stmt = $pdo->prepare("INSERT INTO habitats (habitat_name, availability, capacity, environment, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $availability , $capacity, $environment, $description]);

        // After adding the habitat, reload habitats from the database
        $this->habitatManagementSystem->loadHabitatsFromDatabase();
    }
}
?>
