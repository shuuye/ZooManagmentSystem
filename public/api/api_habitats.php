<?php
// public/api/habitats.php

require_once '../../config/databaseConfig.php'; 
require_once '../../Model/ObserverN/AnimalModel.php';
require_once '../../Control/AnimalcontrollerN/HabitatControllerObserver.php';

// Initialize database connection
$dbConfig = new databaseConfig();
$db = $dbConfig->getConnection();
$habitatModel = new AnimalModel($db);
$habitatController = new HabitatControllerObserver($habitatModel);

// Route requests
if (isset($_GET['habitat_id'])) {
    $habitat_id = $_GET['habitat_id'];
    $habitatController->getHabitatById2($habitat_id);
} else {
    $habitatController->getAllHabitats();
}

