
<?php

require_once 'Observer.php';

class HealthObserver implements Observer {
    public function update(subject $subject) {
        if ($subject instanceof AnimalModel) {
            $healthRecordId = $subject->getHealthRecordId();
            echo "\n Health Records updated successfully.\n";
        }
    }
}




