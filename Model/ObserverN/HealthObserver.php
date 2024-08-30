<?php
include_once 'Observer.php';

class HealthObserver implements Observer {

    public function update($animalId) {
        // Handle the update, e.g., log the event or trigger other actions
        // For example, update health-related information for the animal
        echo "HealthObserver: Animal with ID $animalId has been added.";
    }
}
?>

