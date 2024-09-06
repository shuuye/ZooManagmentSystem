<?php
include_once 'Observer.php';
require_once '../../Model/ObserverN/AnimalModel.php';

class HealthObserver implements Observer {

 public function update($subject) {
        // Check if the subject is an AnimalModel object
        if ($subject instanceof AnimalModel) {
            // Access the animal ID from the AnimalModel object
            $animalId = $subject->getId();
            // Check if the update is for a health record
            if (isset($subject->healthRecordId)) {
                echo "HealthObserver: Health record with ID {$subject->healthRecordId} for animal with ID $animalId has been updated.";
            } else {
                echo "Health Observer notify: Animal has successfully added new health records.";
            }
            
            // Redirect to the animal_home.php page after successful operation
            header("Location: list_healthRecords.php");
            exit();
        }
    }

}
?>

