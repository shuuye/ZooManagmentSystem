<?php
require_once '..\..\Config\AnimalDB\dbConnection.php';
require_once '..\..\Model\Observer\HealthRecord.php';
require_once '..\..\Model\Observer\HealthObserver.php';

class HealthController {
    private $observer;

    public function __construct() {
        $this->observer = new HealthObserver();
    }

    // Method to check health of an animal
    public function checkHealth($animal_id) {
        $healthRecord = new HealthRecord($animal_id);
        $this->observer->update($healthRecord);
        return $healthRecord;
    }

    // Method to update an existing health record
    public function updateHealth($animal_id, $last_checkup, $treatments, $condition) {
        $healthRecord = new HealthRecord($animal_id);
        $healthRecord->updateHealthRecord($last_checkup, $treatments, $condition);
        $this->observer->update($healthRecord);
    }

    // Method to add a new health record
    public function addHealthRecord($animal_id, $last_checkup, $treatments, $condition) {
        $db = new dbConnection();
        $pdo = $db->getPDO();

        $stmt = $pdo->prepare("INSERT INTO health_records (animal_id, last_checkup, treatments, ani_condition) VALUES (?, ?, ?, ?)");
        $stmt->execute([$animal_id, $last_checkup, $treatments, $condition]);

        // Update the observer with the new record
        $healthRecord = new HealthRecord($animal_id);
        $this->observer->update($healthRecord);
    }
}
?>
