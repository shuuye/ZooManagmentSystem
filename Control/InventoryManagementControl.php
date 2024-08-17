<?php
require_once '../Model/Factory/FoodInventoryFactory.php';
require_once '../Model/Factory/HabitatInventoryFactory.php';
require_once '../Model/Factory/CleaningInventoryFactory.php';

echo 'start <br>';

$FoodInventory = new FoodInventoryFactory();
$meat = $FoodInventory->createItem();

echo '<br>end<br>';

echo "lets add stock<br>";
$meat->addStock();
echo '<br>end<br>';


  