
<?php


// AnimalManagementSystem.php
// This is Subject Class of Observer Design Pattern.


class AnimalManagementSystem implements Subject {
    private $observers = array();
    private $animals = array(); // This will be a list of all animals

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        foreach ($this->observers as $key => $o) {
            if ($o === $observer) {
                unset($this->observers[$key]);
            }
        }
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    public function addAnimal(Animal $animal) {
        $this->animals[] = $animal;
        $this->notify();
    }

    public function getAnimals() {
        return $this->animals;
    }
}
?>
