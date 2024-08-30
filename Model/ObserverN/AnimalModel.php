<?php

//This model represents the data for an animal. When the animal's information changes, it will notify all observers.


require_once '../../Config/databaseConfig.php';
require_once 'subject.php';

class AnimalModel extends databaseConfig implements subject {
    private $observers = [];
    private $animalData;

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function setAnimalData($data) {
        $this->animalData = $data;
        $this->notify();  // Notify all observers about the change
    }

    public function getAnimalData() {
        return $this->animalData;
    }
}
