<?php

require_once '../../Model/Observer/Observer.php';

class animalView implements Observer {
    public function update($subject) { // function from observer model
        $this->render($subject->getAnimalData());
    }

    public function render($data) {
        echo "Animal Data: " . $data;
    }
}
