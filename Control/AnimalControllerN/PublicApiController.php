<?php
include_once '../../Model/ObserverN/AnimalModel.php';

class PublicApiController {
    private $model;

    public function __construct() {
        $this->model = new AnimalModel();
    }

    public function getAnimalHealthReports() {
        $data = $this->model->getAnimalHealthReports();
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
?>
