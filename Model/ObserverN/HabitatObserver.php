<?php 
require_once 'Observer.php';
class HabitatObserver implements Observer {
    public function update($subject) {
        if ($subject instanceof AnimalModel) {
            $habitatData = $subject->getHabitatData();
            echo "Habitat updated successfully.";
        }
    }
}