<?php

require_once 'Observer.php';

class HabitatObserver implements Observer {
    public function update($animalModel) {
        $habitatData = $animalModel->getHabitatData();
        echo "New habitat added which is Habitat ID " . $habitatData['habitat_id'];
    }
}
