<?php
require_once 'Observer.php';

class HealthObserver implements Observer {
    public function update($subject) {
        // Assuming $subject is an instance of HealthRecord
        if ($subject instanceof HealthRecord) {
            // Handle the HealthRecord update
            echo "Health record for animal ID: " . $subject->getAnimalId() . " has been updated.";
        } else {
            // Handle error or other logic
            echo "Invalid subject type.";
        }
    }
}
?>
