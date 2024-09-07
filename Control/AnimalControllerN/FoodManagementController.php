<?php

include_once '../../Model/ObserverN/AnimalModel.php';

class FoodManagementController {
    private $model;

    public function __construct() {
        $this->model = new AnimalModel();
    }
    
    public function getAnimalIds() {
        return $this->model->getAllAnimalIds();
    }
    
    public function getFoodItem(){
        return $this->model->getFoodInventory();
    }
    
    public function getAllFeedingRecords() {
        return $this->model->getAllFeedingRecords();
    }
  
    public function addFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed) {
        $this->model->addOrUpdateFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed);
        header('Location: ../../View/AnimalView/feeding_report.php');
        exit('Redirecting to Feeding Report...');
    }


    public function showConsumptionPatterns($animal_id, $start_date, $end_date) {
        $patterns = $this->model->getConsumptionPatterns($animal_id, $start_date, $end_date);
        include '../../View/AnimalView/consumption_patterns.php';
    }

    public function addConsumptionRecord($animal_id, $total_quantity_fed, $start_date, $end_date) {
        $this->model->addConsumptionRecord($animal_id, $total_quantity_fed, $start_date, $end_date);
        // Redirect or show a success message
        header('Location: ../../View/AnimalView/consumption_patterns.php');
        exit();
    }

    public function handleAddfeedingRecordForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['form_type'] === 'feeding_report') {
            $animal_id = $_POST['animal_id'];
            $food_id = $_POST['food_id'];
            $feeding_time = $_POST['feeding_time'];
            $quantity_fed = $_POST['quantity_fed'];

            $this->addFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed);
        }
    }
}
?>
