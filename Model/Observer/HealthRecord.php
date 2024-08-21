<?php
require_once '..\..\Config\AnimalDB\dbConnection.php';

class HealthRecord {
    private $animal_id;
    public $last_checkup;
    public $treatments;
    public $condition;

    public function __construct($animal_id) {
        $this->animal_id = $animal_id;
        $this->fetchHealthRecord();
    }

    // Fetch the health record from the database
    private function fetchHealthRecord() {
        $db = new dbConnection();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM health_records WHERE animal_id = ?");
        $stmt->execute([$this->animal_id]);

        $record = $stmt->fetch();

        if ($record) {
            $this->last_checkup = $record['last_checkup'];
            $this->treatments = $record['treatments'];
            $this->condition = $record['ani_condition'];
        } else {
            $this->last_checkup = null;
            $this->treatments = null;
            $this->condition = null;
        }
    }

    // Update the health record
    public function updateHealthRecord($last_checkup, $treatments, $condition) {
        $db = new dbConnection();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("UPDATE health_records SET last_checkup = ?, treatments = ?, ani_condition = ? WHERE animal_id = ?");
        $stmt->execute([$last_checkup, $treatments, $condition, $this->animal_id]);

        // Update the object properties
        $this->last_checkup = $last_checkup;
        $this->treatments = $treatments;
        $this->condition = $condition;
    }

    // Getters for health record properties
    public function getLastCheckup() {
        return $this->last_checkup;
    }

    public function getTreatments() {
        return $this->treatments;
    }

    public function getCondition() {
        return $this->condition;
    }
    
   public function getAnimalId() {
    return $this->animal_id;
}


}
?>
