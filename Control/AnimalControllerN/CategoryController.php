<?php
include_once '../../Model/ObserverN/AnimalModel.php';
class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new AnimalModel();
    }

    public function getCategoryCounts() {
        $data = $this->model->getCategoryCounts();
        header('Content-Type: application/json');
        echo json_encode($data);
    }
  

}
?>
