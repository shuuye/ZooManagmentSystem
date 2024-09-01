<?php 

require_once 'Observer.php';
require_once '../../Model/ObserverN/AnimalModel.php';

class HabitatObserver implements Observer {
    public function update($subject) {
        
        if ($subject instanceof AnimalModel) {
             $habitatData = $subject->getHabitatData();
             echo "Habitat added which is Habitat ID " . $habitatData['habitat_id'];
        }
       
    }
}
