<?php

require_once '../../Model/ObserverN/Observer.php';
require_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../View/AnimalView/habitatView.php';

class HabitatControllerObserver implements Observer {
    private $model;
    private $view;
    

    public function __construct(AnimalModel $model, AimalView $view) {
        $this->model = $model;
        $this->view = $view;
        $this->model->attach($this);  // Attach the controller as an observer
    }

    public function update($subject) {
        if ($subject === $this->model) {
            $this->view->renderHabitat($subject->getHabitatData());
        }
    }

    // Function to add or update a habitat based on ID
    public function saveHabitat($habitatData) {
        if (isset($habitatData['habitat_id']) && !empty($habitatData['habitat_id'])) {
            // Update existing habitat
            $this->model->updateHabitat(
                $habitatData['habitat_id'],
                $habitatData['habitat_name'],
                $habitatData['availability'],
                $habitatData['type'],
                $habitatData['capacity'],
                $habitatData['environment'],
                $habitatData['description']
            );
        } else {
            // Insert new habitat
            $this->model->insertNewHabitat(
                $habitatData['habitat_name'],
                $habitatData['availability'],
                $habitatData['type'],
                $habitatData['capacity'],
                $habitatData['environment'],
                $habitatData['description']
            );
        }
    }

    // Function to fetch all habitats and render them
    public function displayHabitats() {
        $habitats = $this->model->getAllHabitats();
        $this->view->renderHabitatsList($habitats);
    }
}
