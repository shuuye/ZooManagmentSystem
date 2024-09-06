<?php 

require_once 'Observer.php';
require_once '../../Model/ObserverN/AnimalModel.php';

class HabitatObserver implements Observer {
    public function update($subject) {
        if ($subject instanceof AnimalModel) {
            $habitatData = $subject->getHabitatData();
            // Send success message
            echo "Habitat updated successfully.";
        }
    }
}
