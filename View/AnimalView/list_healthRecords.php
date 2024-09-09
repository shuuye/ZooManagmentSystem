<?php
// Include the HealthRecordController
include_once '../../Control/AnimalControllerN/HealthController.php';

// Create an instance of HealthRecordController
$controller = new HealthController();

$controller->displayHealthRecords();
// Handle the request
$controller->handleAddHealthRecordForm();
?>
