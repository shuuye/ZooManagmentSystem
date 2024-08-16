<?php

require_once 'Inventory.php';
require_once 'InventoryCommand.php';

class FoodInventory extends Inventory {
    private $foodType;
    private $nutritionInfo;

    public function __construct($id, $name, $quantity, $foodType, $nutritionInfo) {
        parent::__construct($id, $name, $quantity);
        $this->foodType = $foodType;
        $this->nutritionInfo = $nutritionInfo;
    }

    public function getFoodType() {
        return $this->foodType;
    }

    public function getNutritionInfo() {
        return $this->nutritionInfo;
    }

    public function toString() {
        return "Food Item: " . $this->name . "<br>" .
               "Food Type: " . $this->foodType . "<br>" .
               "Expiration Date: " . $this->expirationDate . "<br>" .
               "Nutrition Info: " . $this->nutritionInfo . "<br>" .
               "Quantity: " . $this->quantity . "<br>";
    }
}
