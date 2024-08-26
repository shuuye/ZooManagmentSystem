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
    
    public function editHabitat($id, $name, $availability, $capacity, $environment, $description) {
    $db = new databaseConfig();
    $pdo = $db->getConnection();

    // Update the habitat in the database
    $stmt = $pdo->prepare("UPDATE habitats SET habitat_name = ?, availability = ?, capacity = ?, environment = ?, description = ? WHERE habitat_id = ?");
    $stmt->execute([$name, $availability, $capacity, $environment, $description, $id]);

    // After updating the habitat, reload habitats from the database
    $this->habitatManagementSystem->loadHabitatsFromDatabase();
}

    public function getHabitatById($id) {
        $db = new databaseConfig();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("SELECT * FROM habitats WHERE habitat_id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        if ($result) {
            $habitat = new Habitat(
                $result['habitat_id'],
                $result['habitat_name'],
                $result['availability'],
                $result['capacity'],
                $result['environment'],
                $result['description']
            );
            return $habitat;
        } else {
            return null;
        }
    }
    
    public function displayAvailableHabitats() {
    $db = new databaseConfig();
    $pdo = $db->getConnection();

    // Query to select available habitats
    $stmt = $pdo->prepare("SELECT * FROM habitats WHERE availability = 'Available'");
    $stmt->execute();

    // Fetch all available habitats
    $habitats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $habitats;
}


}
?>
