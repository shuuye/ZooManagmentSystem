<?php

include_once '../../Model/ObserverN/AnimalModel.php';

class FoodManagementController {
    private $model;
    private ?AnimalInventory $AnimalInventory;
    private ?FoodInventory $FoodInventory;
    private $AnimalFood = [];
    private $FoodList = [];

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
        $animal_id = $this->sanitize_input($animal_id);
        $food_id = $this->sanitize_input($food_id);
        $feeding_time = $this->sanitize_input($feeding_time);
        $quantity_fed = $this->sanitize_input($quantity_fed);

        // Validate the quantity (must be a positive number)
        if (!is_numeric($quantity_fed) || $quantity_fed <= 0) {
            die('Invalid quantity fed.');
        }

        $this->model->addOrUpdateFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed);
        header('Location: ../../View/AnimalView/feeding_report.php');
        exit('Redirecting to Feeding Report...');
    }
    
    public function handleAddFeedingRecordForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'feeding_report') {
            $animal_id = isset($_POST['animal_id']) ? $_POST['animal_id'] : '';
            $food_id = isset($_POST['food_id']) ? $_POST['food_id'] : '';
            $feeding_time = isset($_POST['feeding_time']) ? $_POST['feeding_time'] : '';
            $quantity_fed = isset($_POST['quantity_fed']) ? $_POST['quantity_fed'] : '';

            $this->addFeedingRecord($animal_id, $food_id, $feeding_time, $quantity_fed);
        }
    }

    private function sanitize_input($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
?>
