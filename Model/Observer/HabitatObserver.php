<?php
require_once 'Observer.php';

class HabitatObserver implements Observer {
    public function update($subject) {
        // Logic to handle updates when a habitat is added or edited
        echo "Habitat has been updated: " . $subject->getName();
    }
}


