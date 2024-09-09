
<?php

require_once 'Observer.php';

class HealthObserver implements Observer {
    public function update(subject $subject) {
        if ($subject instanceof AnimalModel) {
            $healthRecordId = $subject->getHealthRecordId();
            echo "Health Record (ID: $healthRecordId) has been updated.";
        }
    }
}




