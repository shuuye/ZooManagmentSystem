<?php

require_once '../../Model/Observer/AnimalManagementSystem.php';

class ObserverController {

    private $animalManagementSystem;

    public function __construct() {
        $this->animalManagementSystem = new AnimalManagementSystem();
    }

    // Function to attach observers
    public function attachObserver($observer) {
        $this->animalManagementSystem->attach($observer);
    }

    // Function to detach observers
    public function detachObserver($observer) {
        $this->animalManagementSystem->detach($observer);
    }

    // Function to notify observers (can be called after an animal or habitat update)
    public function notifyObservers() {
        $this->animalManagementSystem->notify();
    }
}

?>
