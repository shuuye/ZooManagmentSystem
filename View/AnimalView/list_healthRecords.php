<?php
// Include the HealthRecordController
include_once '../../Control/AnimalControllerN/HealthController.php';

// Create an instance of HealthRecordController
$controller = new HealthController();

// Handle the request
$controller->handleRequest();
?>
