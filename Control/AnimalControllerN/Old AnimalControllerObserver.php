<?php

//This controller will update the model and act as an observer, responding to changes in the model.

require_once '../../Model/ObserverN/Observer.php';
require_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../View/AnimalView/animalView.php';

class AnimalControllerObserver implements Observer {
    private $model;
    private $view;

    public function __construct(AnimalModel $model, animalView $view) {
        $this->model = $model;
        $this->view = $view;
        $this->model->attach($this);  // Attach controller as an observer
    }

    public function update($subject) {
        // Handle the update
        if ($subject === $this->model) {
            $this->view->render($subject->getAnimalData());
        }
    }

    public function updateAnimal($data) {
        $this->model->setAnimalData($data);
    }
}


